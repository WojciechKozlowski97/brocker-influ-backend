<?php

namespace App\Service;

use App\Entity\Bid;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

interface DisplayOfferServiceInterface
{
    /**
     * @throws ExceptionInterface
     */
    public function getOffer(Bid $offer): array;
}
