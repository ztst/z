<?php

namespace Znaika\FrontendBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class QuizControllerTest extends WebTestCase
{
    public function testAddquizform()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'add-quiz-form');
    }

}
