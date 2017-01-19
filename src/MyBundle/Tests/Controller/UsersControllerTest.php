<?php

namespace MyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
{
    public function testGetusers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getUsers');
    }

    public function testRedirect()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/redirect');
    }

}
