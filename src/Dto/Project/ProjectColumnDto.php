<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\ProjectColumn;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: ProjectColumn::class, source: ProjectColumn::class)]
class ProjectColumnDto
{
    private int $id;
    private string $name;
    private int $position;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): ProjectColumnDto
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProjectColumnDto
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): ProjectColumnDto
    {
        $this->position = $position;

        return $this;
    }
}
