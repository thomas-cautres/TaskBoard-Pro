<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use App\ObjectMapper\CollectionTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

class ProjectDto extends AbstractProjectDto
{
    private int $id;
    private ?Uuid $uuid;
    private string $name;
    private ?string $description;
    private ProjectType $type;
    private ?\DateTimeImmutable $startDate;
    private ?\DateTimeImmutable $endDate;
    private \DateTimeImmutable $createdAt;
    /** @var ProjectColumnDto[] */
    #[Map(target: 'columns', source: 'columnsSortedByPosition', transform: CollectionTransformer::class)]
    public array $columns = [];

    public function __construct()
    {
        $this->uuid = Uuid::v7();
        $this->createdAt = new \DateTimeImmutable('now');
    }

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ProjectType
    {
        return $this->type;
    }

    public function setType(ProjectType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return ProjectColumnDto[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param ProjectColumnDto[] $columns
     */
    public function setColumns(array $columns): static
    {
        $this->columns = $columns;

        return $this;
    }
}
