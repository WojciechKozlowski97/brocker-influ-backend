<?php

namespace App\Service;

use App\Entity\User;
use Exception;

interface UserDeletionServiceInterface
{
    /**
     * @throws Exception
     */
    public function deleteUser(User $user): void;
}
