<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setName('Imię ' . $i)
                ->setEmail('moj_email' . $i . '@gmail.com')
                ->setIsVerified(true)
                ->setDescription('Example description' . $i)
                ->setIsDeleted(false);

            $user->setPassword($this->userPasswordHasher->hashPassword($user, '1234' . $i));
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }
}
