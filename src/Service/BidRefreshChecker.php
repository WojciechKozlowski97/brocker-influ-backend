<?php

namespace App\Service;

use App\Entity\Bid;
use App\Repository\BidRepository;
use Exception;

class BidRefreshChecker implements BidRefreshCheckerInterface
{
    public function __construct(private readonly BidRepository $bidRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function canBidBeRefreshed(int $id): bool
    {
        $bid = $this->bidRepository->findOneBy(['id' => $id]);

        if (!$bid) {
            throw new Exception('Bid does not exist');
        }

        return $this->checkBidAge($bid);
    }

    /**
     * @throws Exception
     */
    private function checkBidAge(Bid $bid): bool
    {
        $updatedAt = $bid->getUpdatedAt();

        if ($updatedAt === null) {
            throw new Exception('Bid does not have an updated at timestamp');
        }

        $date14DaysAgo = new \DateTime();
        $date14DaysAgo->modify('-14 days');

        return $updatedAt < $date14DaysAgo;
    }
}
