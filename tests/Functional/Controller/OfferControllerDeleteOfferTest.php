<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferControllerDeleteOfferTest extends WebTestCase
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

    public function testDeleteOfferSuccess(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');

        $client->request(
            'DELETE',
            '/api/delete-offer/1'
        );

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertTrue($data['success']);
        $this->assertSame('Offer deleted successfully', $data['message']);
    }

    public function testDeleteOfferFailure(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');

        $client->request(
            'DELETE',
            '/api/delete-offer/9999'
        );

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertFalse($data['success']);
        $this->assertStringContainsString('Failed to delete offer', $data['message']);
    }
}
