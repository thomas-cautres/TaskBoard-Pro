<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Behat\Behat\Context\Context;

class ProjectContext implements Context
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    /**
     * @Then the project :name should exist in database
     */
    public function theProjectShouldExistInDatabase(string $name): void
    {
        $project = $this->projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }
    }

    /**
     * @Then the project :name should have type :type
     */
    public function theProjectShouldHaveType(string $name, string $type): void
    {
        $project = $this->projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }

        if ($project->getType()->value !== $type) {
            throw new \Exception(sprintf('Project type is "%s", expected "%s"', $project->getType()->value, $type));
        }
    }

    /**
     * @Then the project :name should have status :status
     */
    public function theProjectShouldBehaveStatus(string $name, string $status): void
    {
        $project = $this->projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }

        if ($project->getStatusAsString() !== $status) {
            throw new \Exception(sprintf('Project status is "%s", expected "%s"', $project->getStatusAsString(), $status));
        }
    }

    /**
     * @Then the project :name should have :count columns
     */
    public function theProjectShouldHaveColumns(string $name, int $count): void
    {
        $project = $this->projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }

        $actualCount = $project->getColumns()->count();
        if ($actualCount !== $count) {
            throw new \Exception(sprintf('Project has %d columns, expected %d', $actualCount, $count));
        }
    }

    /**
     * @Then the project :name should have column :columnName at position :position
     */
    public function theProjectShouldHaveColumnAtPosition(string $name, string $columnName, int $position): void
    {
        $project = $this->projectRepository->findOneBy(['name' => $name]);

        if (!$project instanceof Project) {
            throw new \Exception(sprintf('Project with name "%s" not found', $name));
        }

        $column = $project->getColumns()->get($position);

        if (!$column) {
            throw new \Exception(sprintf('No column found at position %d', $position));
        }

        if ($column->getName() !== $columnName) {
            throw new \Exception(sprintf('Column at position %d is "%s", expected "%s"', $position, $column->getName(), $columnName));
        }
    }
}
