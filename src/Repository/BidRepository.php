<?php

namespace App\Repository;

use App\Entity\Bid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bid>
 *
 * @method Bid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bid[]    findAll()
 * @method Bid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bid::class);
    }

    public function findBidByUserId(int $userId): array
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.userInstagram', 'ui')
            ->innerJoin('ui.user', 'u')
            ->andWhere('b.isDeleted = :isDeleted')
            ->andWhere('u.id = :userId')
            ->setParameter('isDeleted', 0)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function findAllBidsSortedByUpdatedAt(int $page = 1, int $pageSize = 10): Paginator
    {
        $query = $this->createQueryBuilder('b')
            ->where('b.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false)
            ->orderBy('b.updatedAt', 'DESC')
            ->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        return new Paginator($query, true);
    }

    public function findOffersByCategoryId(int $categoryId, int $page = 1, int $pageSize = 10): Paginator
    {
        $query = $this->createQueryBuilder('b')
            ->innerJoin('b.userInstagram', 'ui')
            ->innerJoin('ui.userInstagramCategories', 'uic')
            ->innerJoin('uic.Category', 'c')
            ->where('c.id = :category')
            ->setParameter('category', $categoryId)
            ->getQuery()
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        return new Paginator($query, true);
    }
}
