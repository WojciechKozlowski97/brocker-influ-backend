<?php

namespace App\Service;

use Exception;

interface BidRefreshCheckerInterface
{
    /**
     * @throws Exception
     */
    public function canBidBeRefreshed(int $id): bool;
}
