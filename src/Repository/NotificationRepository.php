<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function persist(Notification $notification, bool $flush = true): void
    {
        $this->getEntityManager()->persist($notification);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return array<mixed>
     */
    public function findByUser(User $user, bool $unread = false, int $limit = 5): array
    {
        $qb = $this->createQueryBuilder('n')
            ->andWhere('n.user = :user')
            ->orderBy('n.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setParameter('user', $user);

        if (true === $unread) {
            $qb->andWhere('n.readAt IS NULL');
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function countByUser(User $user, bool $unread = false): int
    {
        $qb = $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->andWhere('n.user = :user')
            ->setParameter('user', $user);

        if (true === $unread) {
            $qb->andWhere('n.readAt IS NULL');
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
