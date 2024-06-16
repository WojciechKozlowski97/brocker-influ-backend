<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserInstagram;
use App\Repository\UserInstagramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InstagramService implements InstagramServiceInterface
{
    private const GRAPH_INSTAGRAM_ME = 'https://graph.instagram.com/me';
    private const API_INSTAGRAM_TOKEN = 'https://api.instagram.com/oauth/access_token';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly HttpClientInterface $httpClient,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserInstagramRepository $userInstagramRepository
    ) {
    }

    public function handleInstagramUser(User $user, array $instagramUserDetails, int $followers): void
    {
        $userInstagram = $this->userInstagramRepository->findOneBy(['username' => $instagramUserDetails['username']]);

        if ($userInstagram) {
            return;
        }

        $this->saveUserInstagram($user, $instagramUserDetails, $followers);
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function getAccessToken(string $code): ?string
    {
        $redirectUri = $this->urlGenerator->generate(
            'connect_instagram_check',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $formData = [
            'client_id' => $_ENV['OAUTH_INSTAGRAM_CLIENT_ID'],
            'client_secret' => $_ENV['OAUTH_INSTAGRAM_CLIENT_SECRET'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
            'code' => $code,
        ];

        try {
            $response = $this->httpClient->request('POST', self::API_INSTAGRAM_TOKEN, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => http_build_query($formData),
            ]);

            $data = $response->toArray();

            if (isset($data['access_token'])) {
                return $data['access_token'];
            }

            throw new Exception('Failed to obtain access token');
        } catch (TransportExceptionInterface $e) {
            throw new Exception('HTTP request failed: ' . $e->getMessage());
        }
    }

    /**
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function getUserDetails(string $accessToken): array
    {
        try {
            $response = $this->httpClient->request('GET', self::GRAPH_INSTAGRAM_ME, [
                'query' => [
                    'fields' => 'id,username,account_type,media_count',
                    'access_token' => $accessToken,
                ],
            ]);

            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
            throw new Exception('Failed to fetch user details: ' . $e->getMessage());
        }
    }

    private function saveUserInstagram(User $user, array $instagramUserDetails, int $followers): void
    {
        $newUserInstagram = new UserInstagram();
        $newUserInstagram->setUser($user);
        $newUserInstagram->setInstagramId($instagramUserDetails['id']);
        $newUserInstagram->setUsername($instagramUserDetails['username']);
        $newUserInstagram->setFollowersCount($followers);

        $user->setUserInstagram($newUserInstagram);

        $this->entityManager->persist($user);
        $this->entityManager->persist($newUserInstagram);

        $this->entityManager->flush();
    }
}
