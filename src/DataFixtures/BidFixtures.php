<?php

namespace App\DataFixtures;

use App\Entity\Bid;
use App\Entity\Site;
use App\Entity\UserInstagram;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class BidFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'moj_email0@gmail.com']);

        $site = new Site();
        $site->setName('Example Site');
        $manager->persist($site);

        $userInstagram = new UserInstagram();
        $userInstagram->setUsername('example_user');
        $userInstagram->setFollowersCount(1000);
        $userInstagram->setUser($user);
        $manager->persist($userInstagram);

        $bid = new Bid();
        $bid->setTitle('Example Title');
        $bid->setContent('Detailed description of the offer.');
        $bid->setPrice(100);
        $bid->setIsDeleted(false);
        $bid->setCreatedAt((new DateTime())->modify('-15 days'));
        $bid->setUpdatedAt((new DateTime())->modify('-15 days'));
        $bid->setUserInstagram($userInstagram);
        $bid->setSite($site);

        $manager->persist($bid);
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2;
    }
}
