<?php

namespace App\Service;

use App\Entity\User;
use Exception;

interface OfferCreationServiceInterface
{
    /**
     * @throws Exception
     */
    public function createOffer(array $content, array $images, User $user): void;
}
