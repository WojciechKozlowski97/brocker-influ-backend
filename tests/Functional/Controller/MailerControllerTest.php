<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailerControllerTest extends WebTestCase
{
    private function createClientAndRequest(string $method, string $uri, array $parameters = [], array $content = []): array
    {
        $client = static::createClient();

        if ($method === 'GET') {
            $client->request($method, $uri, $parameters);
        } else {
            $client->request($method, $uri, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($content));
        }

        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();
        return json_decode($content, true);
    }

    private function assertResponseData(array $data, bool $expectedSuccess, string $expectedMessage): void
    {
        $this->assertArrayHasKey('success', $data);
        $this->assertSame($expectedSuccess, $data['success']);
        $this->assertSame($expectedMessage, $data['message']);
    }

    public function testActivateAccountSuccess(): void
    {
        $data = $this->createClientAndRequest('GET', '/api/active-account', [
            'email' => 'moj_email0@gmail.com',
            'token' => '57b11d4a5bcc0016e2a0488df8927229'
        ]);

        $this->assertResponseData($data, true, 'Account activated successfully');
    }

    public function testActivateAccountFailure(): void
    {
        $data = $this->createClientAndRequest('GET', '/api/active-account', [
            'email' => 'moj_email0@gmail.com',
            'token' => '123456789'
        ]);

        $this->assertResponseData($data, false, 'Failed to send email. User not found or activation token is invalid.');
    }

    public function testSendEmailSuccess(): void
    {
        $postData = ['email' => 'moj_email0@gmail.com'];
        $data = $this->createClientAndRequest('POST', '/api/send-email', [], $postData);

        $this->assertResponseData($data, true, 'Email sent successfully');
    }

    public function testSendEmailUserNotFound(): void
    {
        $postData = ['email' => 'nonexistent@example.com'];
        $data = $this->createClientAndRequest('POST', '/api/send-email', [], $postData);

        $this->assertResponseData($data, false, 'User for given email not found');
    }
}
