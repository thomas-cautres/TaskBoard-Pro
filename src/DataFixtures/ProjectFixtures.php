<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\AppEnum\ProjectColumnName;
use App\AppEnum\ProjectType;
use App\Entity\Project;
use App\Entity\ProjectColumn;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
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
                ->setName($projectArray['name'])
                ->setDescription($projectArray['description'])
                ->setType($projectArray['type'])
                ->setStartDate($projectArray['startDate'])
                ->setEndDate($projectArray['endDate'])
                ->setCreatedBy($createdBy);

            foreach ($projectArray['columns'] as $columnArray) {
                $project->addColumn(
                    new ProjectColumn()
                        ->setName($columnArray['name'])
                        ->setPosition($columnArray['position'])
                );
            }

            $manager->persist($project);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * @return \Iterator<array{
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
        yield [
            'name' => 'Project Scrum',
            'description' => 'Project description',
            'type' => ProjectType::Scrum,
            'startDate' => new \DateTimeImmutable('2025-01-01'),
            'endDate' => new \DateTimeImmutable('2025-02-01'),
            'createdByEmail' => 'user-confirmed@domain.com',
            'columns' => [
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
            ],
        ];

        yield [
            'name' => 'Project Kanban',
            'description' => 'Project description',
            'type' => ProjectType::Kanban,
            'startDate' => new \DateTimeImmutable('2025-01-01'),
            'endDate' => new \DateTimeImmutable('2025-02-01'),
            'createdByEmail' => 'user-confirmed@domain.com',
            'columns' => [
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
            ],
        ];
    }
}
