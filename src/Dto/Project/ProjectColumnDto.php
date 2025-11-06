<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\ProjectColumn;
use App\ObjectMapper\CollectionTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

#[Map(target: ProjectColumnDto::class, source: ProjectColumn::class)]
class ProjectColumnDto
{
    private int $id;
    private ?Uuid $uuid;
    private string $name;
    private int $position;
    public string $projectUuid;
    #[Map(target: 'tasks', source: 'tasks', transform: CollectionTransformer::class)]
    public array $tasks = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getProjectUuid(): string
    {
        return $this->projectUuid;
    }

    public function setProjectUuid(string $projectUuid): static
    {
        $this->projectUuid = $projectUuid;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(?Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }
}
