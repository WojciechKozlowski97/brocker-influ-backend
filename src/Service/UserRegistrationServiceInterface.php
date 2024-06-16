<?php

namespace App\Service;

interface UserRegistrationServiceInterface
{
    public function registerUser(string $name, string $email, string $password): array;
}
