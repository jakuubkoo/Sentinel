<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find emails by user ids.
     *
     * @param array<int> $userIds The user ids.
     *
     * @return array<string> The emails.
     */
    public function findEmailsByIds(array $userIds): array
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.email')
            ->where('u.id IN (:userIds)')
            ->setParameter('userIds', $userIds);

        $emails = $qb->getQuery()->getArrayResult();

        return array_column($emails, 'email');
    }
}
