<?php

namespace App\Repository;

use App\Entity\UserInstagramCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInstagramCategory>
 *
 * @method UserInstagramCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInstagramCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInstagramCategory[]    findAll()
 * @method UserInstagramCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInstagramCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInstagramCategory::class);
    }
}
