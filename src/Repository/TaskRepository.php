<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Task\TaskDto;
use App\Entity\Project;
use App\Entity\ProjectColumn;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly ObjectMapperInterface $objectMapper)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(TaskDto|Task $task, bool $flush = true): void
    {
        if ($task instanceof TaskDto) {
            $entity = $this->findOneBy(['uuid' => $task->getUuid()]);
            $task = $this->objectMapper->map($task, ($entity instanceof Task) ? $entity : Task::class);
        }

        $this->getEntityManager()->persist($task);

        if (true === $flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastForProject(Project $project): ?Task
    {
        return $this->createQueryBuilder('t')
            ->join('t.projectColumn', 'pc')
            ->where('pc.project = :project')
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->setParameter('project', $project)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findLastForProjectColumn(ProjectColumn $projectColumn): ?Task
    {
        return $this->createQueryBuilder('t')
            ->where('t.projectColumn = :projectColumn')
            ->orderBy('t.position', 'DESC')
            ->setMaxResults(1)
            ->setParameter('projectColumn', $projectColumn)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
