<?php

namespace App\Tests\Controller\Backend;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryControllerTest.
 */
class CategoryControllerTest extends WebTestCase
{
    public $client;

    public function setUp()
    {
        $this->client = static::createClient([], [
         'PHP_AUTH_USER' => 'tomek_admin@test.pl',
         'PHP_AUTH_PW' => 'admin',
     ]);
    }

    /**
     * @dataProvider getUrlsForUsers
     *
     * @param string $httpMethod
     * @param string $url
     */
    public function testAccessDeniedForStudents(string $httpMethod, string $url)
    {
        $client_student = static::createClient([], [
            'PHP_AUTH_USER' => 'kuba_student@test.pl',
            'PHP_AUTH_PW' => 'student',
        ]);
        $client_student->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client_student->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider getUrlsForUsers
     *
     * @param string $httpMethod
     * @param string $url
     */
    public function testAccessForEditors(string $httpMethod, string $url)
    {
        $client_student = static::createClient([], [
            'PHP_AUTH_USER' => 'oxygen_editor@test.pl',
            'PHP_AUTH_PW' => 'editor',
        ]);
        $client_student->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_OK, $client_student->getResponse()->getStatusCode());
    }

    /**
     * @return \Generator
     */
    public function getUrlsForUsers()
    {
        yield ['GET', '/panel/blog/kategorie'];
        yield ['GET', '/panel/blog/kategorie/kategoria'];
//        yield ['GET', '/panel/blog/kategorie/usun/testing-category/vwuDpAvTxkw23M-9aLOtcLr6eyxmLEY3qys-9YF8WhI'];
    }

    public function testAdminCategoryList()
    {
        $crawler = $this->client->request('GET', '/panel/blog/kategorie');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(
            5,
            $crawler->filter('td')->count(),
            'The backend category list displays all the available categories.'
        );
    }

    public function testAdminCreateNewCategory()
    {
        $categoryName = 'Category Name '.mt_rand();

        $crawler = $this->client->request('GET', '/panel/blog/kategorie/kategoria');
        $form = $crawler->selectButton('Zapisz')->form([
            'taxonomy[name]' => $categoryName,
        ]);
        $this->client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $category = $this->client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy([
            'name' => $categoryName,
        ]);
        $this->assertNotNull($category);
        $this->assertSame($categoryName, $category->getName());
    }

    public function testAdminEditCategory()
    {
        $newBlogCategoryName = 'Blog Category Name '.mt_rand();
        $crawler = $this->client->request('GET', '/panel/blog/kategorie/kategoria/testing-category');
        $form = $crawler->selectButton('Zapisz')->form([
            'taxonomy[name]' => $newBlogCategoryName,
        ]);
        $this->client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $category = $this->client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy(['slug' => 'testing-category']);
        $this->assertSame($newBlogCategoryName, $category->getName());
    }

    public function testAdminDeleteCategory()
    {
        $crawler = $this->client->request('GET', '/panel/blog/kategorie/kategoria/testing-category');
        $this->client->click($crawler->filter('#delete-item')->link());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $category = $this->client->getContainer()->get('doctrine')->getRepository(Category::class)->findOneBy(['slug' => 'testing-category']);
        $this->assertNull($category);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function generateRandomString(int $length): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return mb_substr(str_shuffle(str_repeat($chars, ceil($length / mb_strlen($chars)))), 1, $length);
    }
}
