<?php

namespace App\DataFixtures;

use App\Entity\UserActivation;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserActivationFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'moj_email0@gmail.com']);

        $userActivation = new UserActivation();
        $userActivation->setUser($user);
        $userActivation->setEmailVerificationToken('57b11d4a5bcc0016e2a0488df8927229');

        $user->setUserActivation($userActivation);

        $manager->persist($userActivation);
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 3;
    }
}
