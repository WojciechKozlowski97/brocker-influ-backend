<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Exception;

interface EmailServiceInterface
{
    /**
     * @throws TransportExceptionInterface
     */
    public function sendActivationEmail(User $user): void;

    /**
     * @throws Exception
     */
    public function activateAccount(string $email, string $token): void;

    /**
     * @throws TransportExceptionInterface
     */
    public function sendSuccessResetPasswordEmail(string $email): void;
}
