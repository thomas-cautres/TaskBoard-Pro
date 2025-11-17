<?php

declare(strict_types=1);

namespace App\Dto\Task;

use App\AppEnum\TaskPriority;
use App\Entity\ProjectColumn;
use App\Entity\Task;

final readonly class TaskDto
{
    public function __construct(
        private string $uuid,
        private string $code,
        private string $title,
        private \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt,
        private int $position,
        private string $columnName,
        private ?string $description = null,
        private ?TaskPriority $priority = null,
        private ?\DateTime $endDate = null,
    ) {
    }

    public static function fromEntity(Task $task): self
    {
        if (!$task->getProjectColumn() instanceof ProjectColumn) {
            throw new \LogicException(sprintf('Column missing for task of uuid %s', $task->getUuid()->toRfc4122()));
        }

        return new self(
            uuid: $task->getUuid()->toRfc4122(),
            code: $task->getCode(),
            title: $task->getTitle(),
            createdAt: $task->getCreatedAt(),
            updatedAt: $task->getUpdatedAt(),
            position: $task->getPosition(),
            columnName: $task->getProjectColumn()->getName(),
            description: $task->getDescription(),
            priority: $task->getPriority(),
            endDate: $task->getEndDate()
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPriority(): ?TaskPriority
    {
        return $this->priority;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function isOverdue(): bool
    {
        if (null === $this->getEndDate()) {
            return false;
        }

        return new \DateTimeImmutable() > $this->getEndDate();
    }
}
