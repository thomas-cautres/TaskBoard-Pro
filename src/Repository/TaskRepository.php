<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Entity\ProjectColumn;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(Task $task, bool $flush = true): void
    {
        $this->getEntityManager()->persist($task);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastForProject(Project $project): ?Task
    {
        /** @var ?Task $task */
        $task = $this->createQueryBuilder('t')
            ->join('t.projectColumn', 'pc')
            ->where('pc.project = :project')
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('project', $project)
            ->getQuery()
            ->getOneOrNullResult();

        return $task;
    }

    public function findLastForProjectColumn(ProjectColumn $projectColumn): ?Task
    {
        /** @var ?Task $task */
        $task = $this->createQueryBuilder('t')
            ->where('t.projectColumn = :projectColumn')
            ->orderBy('t.position', 'DESC')
            ->setMaxResults(1)
            ->setParameter('projectColumn', $projectColumn)
            ->getQuery()
            ->getOneOrNullResult();

        return $task;
    }
}
