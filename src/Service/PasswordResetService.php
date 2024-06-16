<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordResetService implements PasswordResetServiceInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserRepository $userRepository,
        private readonly ParameterBagInterface $parameterBag,
        private readonly TokenGeneratorServiceInterface $tokenGeneratorService,
        private readonly TokenCheckerServiceInterface $tokenCheckerService,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function sendPasswordResetEmail(string $email): void
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new Exception(
                'If the e-mail address is in our database, a password reset link has been sent to your inbox'
            );
        }

        $passwordResetToken = $this->tokenGeneratorService->generatePasswordResetToken($user);

        $resetPasswordUrl = $this->parameterBag->get('reset_password_url');
        $url = $this->generateUrl($resetPasswordUrl, $email, $passwordResetToken->getToken());

        $emailContent = sprintf(
            'Here is the link to reset your password: <a href="%s">Reset Your Password Here</a>',
            $url
        );

        $emailMessage = (new Email())
            ->from($this->parameterBag->get('mailer_from'))
            ->to($email)
            ->subject('Reset your password')
            ->html($emailContent);

        $this->mailer->send($emailMessage);
    }

    private function generateUrl(string $resetPasswordUrl, string $email, string $token): string
    {
        return sprintf('%s?email=%s&token=%s',
            $resetPasswordUrl,
            urlencode($email),
            urlencode($token)
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function resetPassword(string $email, string $token, string $newPassword): void
    {
        $user = $this->userRepository->findOneByEmailAndPasswordResetToken($email, $token);

        if (!$user) {
            throw new Exception('The password reset link is invalid');
        }

        $createdAt = $user->getPasswordResetToken()->getCreatedAt();

        if ($this->tokenCheckerService->checkTokenAge($createdAt, 60)) {
            throw new Exception('Token expired');
        }

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $newPassword));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
