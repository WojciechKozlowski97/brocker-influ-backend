<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InstagramScraperService implements InstagramScraperServiceInterface
{
    private const INSTAGRAM_URL = 'https://i.instagram.com/api/v1/users/web_profile_info/?username=';

    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    public function getFollowerCount(string $username): ?int
    {
        $url = self::INSTAGRAM_URL . $username;

        $headers = [
            'User-Agent' => 'Instagram 76.0.0.15.395 Android (24/7.0; 640dpi; 1440x2560; samsung; ' .
                'SM-G930F; herolte; samsungexynos8890; en_US; 138226743)',
            'Origin' => 'https://www.instagram.com',
            'Referer' => 'https://www.instagram.com/',
        ];

        try {
            $response = $this->httpClient->request('GET', $url, [
                'headers' => $headers,
            ]);

            $data = $response->toArray();

            return $data['data']['user']['edge_followed_by']['count'] ?? null;
        } catch (TransportExceptionInterface $e) {
            return null;
        }
    }
}
