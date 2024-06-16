<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\BidRepository;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OfferEditionService implements OfferEditionServiceInterface
{
    public function __construct(
        private readonly BidRepository $bidRepository,
        private readonly  NormalizerInterface $normalizer
    ) {
    }

    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function getOffer(int $offerId, User $user): array
    {
        if (!$user) {
            throw new Exception("Cannot get the offer");
        }

        $bid = $this->bidRepository->findOneBy(['id' => $offerId]);

        if (!$bid) {
            throw new Exception("The specified offer does not exist");
        }

        if ($bid->getUserInstagram()->getUser()->getId() !== $user->getId()) {
            throw new Exception("Unauthorized access");
        }

        return $this->normalizer->normalize($bid, null, ['groups' => ['detailed']]);
    }
}
