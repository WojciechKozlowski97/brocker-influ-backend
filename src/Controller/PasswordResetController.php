<?php

namespace App\Controller;

use App\Form\PasswordResetType;
use App\Service\EmailServiceInterface;
use App\Service\PasswordResetServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends AbstractController
{
    public function __construct(private readonly PasswordResetServiceInterface $passwordResetService)
    {
    }

    #[Route('/api/password-reset-request', name: 'api_password_reset_request', methods: ['POST'])]
    public function passwordResetRequest(Request $request): Response
    {
        $postBody = json_decode($request->getContent(), true);
        $email = $postBody['email'];

        try {
            $this->passwordResetService->sendPasswordResetEmail($email);
            return $this->json(['success' => true, 'message' => 'Reset password email sent successfully']);
        } catch (TransportExceptionInterface $e) {
            return $this->json(['success' => false, 'message' => 'Failed to send email. ' . $e->getMessage()]);
        }
    }

    #[Route('/api/password-reset', name: 'api_password_reset', methods: ['POST'])]
    public function passwordReset(Request $request, EmailServiceInterface $emailService): Response
    {
        $postBody = json_decode($request->getContent(), true);

        if (!isset($postBody['email'], $postBody['token'], $postBody['newPassword'])) {
            return $this->json([
                'success' => false,
                'message' => 'Missing data (email, token, or newPassword).'
            ]);
        }

        $form = $this->createForm(PasswordResetType::class);
        $form->submit($postBody);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $postBody['email'];
            $token = $postBody['token'];
            $newPassword = $postBody['newPassword'];

            try {
                $this->passwordResetService->resetPassword($email, $token, $newPassword);
            } catch (Exception $e) {
                return $this->json([
                    'success' => false,
                    'message' => 'Failed to reset password: ' . $e->getMessage()
                ]);
            }

            try {
                $emailService->sendSuccessResetPasswordEmail($email);
            } catch (TransportExceptionInterface $e) {
                return $this->json([
                    'success' => false,
                    'message' => 'Password reset successfully, but failed to send confirmation email: ' . $e->getMessage(
                        )
                ]);
            }

            return $this->json([
                'success' => true,
                'message' => 'Password reset successfully, confirmation email sent.'
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        return $this->json([
            'success' => false,
            'message' => $errors
        ]);
    }
}
