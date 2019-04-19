<?php

namespace App\Tests\Controller\Backend;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TagControllerTest.
 */
class TagControllerTest extends WebTestCase
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
        yield ['GET', '/panel/blog/tagi'];
        yield ['GET', '/panel/blog/tagi/tag'];
    }

    public function testAdminTagList()
    {
        $crawler = $this->client->request('GET', '/panel/blog/tagi');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(
            15,
            $crawler->filter('td')->count(),
            'The backend tag list displays all the available tags.'
        );
    }

    public function testAdminCreateNewTag()
    {
        $tagName = 'Tag Name '.mt_rand();

        $crawler = $this->client->request('GET', '/panel/blog/tagi/tag');
        $form = $crawler->selectButton('Zapisz')->form([
            'taxonomy[name]' => $tagName,
        ]);
        $this->client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $tag = $this->client->getContainer()->get('doctrine')->getRepository(Tag::class)->findOneBy([
            'name' => $tagName,
        ]);
        $this->assertNotNull($tag);
        $this->assertSame($tagName, $tag->getName());
    }

    public function testAdminEditTag()
    {
        $newBlogTagName = 'Blog Tag Name '.mt_rand();
        $crawler = $this->client->request('GET', '/panel/blog/tagi/tag/testing-tag');
        $form = $crawler->selectButton('Zapisz')->form([
            'taxonomy[name]' => $newBlogTagName,
        ]);
        $this->client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $tag = $this->client->getContainer()->get('doctrine')->getRepository(Tag::class)->findOneBy(['slug' => 'testing-tag']);
        $this->assertSame($newBlogTagName, $tag->getName());
    }

    public function testAdminDeleteTag()
    {
        $crawler = $this->client->request('GET', '/panel/blog/tagi/tag/testing-tag');
        $this->client->click($crawler->filter('#delete-item')->link());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $tag = $this->client->getContainer()->get('doctrine')->getRepository(Tag::class)->findOneBy(['slug' => 'testing-tag']);
        $this->assertNull($tag);
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
