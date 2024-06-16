<?php

namespace App\Service;

use Exception;

interface UpdateInstagramFollowersServiceInterface
{
    /**
     * @throws Exception
     */
    public function updateInstagramFollowersNumber(): void;
}
