<?php

namespace App\Tests\Unit\Service;

use App\Entity\Bid;
use App\Repository\BidRepository;
use App\Service\BidRefreshChecker;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class BidRefreshCheckerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanBidBeRefreshed(): void
    {
        $bidRepository = $this->createMock(BidRepository::class);
        $bid = new Bid();
        $bid->setUpdatedAt((new DateTime())->modify('-15 days'));

        $bidRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($bid);

        $bidRefreshChecker = new BidRefreshChecker($bidRepository);
        $result = $bidRefreshChecker->canBidBeRefreshed(1);

        $this->assertTrue($result);
    }

    public function testCanBidBeRefreshedException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Bid does not exist');

        $bidRepository = $this->createMock(BidRepository::class);

        $bidRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(null);

        $bidRefreshChecker = new BidRefreshChecker($bidRepository);
        $bidRefreshChecker->canBidBeRefreshed(1);
    }

    public function testCheckBidAgeThrowsExceptionWhenNoUpdatedAt(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Bid does not have an updated at timestamp');

        $bidRepository = $this->createMock(BidRepository::class);
        $bid = new Bid();

        $bidRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($bid);

        $bidRefreshChecker = new BidRefreshChecker($bidRepository);
        $bidRefreshChecker->canBidBeRefreshed(1);
    }
}
