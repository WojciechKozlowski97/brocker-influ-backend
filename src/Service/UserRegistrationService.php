<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserActivation;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegistrationService implements UserRegistrationServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly ValidatorInterface $validator,
        private readonly EmailServiceInterface $emailService
    ) {
    }

    public function registerUser(string $name, string $email, string $password): array
    {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setIsDeleted(false);

        $userActivation = new UserActivation();
        $userActivation->setUser($user);
        $userActivation->setEmailVerificationToken(hash('sha256', random_bytes(16)));

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setUserActivation($userActivation);

        $errors = $this->validateUser($user);
        if (!empty($errors)) {
            return ['error' => $errors];
        }

        try {
            $this->entityManager->persist($user);
            $this->entityManager->persist($userActivation);
            $this->entityManager->flush();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }

        try {
            $this->emailService->sendActivationEmail($user);
        } catch (TransportExceptionInterface $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return ['success' => true, 'message' => 'User successfully registered'];
    }

    private function validateUser(User $user): array
    {
        $validationErrors = $this->validator->validate($user);
        $errors = [];

        foreach ($validationErrors as $error) {
            $errors[] = $error->getMessage();
        }

        return $errors;
    }
}
