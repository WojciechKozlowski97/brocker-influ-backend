<?php

namespace App\Controller;

use App\Repository\BidRepository;
use App\Repository\UserRepository;
use App\Service\UserDeletionServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    #[Route('/api/users', name: 'api_users', methods: ['GET'])]
    public function getUsers(): Response
    {
        $allProfiles = $this->userRepository->findAll();

        return $this->json($allProfiles);
    }

    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function getMe(Request $request, BidRepository $bidRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User for given id not found'], Response::HTTP_NOT_FOUND);
        }

        $userInfo = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'Bid' => $bidRepository->findBidByUserId($user->getId()),
            'accountType' => 'influencer', // hardcoded in the future: 'advertiser' or 'manager'
            'language' => 'pl_PL', // hardcoded
        ];

        return $this->json($userInfo);
    }

    /**
     * @throws Exception
     */
    #[Route('/api/user/delete', name: 'api_user_delete', methods: ['DELETE'])]
    public function deleteAccount(UserDeletionServiceInterface $userDeletionService): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw new Exception("User not logged in");
        }

        try {
            $userDeletionService->deleteUser($user);
            return $this->json(['success' => true, 'message' => 'User has been deleted successfully']);
        } catch (Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
