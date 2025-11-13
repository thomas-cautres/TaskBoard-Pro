<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\AppEnum\ProjectColumnName;
use App\AppEnum\ProjectType;
use App\AppEnum\TaskPriority;
use App\Entity\Project;
use App\Entity\ProjectColumn;
use App\Entity\Task;
use App\Entity\User;
use App\Generator\TaskGeneratorInterface;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TaskGeneratorInterface $taskGenerator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getProjects() as $projectArray) {
            $createdBy = $this->userRepository->findOneBy(['email' => $projectArray['createdByEmail']]);

            if (!$createdBy instanceof User) {
                throw new \LogicException(sprintf('No user found for email %s', $projectArray['createdByEmail']));
            }

            $project = new Project();
            $project
                ->setUuid($projectArray['uuid'])
                ->setName($projectArray['name'])
                ->setDescription($projectArray['description'])
                ->setType($projectArray['type'])
                ->setStartDate($projectArray['startDate'])
                ->setEndDate($projectArray['endDate'])
                ->setCreatedBy($createdBy)
                ->setCreatedAt(new \DateTimeImmutable());

            foreach ($projectArray['columns'] as $columnArray) {
                $project->addColumn(
                    new ProjectColumn()
                        ->setName($columnArray['name'])
                        ->setPosition($columnArray['position'])
                );
            }

            foreach ($projectArray['tasks'] as $taskArray) {
                $columnName = $taskArray['column'];

                $column = $project->getColumns()->findFirst(fn (int $index, ProjectColumn $column) => $column->getName() === $columnName);

                if (!$column instanceof ProjectColumn) {
                    continue;
                }

                $task = new Task();
                $task->setProjectColumn($column);
                $task
                    ->setUuid(Uuid::v7())
                    ->setTitle($taskArray['title'])
                    ->setDescription($taskArray['description'])
                    ->setPriority(TaskPriority::tryFrom($taskArray['priority']))
                    ->setEndDate(new \DateTime($taskArray['endDate']))
                    ->setCreatedBy($createdBy)
                    ->setCode($taskArray['code'])
                    ->setPosition($taskArray['position']);

                $column->addTask($task);
                $manager->persist($column);
            }

            $manager->persist($project);

            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * @return \Iterator<array{
     *     uuid: Uuid,
     *     name: string,
     *     description: string|null,
     *     type: ProjectType,
     *     startDate: \DateTimeImmutable|null,
     *     endDate: \DateTimeImmutable|null,
     *     createdByEmail: string,
     *     columns: array<array{position: int, name: string}>
     * }>
     */
    private function getProjects(): \Iterator
    {
        $jsonPath = __DIR__.'/../../data/projects.json';

        if (!file_exists($jsonPath)) {
            throw new \RuntimeException(sprintf('Projects data file not found at: %s', $jsonPath));
        }

        $jsonContent = (string) file_get_contents($jsonPath);

        /** @var array<array<string, string>> $projectsData */
        $projectsData = json_decode($jsonContent, true, flags: JSON_THROW_ON_ERROR);

        foreach ($projectsData as $projectData) {
            yield [
                'uuid' => Uuid::fromString($projectData['uuid']),
                'name' => $projectData['name'],
                'description' => $projectData['description'] ?? null,
                'type' => ProjectType::from($projectData['type']),
                'startDate' => isset($projectData['startDate'])
                    ? new \DateTimeImmutable($projectData['startDate'])
                    : null,
                'endDate' => isset($projectData['endDate'])
                    ? new \DateTimeImmutable($projectData['endDate'])
                    : null,
                'createdByEmail' => $projectData['createdByEmail'],
                'columns' => $this->getColumnsForProjectType(ProjectType::from($projectData['type'])),
                'tasks' => $projectData['tasks'] ?? [],
            ];
        }
    }

    /**
     * @return array<array{position: int, name: string}>
     */
    private function getColumnsForProjectType(ProjectType $type): array
    {
        return match ($type) {
            ProjectType::Scrum => $this->getScrumColumns(),
            ProjectType::Kanban => $this->getKanbanColumns(),
            ProjectType::Basic => $this->getBasicColumns(),
        };
    }

    /**
     * @return array<array{position: int, name: string}>
     */
    private function getScrumColumns(): array
    {
        return [
            [
                'position' => 1,
                'name' => ProjectColumnName::BackLog->value,
            ],
            [
                'position' => 2,
                'name' => ProjectColumnName::ToDo->value,
            ],
            [
                'position' => 3,
                'name' => ProjectColumnName::InProgress->value,
            ],
            [
                'position' => 4,
                'name' => ProjectColumnName::Review->value,
            ],
            [
                'position' => 5,
                'name' => ProjectColumnName::Done->value,
            ],
        ];
    }

    /**
     * @return array<array{position: int, name: string}>
     */
    private function getKanbanColumns(): array
    {
        return [
            [
                'position' => 1,
                'name' => ProjectColumnName::ToDo->value,
            ],
            [
                'position' => 2,
                'name' => ProjectColumnName::InProgress->value,
            ],
            [
                'position' => 3,
                'name' => ProjectColumnName::Done->value,
            ],
        ];
    }

    /**
     * @return array<array{position: int, name: string}>
     */
    private function getBasicColumns(): array
    {
        return [
            [
                'position' => 1,
                'name' => ProjectColumnName::Open->value,
            ],
            [
                'position' => 2,
                'name' => ProjectColumnName::Closed->value,
            ],
        ];
    }
}
