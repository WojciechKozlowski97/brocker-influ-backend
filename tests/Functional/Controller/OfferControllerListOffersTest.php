<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferControllerListOffersTest extends WebTestCase
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

    public function testListOffersSuccess(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');
        $client->request('GET', '/api/offers');

        $this->assertResponseIsSuccessful();

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('title', $data['data'][0]);
    }

    public function testListOffersByCategory(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');
        $client->request('GET', '/api/offers/category/8');

        $this->assertResponseIsSuccessful();

        $response = $client->getResponse()->getContent();
        $data = json_decode($response, true);

        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('data', $data);
    }
}
