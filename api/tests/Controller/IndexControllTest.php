<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testAll()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);

        // Assert the structure of the JSON response
        $this->assertArrayHasKey('currentPage', $data);
        $this->assertArrayHasKey('pageSize', $data);
        $this->assertArrayHasKey('results', $data);
        $this->assertArrayHasKey('numResults', $data);
    }
}
