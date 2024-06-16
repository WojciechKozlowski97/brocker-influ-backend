<?php

namespace App\Service;

use App\Repository\BidRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class OfferDeletionService implements OfferDeletionServiceInterface
{
    public function __construct(
        private readonly BidRepository $bidRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws Exception
     */
    public function deleteOffer(int $offerId): void
    {
        $bid = $this->bidRepository->findOneBy(['id' => $offerId]);

        if (!$bid) {
            throw new Exception("The specified offer does not exist");
        }

        $bid->setIsDeleted(true);
        $this->entityManager->persist($bid);
        $this->entityManager->flush();
    }
}
