<?php

namespace App\Service;

use App\Entity\Bid;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DisplayOfferService implements DisplayOfferServiceInterface
{
    public function __construct(
        private readonly NormalizerInterface $normalizer,
        private readonly InstagramProfileLinkGeneratorServiceInterface $instagramProfileLinkGenerator
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function getOffer(Bid $offer): array
    {
        $instagramProfileLink = $this->instagramProfileLinkGenerator->generateLink($offer->getUserInstagram());
        $offerDetails = $this->normalizer->normalize($offer, null, ['groups' => ['detailed']]);

        return array_merge($offerDetails, ['instagramProfile' => $instagramProfileLink]);
    }
}
