<?php

namespace App\Service;

use App\Entity\UserInstagram;
use App\Repository\UserInstagramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

class UpdateInstagramFollowersService implements UpdateInstagramFollowersServiceInterface
{
    public function __construct(
        private readonly UserInstagramRepository $userInstagramRepository,
        private readonly InstagramScraperServiceInterface $instagramScraperService,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws Exception
     */
    public function updateInstagramFollowersNumber(): void
    {
        $instagramUsers = $this->userInstagramRepository->findAll();

        foreach ($instagramUsers as $instagramUser) {
            $this->processInstagramUser($instagramUser);
        }

        $this->entityManager->flush();
    }

    /**
     * @throws Exception
     */
    private function processInstagramUser(UserInstagram $instagramUser): void
    {
        $username = $instagramUser->getUsername();

        try {
            $followersNumber = $this->instagramScraperService->getFollowerCount($username);
            $instagramUser->setFollowersCount($followersNumber);

            $this->entityManager->persist($instagramUser);
        } catch (Exception $e) {
            $this->logger->error('Failed to update Instagram followers for user: ' . $username, [
                'exception' => $e,
            ]);
        }
    }
}
