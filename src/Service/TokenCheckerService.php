<?php

namespace App\Service;

use DateInterval;
use DateTime;

class TokenCheckerService implements TokenCheckerServiceInterface
{
    public function checkTokenAge(DateTime $createdAt, int $limit): bool
    {
        $dateTime = new DateTime('now');
        $diff = $dateTime->diff($createdAt);

        $minutes = $this->calculateTotalMinutes($diff);

        if ($minutes < $limit) {
            return false;
        }

        return true;
    }

    private function calculateTotalMinutes(DateInterval $diff): int
    {
        $minutes = $diff->days * 24 * 60;
        $minutes += $diff->h * 60;
        $minutes += $diff->i;

        return $minutes;
    }
}
