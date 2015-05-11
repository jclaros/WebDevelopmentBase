<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testGetProducts()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/products');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $body = json_decode($client->getResponse()->getContent());
        $this->assertTrue($body->page === 1);
    }
    
}
