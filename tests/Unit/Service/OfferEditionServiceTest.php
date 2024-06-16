<?php

namespace App\Tests\Unit\Service;

use App\Entity\Bid;
use App\Entity\User;
use App\Entity\UserInstagram;
use App\Repository\BidRepository;
use App\Service\OfferEditionService;
use App\Service\OfferEditionServiceInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OfferEditionServiceTest extends TestCase
{
    private BidRepository $bidRepository;
    private NormalizerInterface $normalizer;
    private OfferEditionServiceInterface $offerEditionService;

    protected function setUp(): void
    {
        $this->bidRepository = $this->createMock(BidRepository::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->offerEditionService = new OfferEditionService($this->bidRepository, $this->normalizer);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testGetOfferSuccess(): void
    {
        $bid = $this->createMock(Bid::class);
        $user = $this->createMock(User::class);
        $userInstagram = $this->createMock(UserInstagram::class);
        $normalizedData = ['some' => 'data'];

        $user->method('getId')->willReturn(1);
        $userInstagram->method('getUser')->willReturn($user);
        $bid->method('getUserInstagram')->willReturn($userInstagram);

        $this->bidRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($bid);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($bid, null, ['groups' => ['detailed']])
            ->willReturn($normalizedData);

        $result = $this->offerEditionService->getOffer(1, $user);
        $this->assertSame($normalizedData, $result);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testGetOfferThrowsExceptionWhenOfferDoesNotExist(): void
    {
        $user = $this->createMock(User::class);

        $this->bidRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("The specified offer does not exist");

        $this->offerEditionService->getOffer(1, $user);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testGetOfferThrowsExceptionWhenUnauthorizedAccess(): void
    {
        $user = $this->createMock(User::class);
        $userInstagram = $this->createMock(UserInstagram::class);
        $bid = $this->createMock(Bid::class);
        $differentUser = $this->createMock(User::class);

        $user->method('getId')->willReturn(1);
        $differentUser->method('getId')->willReturn(2);
        $userInstagram->method('getUser')->willReturn($differentUser);
        $bid->method('getUserInstagram')->willReturn($userInstagram);

        $this->bidRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($bid);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Unauthorized access");

        $this->offerEditionService->getOffer(1, $user);
    }
}
