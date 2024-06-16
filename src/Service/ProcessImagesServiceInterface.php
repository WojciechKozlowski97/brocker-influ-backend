<?php

namespace App\Service;

use App\Entity\Bid;

interface ProcessImagesServiceInterface
{
    public function processImages(Bid $bid, array $images): void;
}
