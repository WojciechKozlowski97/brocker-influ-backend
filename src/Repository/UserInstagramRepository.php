<?php

namespace App\Repository;

use App\Entity\UserInstagram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInstagram>
 *
 * @method UserInstagram|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInstagram|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInstagram[]    findAll()
 * @method UserInstagram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInstagramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInstagram::class);
    }

    public function searchUsers(?string $phrase): array
    {
        return $this->createQueryBuilder('ui')
            ->innerJoin('ui.user', 'u')
            ->innerJoin('ui.bids', ('b'))
            ->Where('ui.username LIKE :query')
            ->orWhere('u.name LIKE :query')
            ->andWhere('b.isDeleted = 0')
            ->setParameter('query', '%' . $phrase  . '%')
            ->getQuery()
            ->getResult();
    }
}
