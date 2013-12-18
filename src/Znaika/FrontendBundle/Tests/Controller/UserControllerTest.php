<?php

namespace Znaika\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');
    }

    public function testShowuserprofile()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user-profile/{userId}');
    }

}
