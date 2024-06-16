<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\EmailServiceInterface;
use App\Service\TokenCheckerServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    public function __construct(
        private readonly EmailServiceInterface $emailService,
        private readonly UserRepository $userRepository
    ) {
    }

    #[Route('/api/active-account', name: 'api_activate_account', methods: ['GET'])]
    public function activateAccount(Request $request): Response
    {
        $email = $request->query->get('email');
        $token = $request->query->get('token');

        try {
            $this->emailService->activateAccount($email, $token);
        } catch (Exception $e) {
            return $this->json(['success' => false, 'message' => 'Failed to send email. ' . $e->getMessage()]);
        }

        return $this->json(['success' => true, 'message' => 'Account activated successfully']);
    }

    #[Route('/api/send-email', name: 'api_send_email', methods: ['POST'])]
    public function sendEmail(Request $request, TokenCheckerServiceInterface $tokenCheckerService): Response
    {
        $postBody = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['email' => $postBody['email']]);

        if (!$user) {
            return $this->json(['success' => false, 'message' => 'User for given email not found']);
        }

        if (!$tokenCheckerService->checkTokenAge($user->getUserActivation()->getCreatedAt(), 5)) {
            return $this->json(['success' => false, 'message' => 'Token age is less than 5 minutes']);
        }

        try {
            $this->emailService->sendActivationEmail($user);
            return $this->json(['success' => true, 'message' => 'Email sent successfully']);
        } catch (TransportExceptionInterface $e) {
            return $this->json(['success' => false, 'message' => 'Failed to send email. ' . $e->getMessage()]);
        }
    }
}
