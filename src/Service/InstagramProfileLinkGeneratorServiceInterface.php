<?php

namespace App\Service;

use App\Entity\UserInstagram;

interface InstagramProfileLinkGeneratorServiceInterface
{
    public function generateLink(UserInstagram $userInstagram): string;
}
