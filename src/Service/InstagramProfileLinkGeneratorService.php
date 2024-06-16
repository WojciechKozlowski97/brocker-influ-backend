<?php

namespace App\Service;

use App\Entity\UserInstagram;

class InstagramProfileLinkGeneratorService implements InstagramProfileLinkGeneratorServiceInterface
{
    public function generateLink(UserInstagram $userInstagram): string
    {
        $instagramUsername = $userInstagram->getUsername();
        return "https://www.instagram.com/{$instagramUsername}/";
    }
}
