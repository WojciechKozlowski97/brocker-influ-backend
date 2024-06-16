<?php

namespace App\Controller;

use App\Service\BidRefreshCheckerInterface;
use App\Service\RefreshBidServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BidController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/api/bid/{id}/check-refresh', name: 'api_check_bid_refresh', methods: ['GET'])]
    public function checkBidRefresh(int $id, BidRefreshCheckerInterface $bidRefreshChecker): Response
    {
        try {
            $canRefresh = $bidRefreshChecker->canBidBeRefreshed($id);
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()]);
        }

        return $this->json(['canRefresh' => $canRefresh]);
    }

    #[Route('/api/bid/{id}/refresh-bid', name: 'api_refresh_bid', methods: ['POST'])]
    public function refreshBid(int $id, RefreshBidServiceInterface $refreshBidService): Response
    {
        try {
            $refreshBidService->refreshBid($id, $this->getUser());
            return $this->json(['success' => true, 'message' => 'Bid has been successfully refreshed']);
        } catch (Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
