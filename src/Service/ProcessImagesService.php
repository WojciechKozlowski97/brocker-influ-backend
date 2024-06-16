<?php

namespace App\Service;

use App\Entity\Bid;
use App\Entity\BidImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProcessImagesService implements ProcessImagesServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function processImages(Bid $bid, array $images): void
    {
        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $bidImage = new BidImage();
                $bidImage->setBid($bid);
                $bidImage->setImageFile($image);

                $this->entityManager->persist($bidImage);
            }
        }

        $this->entityManager->flush();
    }
}
