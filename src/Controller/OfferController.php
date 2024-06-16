<?php

namespace App\Controller;

use App\Repository\BidRepository;
use App\Service\DisplayOfferServiceInterface;
use App\Service\OfferCreationServiceInterface;
use App\Service\OfferDeletionServiceInterface;
use App\Service\OfferEditionServiceInterface;
use App\Service\OfferServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class OfferController extends AbstractController
{
    #[Route('/api/new-offer', name: 'api_new_offer', methods: ['POST'])]
    public function createOffer(Request $request, OfferCreationServiceInterface $offerCreationService): Response
    {
        $loggedUser = $this->getUser();

        if ($loggedUser->getUserInstagram() === null) {
            return $this->json([
                'success' => false,
                'message' => 'You need to verify your account through Instagram to add an offer'
            ]);
        }

        $jsonData = $request->request->get('data');
        $content = json_decode($jsonData, true);

        $images = $request->files->get('images');

        if (!isset($content['title'], $content['content'], $content['site'], $content['price'], $content['deals'])) {
            return $this->json(
                ['success' => false, 'message' => 'Missing required fields']
            );
        }

        try {
            $offerCreationService->createOffer($content, $images, $this->getUser());
        } catch (Exception $e) {
            return $this->json(['success' => false, 'message' => 'Failed to create offer ' . $e->getMessage()]);
        }

        return $this->json(['success' => true, 'message' => 'Offer created successfully']);
    }

    #[Route('/api/delete-offer/{id}', name: 'api_delete_offer', methods: ['DELETE'])]
    public function deleteOffer(int $id, OfferDeletionServiceInterface $offerDeletionService): Response
    {
        //todo: Fix for unauthorized access
        try {
            $offerDeletionService->deleteOffer($id);
            return $this->json(['success' => true, 'message' => 'Offer deleted successfully']);
        } catch (Exception $e) {
            return $this->json(['success' => false, 'message' => 'Failed to delete offer. '. $e->getMessage()]);
        }
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/edit-offer/{id}', name: 'api_edit_offer', methods: ['GET'])]
    public function editOffer(int $id, OfferEditionServiceInterface $offerEditionService): Response
    {
        try {
            return $this->json($offerEditionService->getOffer($id, $this->getUser()));
        } catch (Exception $e) {
            return $this->json(['success' => false, 'message' => 'Failed to edit offer. '. $e->getMessage()]);
        }
    }

    #[Route('/api/offers', name: 'api_offers', methods: ['GET'])]
    public function listOffers(Request $request, OfferServiceInterface $offerService): Response
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);

        try {
            return $this->json($offerService->getOffers($page, $pageSize));
        } catch (ExceptionInterface $e) {
            return $this->json(['success' => false, 'message' => 'Failed to get offers. '. $e->getMessage()]);
        }
    }

    #[Route('/api/offers/category/{id}', name: 'api_offers_by_category', methods: ['GET'])]
    public function listOffersByCategory(int $id, Request $request, OfferServiceInterface $offerService): Response
    {
        $page = $request->query->getInt('page', 1);
        $pageSize = $request->query->getInt('pageSize', 10);

        try {
            return $this->json($offerService->getOffersByCategory($id, $page, $pageSize));
        } catch (Exception $e) {
            return $this->json(['success' => false, 'message' => 'Failed to get offers. '. $e->getMessage()]);
        }
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/api/display-offer/{id}', name: 'api_display_offer', methods: ['GET'])]
    public function displayOffer(int $id, BidRepository $bidRepository, DisplayOfferServiceInterface $offerService): Response
    {
        $offer = $bidRepository->findOneBy(['id' => $id]);

        if (!$offer) {
            return $this->json(['success' => false,'message' => 'Offer not found']);
        }

        return $this->json($offerService->getOffer($offer));
    }
}
