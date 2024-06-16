<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sites = ['Instagram', 'TikTok'];

        foreach ($sites as $siteName) {
            $manager->persist($this->createSite($siteName));
        }

        $manager->flush();
    }

    private function createSite(string $name): Site
    {
        $site = new Site();
        $site->setName($name);
        return $site;
    }
}
