<?php

namespace App\Service;

use DateInterval;
use DateTime;

interface TokenCheckerServiceInterface
{
    public function checkTokenAge(DateTime $createdAt, int $limit): bool;
}
