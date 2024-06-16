<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\InstagramScraperServiceInterface;
use App\Service\InstagramServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InstagramController extends AbstractController
{
    #[Route('/connect/instagram', name: 'connect_instagram_start')]
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('instagram')
            ->redirect([
                'user_profile', 'user_media'
            ]);
    }

    #[Route('/connect/instagram/check', name: 'connect_instagram_check')]
    public function connectCheckAction(
        Request $request,
        InstagramServiceInterface $instagramService,
        InstagramScraperServiceInterface $instagramScraperService
    ): Response {
        $code = $request->get('code');

        if (!$code) {
            return $this->json(['success' => false, 'message' => 'Authorization code not found']);
        }

        try {
            $accessToken = $instagramService->getAccessToken($code);

            if ($accessToken) {
                $userDetails = $instagramService->getUserDetails($accessToken);

                $user = $this->getUser();
                if ($user instanceof User) {
                    $followers = $instagramScraperService->getFollowerCount($userDetails['username']);
                    $instagramService->handleInstagramUser($user, $userDetails, $followers);

                    return $this->json([
                        'success' => true,
                        'message' => 'Instagram account linked successfully',
                        'userDetails' => $userDetails,
                        'followersCount' => $followers,
                    ]);
                }

                return $this->json(['success' => false, 'message' => 'User not authenticated']);
            } else {
                return $this->json(['success' => false, 'message' => 'Failed to obtain access token']);
            }
        } catch (Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
