<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferControllerDisplayOfferTest extends WebTestCase
{
    protected function createAuthenticatedClient($username = 'user', $password = 'password'): KernelBrowser
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testDisplayOfferSuccess(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');

        $client->request(
            'GET',
            '/api/display-offer/1'
        );

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('content', $data);
        $this->assertArrayHasKey('site', $data);
        $this->assertArrayHasKey('price', $data);
        $this->assertArrayHasKey('deals', $data);
    }

    public function testDisplayOfferNotFound(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');

        $client->request(
            'GET',
            '/api/display-offer/9999'
        );

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertFalse($data['success']);
        $this->assertSame('Offer not found', $data['message']);
    }
}
