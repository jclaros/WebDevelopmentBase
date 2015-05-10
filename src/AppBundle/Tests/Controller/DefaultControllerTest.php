<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    protected $movieName;
    protected $movieId;
    public function __construct() {
      $this->movieName = rand(10, 1200000);
    }
    
    public function testGetMovies()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/v1/products');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $body = json_decode($client->getResponse()->getContent());
        $this->assertTrue($body->page === 1);
    }
    
}
