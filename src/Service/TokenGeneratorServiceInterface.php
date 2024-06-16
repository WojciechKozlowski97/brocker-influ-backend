<?php

namespace App\Service;

use App\Entity\PasswordResetToken;
use App\Entity\User;
use Exception;

interface TokenGeneratorServiceInterface
{
    /**
     * @throws Exception
     */
    public function generatePasswordResetToken(User $user): PasswordResetToken;
}
