<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testGetCategories(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/get-categories');

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }
}
