<?php

namespace App\Tests\Controller\Backend;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostControllerTest.
 */
class PostControllerTest extends WebTestCase
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
     * @dataProvider getUrlsForRegularUsers
     *
     * @param string $httpMethod
     * @param string $url
     */
    public function testAccessDeniedForRegularUsers(string $httpMethod, string $url)
    {
        $client_student = static::createClient([], [
            'PHP_AUTH_USER' => 'kuba_student@test.pl',
            'PHP_AUTH_PW' => 'student',
        ]);
        $client_student->request($httpMethod, $url);
        $this->assertSame(Response::HTTP_FORBIDDEN, $client_student->getResponse()->getStatusCode());
    }

    /**
     * @return \Generator
     */
    public function getUrlsForRegularUsers()
    {
        yield ['GET', '/panel/blog'];
        yield ['GET', '/panel/blog/artykul'];
        yield ['GET', '/panel/blog/usun/testing-post/UzfJ9voqXs5mkQRW7flYX1YulZdLkg6jTt3UjvGTseI'];
    }

    public function testAdminPostList()
    {
        $crawler = $this->client->request('GET', '/panel/blog');
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThanOrEqual(
            7,
            $crawler->filter('td')->count(),
            'The backend post list displays all the available posts.'
        );
    }

    public function testAdminCreateNewPost()
    {
        $postTitle = 'Blog Post Title '.mt_rand();
        $introductionContent = $this->generateRandomString(255);
        $postContent = $this->generateRandomString(1024);
        $metaTitle = $this->generateRandomString(60);
        $metaDescription = $this->generateRandomString(120);

        $crawler = $this->client->request('GET', '/panel/blog/artykul');
        $form = $crawler->selectButton('Zapisz')->form([
            'post[title]' => $postTitle,
            'post[introductionContent]' => $introductionContent,
            'post[content]' => $postContent,
            'post[metaTitle]' => $metaTitle,
            'post[metaDescription]' => $metaDescription,
        ]);
        $form['post[category]']->select('pellentesque');
        $form['post[status]']->select('public');
        $form['post[tags]']->select('ullamcorper', 'pellentesque', 'faucibus');

        $this->client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $post = $this->client->getContainer()->get('doctrine')->getRepository(Post::class)->findOneBy([
            'title' => $postTitle,
        ]);
        $this->assertNotNull($post);
        $this->assertSame($postTitle, $post->getTitle());
        $this->assertSame($introductionContent, $post->getIntroductionContent());
        $this->assertSame($postContent, $post->getContent());
        $this->assertSame($metaTitle, $post->getMetaTitle());
        $this->assertSame($metaDescription, $post->getMetaDescription());
    }

    public function testAdminEditPost()
    {
        $newBlogPostTitle = 'Blog Post Title '.mt_rand();
        $crawler = $this->client->request('GET', '/panel/blog/artykul/testing-post');
        $form = $crawler->selectButton('Zapisz')->form([
            'post[title]' => $newBlogPostTitle,
        ]);
        $this->client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $post = $this->client->getContainer()->get('doctrine')->getRepository(Post::class)->findOneBy(['slug' => 'testing-post']);
        $this->assertSame($newBlogPostTitle, $post->getTitle());
    }

    public function testAdminDeletePost()
    {
        $crawler = $this->client->request('GET', '/panel/blog/artykul/testing-post');
        $this->client->click($crawler->filter('#delete-item')->link());
        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
        $post = $this->client->getContainer()->get('doctrine')->getRepository(Post::class)->findOneBy(['slug' => 'testing-post']);
        $this->assertNull($post);
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
