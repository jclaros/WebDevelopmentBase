<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package AppBundle\Tests\Controller
 * Tests the basic interaction for get products call
 *
 */
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
