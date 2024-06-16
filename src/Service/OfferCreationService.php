<?php

namespace App\Service;

use App\Entity\Bid;
use App\Entity\Deal;
use App\Entity\User;
use App\Entity\UserInstagramCategory;
use App\Repository\CategoryRepository;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class OfferCreationService implements OfferCreationServiceInterface
{
    public function __construct(
        private readonly SiteRepository $siteRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CategoryRepository $categoryRepository,
        private readonly ProcessImagesServiceInterface $processImagesService
    ) {
    }

    /**
     * @throws Exception
     */
    public function createOffer(array $content, array $images, User $user): void
    {
        $bidTitle = $content['title'];
        $bidContent = $content['content'];
        $site = $content['site'];
        $price = $content['price'];
        $categories = $content['categories'];

        $site = $this->siteRepository->findOneBy(['name' => $site]);

        if (!$user) {
            throw new Exception("Cannot create the offer");
        }

        if (!$site) {
            throw new Exception("The specified site does not exist.");
        }

        $this->processCategories($categories, $user);

        $bid = new Bid();

        $bid->setUserInstagram($user->getUserInstagram());
        $bid->setSite($site);
        $bid->setTitle($bidTitle);
        $bid->setContent($bidContent);
        $bid->setPrice($price);
        $bid->setIsDeleted(false);

        $this->entityManager->persist($bid);
        $this->entityManager->flush();

        if (isset($images)) {
            $this->processImagesService->processImages($bid, $images);
        }

        $deals = $content['deals'];
        $this->processDeals($deals, $bid);
    }

    private function processDeals(array $deals, Bid $bid): void
    {
        foreach ($deals as $deal) {
            $this->saveDeal($deal, $bid);
        }

        $this->entityManager->flush();
    }

    private function saveDeal(array $dealData, Bid $bid): void
    {
        $deal = new Deal();
        $deal->setBid($bid);
        $deal->setContent($dealData['content']);
        $deal->setPrice($dealData['price']);
        $deal->setDeliveryDays($dealData['delivery_days']);
        $deal->setRevisions($dealData['revisions']);

        $this->entityManager->persist($deal);
    }

    /**
     * @throws Exception
     */
    private function processCategories(array $categories, User $user): void
    {
        foreach ($categories as $category) {
            $this->saveCategory($category, $user);
        }

        $this->entityManager->flush();
    }

    /**
     * @throws Exception
     */
    private function saveCategory(int $category, User $user): void
    {
        $category = $this->categoryRepository->findOneBy(['id' => $category]);

        if (!$category) {
            throw new Exception("The specified category does not exist");
        }

        $userInstagramCategory = new UserInstagramCategory();
        $userInstagramCategory->setCategory($category);
        $userInstagramCategory->setUser($user->getUserInstagram());

        $this->entityManager->persist($userInstagramCategory);
    }
}
