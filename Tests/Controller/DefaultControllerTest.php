<?php

namespace Positibe\Bundle\ContentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

//        $crawler = $client->request('GET', '/hello/Fabien');
    }
}
