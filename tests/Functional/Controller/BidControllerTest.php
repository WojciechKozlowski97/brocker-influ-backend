<?php

namespace App\Tests\Functional\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BidControllerTest extends WebTestCase
{
    protected function createAuthenticatedClient($username = 'user', $password = 'password')
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
    public function testCheckBidRefresh(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/bid/1/check-refresh');

        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        $this->assertArrayHasKey('canRefresh', $data);
    }

    public function testRefreshBid(): void
    {
        $client = $this->createAuthenticatedClient('moj_email0@gmail.com', '12340');
        $client->request('POST', '/api/bid/1/refresh-bid');

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        $data = json_decode($content, true);

        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertTrue($data['success']);
    }
}
