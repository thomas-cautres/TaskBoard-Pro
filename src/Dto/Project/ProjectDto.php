<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use App\Entity\Project;
use App\ObjectMapper\ProjectColumnsCollectionTransformer;
use App\ObjectMapper\UserTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

#[Map(target: Project::class)]
class ProjectDto
{
    private Uuid $uuid;
    private string $name;
    private ?string $description;
    private ProjectType $type;
    private ?\DateTimeImmutable $startDate;
    private ?\DateTimeImmutable $endDate;
    private \DateTimeImmutable $createdAt;
    #[Map(target: 'createdBy', transform: UserTransformer::class)]
    private string $createdByEmail;
    #[Map(target: 'columnsCount')]
    private int $columnsCount;

    #[Map(target: 'columnsSortedByPosition', transform: ProjectColumnsCollectionTransformer::class)]
    private array $columns;
    #[Map(target: 'statusAsString')]
    private string $status;

    public function getFormattedCreatedAt(string $format = 'Y-m-d'): string
    {
        return $this->createdAt->format($format);
    }

    public function getFormattedStartDate(string $format = 'Y-m-d'): ?string
    {
        return $this->startDate?->format($format);
    }

    public function getFormattedEndDate(string $format = 'Y-m-d'): ?string
    {
        return $this->endDate?->format($format);
    }

    public function getTypeLabel(): string
    {
        return $this->type->value;
    }

    public function hasDescription(): bool
    {
        return null !== $this->description && '' !== $this->description;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getType(): ProjectType
    {
        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): ProjectDto
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function setName(string $name): ProjectDto
    {
        $this->name = $name;

        return $this;
    }

    public function setDescription(?string $description): ProjectDto
    {
        $this->description = $description;

        return $this;
    }

    public function setType(ProjectType $type): ProjectDto
    {
        $this->type = $type;

        return $this;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): ProjectDto
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): ProjectDto
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): ProjectDto
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedByEmail(): string
    {
        return $this->createdByEmail;
    }

    public function setCreatedByEmail(string $createdByEmail): ProjectDto
    {
        $this->createdByEmail = $createdByEmail;

        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $columns): ProjectDto
    {
        $this->columns = $columns;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): ProjectDto
    {
        $this->status = $status;

        return $this;
    }

    public function getColumnsCount(): int
    {
        return $this->columnsCount;
    }

    public function setColumnsCount(int $columnsCount): ProjectDto
    {
        $this->columnsCount = $columnsCount;

        return $this;
    }
}
