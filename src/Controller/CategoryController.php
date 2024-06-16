<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/api/get-categories', name: 'api_get_categories', methods: ['GET'])]
    public function getCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAllCategories();
        return $this->json($categories);
    }
}
