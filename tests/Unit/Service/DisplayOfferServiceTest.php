<?php

namespace App\Tests\Unit\Service;

use App\Entity\Bid;
use App\Entity\UserInstagram;
use App\Service\DisplayOfferService;
use App\Service\DisplayOfferServiceInterface;
use App\Service\InstagramProfileLinkGeneratorServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DisplayOfferServiceTest extends TestCase
{
    private NormalizerInterface $normalizer;
    private InstagramProfileLinkGeneratorServiceInterface $instagramProfileLinkGenerator;
    private DisplayOfferServiceInterface $displayOfferService;

    protected function setUp(): void
    {
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->instagramProfileLinkGenerator = $this->createMock(InstagramProfileLinkGeneratorServiceInterface::class);

        $this->displayOfferService = new DisplayOfferService(
            $this->normalizer,
            $this->instagramProfileLinkGenerator
        );
    }

    /**
     * @throws ExceptionInterface
     */
    public function testGetOfferSuccess(): void
    {
        $bid = $this->createMock(Bid::class);
        $userInstagram = $this->createMock(UserInstagram::class);
        $instagramProfileLink = 'https://instagram.com/someprofile';
        $normalizedData = ['some' => 'data'];

        $bid->method('getUserInstagram')->willReturn($userInstagram);

        $this->instagramProfileLinkGenerator->expects($this->once())
            ->method('generateLink')
            ->with($userInstagram)
            ->willReturn($instagramProfileLink);

        $this->normalizer->expects($this->once())
            ->method('normalize')
            ->with($bid, null, ['groups' => ['detailed']])
            ->willReturn($normalizedData);

        $result = $this->displayOfferService->getOffer($bid);

        $expected = array_merge($normalizedData, ['instagramProfile' => $instagramProfileLink]);
        $this->assertSame($expected, $result);
    }
}
