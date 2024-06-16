<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class UserDeletionService implements UserDeletionServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws Exception
     */
    public function deleteUser(User $user): void
    {
        $user->setIsDeleted(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
