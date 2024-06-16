<?php

namespace App\Service;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Exception;

interface OfferServiceInterface
{
    /**
     * @throws ExceptionInterface
     */
    public function getOffers(int $page = 1, int $pageSize = 10): array;

    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getOffersByCategory(int $categoryId, int $page, int $pageSize): array;
}
