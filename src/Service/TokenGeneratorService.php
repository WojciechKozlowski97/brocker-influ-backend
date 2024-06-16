<?php

namespace App\Service;

use App\Entity\PasswordResetToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class TokenGeneratorService implements TokenGeneratorServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenGeneratorInterface $tokenGenerator
    ) {
    }

    /**
     * @throws Exception
     */
    public function generatePasswordResetToken(User $user): PasswordResetToken
    {
        $passwordResetToken = $user->getPasswordResetToken();

        if (!$passwordResetToken) {
            $passwordResetToken = new PasswordResetToken();
        }

        $passwordResetToken->setUser($user);
        $passwordResetToken->setToken($this->tokenGenerator->generateToken());

        $this->entityManager->persist($passwordResetToken);
        $this->entityManager->flush();

        return $passwordResetToken;
    }
}
