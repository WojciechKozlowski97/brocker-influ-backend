<?php

namespace App\Service;

use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

interface PasswordResetServiceInterface
{
    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function sendPasswordResetEmail(string $email): void;

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function resetPassword(string $email, string $token, string $newPassword): void;
}
