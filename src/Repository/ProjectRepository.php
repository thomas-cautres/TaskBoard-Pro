<?php

declare(strict_types=1);

namespace App\Repository;

use App\AppEnum\ProjectStatus;
use App\Dto\Project\ProjectDto;
use App\Dto\Project\ProjectFiltersDto;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly ObjectMapperInterface $objectMapper)
    {
        parent::__construct($registry, Project::class);
    }

    public function save(ProjectDto|Project $project, bool $flush = true): void
    {
        if ($project instanceof ProjectDto) {
            $entity = $this->findOneBy(['uuid' => $project->getUuid()]);

            if ($entity instanceof Project) {
                $project = $this->objectMapper->map($project, $entity);
            } else {
                $project = $this->objectMapper->map($project, Project::class);
            }
        }

        $this->getEntityManager()->persist($project);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countByUserAndName(User $user, string $name, ?ProjectDto $validatedProject): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->andWhere('p.createdBy = :createdBy')
            ->andWhere('LOWER(p.name) = :name')
            ->setParameter('createdBy', $user->getId())
            ->setParameter('name', strtolower($name));

        if ($validatedProject instanceof ProjectDto) {
            $qb->andWhere('p.id != :validatedProject')->setParameter('validatedProject', $validatedProject->getId());
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @return Paginator<Project>
     */
    public function findByUserPaginated(User $user, ProjectFiltersDto $filters, int $start, int $length): Paginator
    {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.createdBy = :user')
            ->setParameter('user', $user)
            ->setFirstResult($start)
            ->setMaxResults($length);

        $this->applyFilters($qb, $filters);
        $this->applySorting($qb, $filters->getSort());

        /** @var Paginator<Project> $paginator */
        $paginator = new Paginator($qb, false);

        return $paginator;
    }

    private function applySorting(QueryBuilder $qb, ?int $sort = null): void
    {
        match ($sort) {
            ProjectFiltersDto::SORT_NAME_ASC => $qb->addOrderBy('p.name', 'ASC'),
            ProjectFiltersDto::SORT_NAME_DESC => $qb->addOrderBy('p.name', 'DESC'),
            default => null,
        };
    }

    private function applyFilters(QueryBuilder $qb, ProjectFiltersDto $filters): void
    {
        if (null !== $filters->getName()) {
            $qb->andWhere('LOWER(p.name) LIKE LOWER(:name)')->setParameter('name', '%'.$filters->getName().'%');
        }

        if (null !== $filters->getType()) {
            $qb->andWhere('p.type = :type')->setParameter('type', $filters->getType());
        }

        if (null === $filters->getActive()) {
            $qb->andWhere('p.status = :status')->setParameter('status', ProjectStatus::Active->value);
        } elseif (ProjectFiltersDto::ACTIVE_FILTER_ARCHIVED === $filters->getActive()) {
            $qb->andWhere('p.status = :status')->setParameter('status', ProjectStatus::Archived->value);
        }
    }
}
