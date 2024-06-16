<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByEmailAndActivationToken(string $email, string $token): ?User
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.userActivation', 'ua')
            ->where('u.email = :email')
            ->andWhere('ua.emailVerificationToken = :token')
            ->setParameter('email', $email)
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByEmailAndPasswordResetToken(string $email, string $token): ?User
    {
        return $this->createQueryBuilder('u')
            ->innerJoin('u.passwordResetToken', 'prt')
            ->where('u.email = :email')
            ->andWhere('prt.token = :token')
            ->setParameter('email', $email)
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
