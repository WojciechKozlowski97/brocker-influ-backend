<?php

namespace App\Service;

interface InstagramScraperServiceInterface
{
    public function getFollowerCount(string $username): ?int;
}
