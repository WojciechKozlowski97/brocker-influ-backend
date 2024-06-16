<?php

namespace App\Controller;

use App\Repository\UserInstagramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SearchController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/search', name: 'api_search', methods: ['GET'])]
    public function searchUser(
        Request $request,
        UserInstagramRepository $userInstagramRepository,
        NormalizerInterface $normalizer,
    ): Response {
        $phrase = $request->query->get('phrase');

        $foundUsers = $normalizer->normalize(
            $userInstagramRepository->searchUsers($phrase),
            '',
            ['groups' => ['search']]
        );

        return $this->json($foundUsers);
    }
}
