<?php

namespace App\Controller;

use App\Service\UserRegistrationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class RegistrationController extends AbstractController
{
    #[Route('/api/registration', name: 'api_registration', methods: ['POST'])]
    public function register(Request $request, UserRegistrationServiceInterface $userService): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $result = $userService->registerUser($data['name'], $data['email'], $data['password']);

        if (array_key_exists('error', $result)) {
            return $this->json(['error' => $result['error']], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['message' => 'User successfully registered'], Response::HTTP_CREATED);
    }
}
