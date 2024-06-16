<?php

namespace App\Tests\Unit\Service;

use App\Entity\Category;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\UserInstagram;
use App\Repository\CategoryRepository;
use App\Repository\SiteRepository;
use App\Service\OfferCreationService;
use App\Service\OfferCreationServiceInterface;
use App\Service\ProcessImagesService;
use App\Service\ProcessImagesServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;

class OfferCreationServiceTest extends TestCase
{
    private SiteRepository $siteRepository;
    private EntityManagerInterface $entityManager;
    private CategoryRepository $categoryRepository;
    private ProcessImagesServiceInterface $processImagesService;
    private OfferCreationServiceInterface $offerCreationService;
    private array $content;

    protected function setUp(): void
    {
        $this->siteRepository = $this->createMock(SiteRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->processImagesService = $this->createMock(ProcessImagesService::class);

        $this->offerCreationService = new OfferCreationService(
            $this->siteRepository,
            $this->entityManager,
            $this->categoryRepository,
            $this->processImagesService
        );

        $this->content = [
            "title" => "Example Title",
            "content" => "Detailed description of the offer.",
            "site" => "instagram",
            "price" => 100,
            "categories" => [1],
            "deals" => [
                [
                    "content" => "Details of the first deal option.",
                    "price" => 150,
                    "delivery_days" => 7,
                    "revisions" => 2
                ],
                [
                    "content" => "Details of the second deal option.",
                    "price" => 200,
                    "delivery_days" => 10,
                    "revisions" => 3
                ]
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function testCreateOfferSuccess(): void
    {
        $site = $this->createMock(Site::class);
        $user = $this->createMock(User::class);
        $category = $this->createMock(Category::class);

        $this->siteRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'instagram'])
            ->willReturn($site);

        $userInstagram = new UserInstagram();
        $user->method('getUserInstagram')->willReturn($userInstagram);

        $this->categoryRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($category);

        $this->entityManager->expects($this->exactly(3))
            ->method('flush');

        $this->offerCreationService->createOffer($this->content, [], $user);
    }

    /**
     * @throws Exception
     */
    public function testCreateOfferWithInvalidCategory(): void
    {
        $this->content['categories'] = [100];

        $user = $this->createMock(User::class);
        $site = $this->createMock(Site::class);

        $this->siteRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'instagram'])
            ->willReturn($site);

        $this->categoryRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 100])
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The specified category does not exist');

        $this->offerCreationService->createOffer($this->content, [], $user);
    }

    public function testCreateOfferWithInvalidSite(): void
    {
        $user = $this->createMock(User::class);

        $this->siteRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => 'instagram'])
            ->willReturn(null);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The specified site does not exist.');
        $this->offerCreationService->createOffer($this->content, [], $user);
    }
}
