<?php

namespace Znaika\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VideoControllerTest extends WebTestCase
{
    public function testShowcatalogue()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/catalogue/{class}/{subject}');
    }

    public function testShowvideo()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/video/{class}/{subject}');
    }

}
