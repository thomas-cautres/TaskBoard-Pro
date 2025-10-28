<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function persist(Project $project, bool $flush = true): void
    {
        $this->getEntityManager()->persist($project);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countByUserAndName(User $user, string $name): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.createdBy = :createdBy')
            ->andWhere('LOWER(p.name) = :name')
            ->setParameter('createdBy', $user->getId())
            ->setParameter('name', strtolower($name))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Paginator<Project>
     */
    public function findByUserPaginated(User $user, int $start, int $length): Paginator
    {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.createdBy = :user')
            ->setParameter('user', $user)
            ->setFirstResult($start)
            ->setMaxResults($length);

        /** @var Paginator<Project> $paginator */
        $paginator = new Paginator($qb, false);

        return $paginator;
    }
}
