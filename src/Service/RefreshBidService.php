<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\BidRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class RefreshBidService implements RefreshBidServiceInterface
{
    public function __construct(
        private readonly BidRepository $bidRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws Exception
     */
    public function refreshBid(int $id, User $user): void
    {
        if (!$user) {
            throw new Exception("Cannot get the offer");
        }

        $bid = $this->bidRepository->findOneBy(['id' => $id]);

        if (!$bid) {
            throw new Exception('Bid does not exist');
        }

        if ($bid->getUserInstagram()->getUser()->getId() !== $user->getId()) {
            throw new Exception("Unauthorized access");
        }

        $bid->setUpdatedAt(new DateTime('now'));
        $this->entityManager->persist($bid);
        $this->entityManager->flush();
    }
}
