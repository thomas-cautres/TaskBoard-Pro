<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\AppEnum\ProjectType;
use App\Entity\Project;
use App\Entity\ProjectColumn;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getProjects() as $projectArray) {
            $createdBy = $this->userRepository->findOneBy(['email' => $projectArray['createdByEmail']]);
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

    private function getProjects(): \Iterator
    {
        yield [
            'name' => 'Project name 1',
            'description' => 'Project description 1',
            'type' => ProjectType::Scrum,
            'startDate' => new \DateTimeImmutable('2025-01-01'),
            'endDate' => new \DateTimeImmutable('2025-02-01'),
            'createdByEmail' => 'user-confirmed@domain.com',
            'columns' =>
                [
                    [
                        'position' => 1,
                        'name' => 'Backlog',
                    ],
                    [
                        'position' => 2,
                        'name' => 'To do',
                    ],
                    [
                        'position' => 3,
                        'name' => 'In progress',
                    ],
                    [
                        'position' => 4,
                        'name' => 'In review',
                    ],
                    [
                        'position' => 5,
                        'name' => 'Done',
                    ],
                ]
        ];
    }
}
