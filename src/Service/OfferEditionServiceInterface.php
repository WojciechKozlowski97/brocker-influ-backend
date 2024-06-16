<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Exception;

interface OfferEditionServiceInterface
{
    /**
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function getOffer(int $offerId, User $user): array;
}
