<?php

namespace App\Service;

use App\Repository\BidRepository;
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OfferService implements OfferServiceInterface
{
    public function __construct(
        private readonly BidRepository $bidRepository,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function getOffers(int $page = 1, int $pageSize = 10): array
    {
        $paginator = $this->bidRepository->findAllBidsSortedByUpdatedAt($page, $pageSize);

        $offers = [];

        foreach ($paginator as $offer) {
            $offers[] = $this->normalizer->normalize($offer, null, ['groups' => ['normal']]);
        }

        return [
            'data' => $offers,
            'current_page' => $page,
            'total_items' => $paginator->count(),
            'total_pages' => ceil($paginator->count() / $pageSize),
        ];
    }

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getOffersByCategory(int $categoryId, int $page, int $pageSize): array
    {
        $paginator = $this->bidRepository->findOffersByCategoryId($categoryId, $page, $pageSize);

        $offers = [];

        foreach ($paginator as $offer) {
            $offers[] = $this->normalizer->normalize($offer, null, ['groups' => 'normal']);
        }

        return [
            'data' => $offers,
            'current_page' => $page,
            'total_items' => $paginator->count(),
            'total_pages' => ceil($paginator->count() / $pageSize),
        ];
    }
}
