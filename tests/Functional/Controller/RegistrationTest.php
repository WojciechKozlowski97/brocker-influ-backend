<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    public function testSuccessfulRegistration(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/registration', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Test',
            'email' => 'testuser@example' . rand(1, 100000) . '.com',
            'password' => 'TestPass123',
        ]));

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
        $this->assertArrayHasKey('message', $response);
        $this->assertEquals('User successfully registered', $response['message']);
    }

    public function testInvalidRegistration(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/registration', [], [], ['CONTENT_TYPE' => 'application/json']);

        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(400);
        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('Invalid JSON', $response['error']);
    }
}
