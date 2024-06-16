<?php

namespace App\Service;

use Exception;

interface OfferDeletionServiceInterface
{
    /**
     * @throws Exception
     */
    public function deleteOffer(int $offerId): void;
}
