<?php

namespace App\Repository;

use App\Entity\BidImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BidImage>
 *
 * @method BidImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method BidImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method BidImage[]    findAll()
 * @method BidImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BidImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BidImage::class);
    }
}
