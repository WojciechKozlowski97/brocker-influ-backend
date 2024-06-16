<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailService implements EmailServiceInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ParameterBagInterface $params
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendActivationEmail(User $user): void
    {
        $activationUrl = $this->urlGenerator->generate(
            'api_activate_account',
            [
                'token' => $user->getUserActivation()->getEmailVerificationToken(),
                'email' => $user->getEmail()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $emailContent = sprintf(
            'Please activate your account by clicking the following link: <a href="%s">Activate Account</a>',
            $activationUrl
        );

        $email = (new Email())
            ->from($this->params->get('mailer_from'))
            ->to($user->getEmail())
            ->subject('Activate your account')
            ->html($emailContent);

        $this->mailer->send($email);
    }

    /**
     * @throws Exception
     */
    public function activateAccount(string $email, string $token): void
    {
        $user = $this->userRepository->findOneByEmailAndActivationToken($email, $token);

        if (!$user) {
            throw new Exception('User not found or activation token is invalid.');
        }

        $user->setIsVerified(true);
        $this->entityManager->flush();
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendSuccessResetPasswordEmail(string $email): void
    {
        $email = (new Email())
            ->from($this->params->get('mailer_from'))
            ->to($email)
            ->subject('Activate your account')
            ->html('Password reset successfully');

        $this->mailer->send($email);
    }
}
