<?php

namespace App\Service;

use App\Entity\User;
use Exception;

interface RefreshBidServiceInterface
{
    /**
     * @throws Exception
     */
    public function refreshBid(int $id, User $user): void;
}
