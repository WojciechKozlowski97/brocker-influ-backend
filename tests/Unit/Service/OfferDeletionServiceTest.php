<?php

namespace App\Tests\Unit\Service;

use App\Entity\Bid;
use App\Repository\BidRepository;
use App\Service\OfferDeletionService;
use App\Service\OfferDeletionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;

class OfferDeletionServiceTest extends TestCase
{
    private BidRepository $bidRepository;
    private EntityManagerInterface $entityManager;
    private OfferDeletionServiceInterface $offerDeletionService;

    protected function setUp(): void
    {
        $this->bidRepository = $this->createMock(BidRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->offerDeletionService = new OfferDeletionService($this->bidRepository, $this->entityManager);
    }

    /**
     * @throws Exception
     */
    public function testDeleteOfferSuccess(): void
    {
        $bid = $this->createMock(Bid::class);

        $this->bidRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($bid);

        $bid->expects($this->once())
            ->method('setIsDeleted')
            ->with(true);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($bid);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->offerDeletionService->deleteOffer(1);
    }

    public function testDeleteOfferExceptionOfferDoesntExist()
    {
        $this->bidRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 0])
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The specified offer does not exist');

        $this->offerDeletionService->deleteOffer(0);
    }
}
