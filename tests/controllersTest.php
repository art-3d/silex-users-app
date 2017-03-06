<?php

use Silex\WebTestCase;
use App\Application;

class controllersTest extends WebTestCase
{
    public function createApplication()
    {
        $app = new Application();
        $app['session.test'] = true;

        return $app;
    }

    public function testHomepagePage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
    }

    public function testRegisterPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/register');
    
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
    }

    public function testLoginPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
    }

    public function testUsersPage()
    {
        $client = $this->createClient();
        $client->request('GET', '/users');

        $this->assertFalse($client->getResponse()->isOk());
    }

    public function test404()
    {
        $client = $this->createClient();
        $client->request('GET', '/test-404-page');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
