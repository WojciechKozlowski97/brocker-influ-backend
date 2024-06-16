<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferControllerCreateOfferTest extends WebTestCase
{
    private array $content;

    protected function setUp(): void
    {
        $this->content = [
            "title" => "Example Title",
            "content" => "Detailed description of the offer.",
            "site" => "instagram",
            "price" => 100,
            "categories" => [9],
            "deals" => [
                [
                    "content" => "Details of the first deal option.",
                    "price" => 150,
                    "delivery_days" => 7,
                    "revisions" => 2
                ],
                [
                    "content" => "Details of the second deal option.",
                    "price" => 200,
                    "delivery_days" => 10,
                    "revisions" => 3
                ]
            ]
        ];
    }

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

    protected function makeRequest(KernelBrowser $client, array $content, array $files = []): array
    {
        $client->request(
            'POST',
            '/api/new-offer',
            ['data' => json_encode($content)],
            $files
        );

        $this->assertResponseIsSuccessful();
        return json_decode($client->getResponse()->getContent(), true);
    }

    public function testCreateOfferSuccess(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');

        $data = $this->makeRequest($client, $this->content, ['images' => []]);

        $this->assertTrue($data['success']);
        $this->assertSame('Offer created successfully', $data['message']);
    }

    public function testCreateOfferMissingFields(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');
        $content = [
            "title" => "Example Title",
            "content" => "Detailed description of the offer.",
        ];

        $data = $this->makeRequest($client, $content, ['images' => []]);

        $this->assertFalse($data['success']);
        $this->assertSame('Missing required fields', $data['message']);
    }

    public function testCreateOfferNotVerifiedUser(): void
    {
        $client = $this->createAuthenticatedClient('moj_email1@gmail.com', '12341');

        $data = $this->makeRequest($client, $this->content, ['images' => []]);

        $this->assertFalse($data['success']);
        $this->assertSame(
            'You need to verify your account through Instagram to add an offer',
            $data['message']
        );
    }
}
