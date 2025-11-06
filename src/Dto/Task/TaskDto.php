<?php

declare(strict_types=1);

namespace App\Dto\Task;

use App\AppEnum\TaskPriority;
use App\Dto\UserDto;
use App\Entity\Task;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Uid\Uuid;

#[Map(target: Task::class, source: Task::class)]
class TaskDto
{
    private int $id;
    private Uuid $uuid;
    private ?string $code = null;
    private ?string $title = null;
    private ?string $description = null;
    private ?TaskPriority $priority = null;
    private ?\DateTime $endDate = null;
    #[Map(if: false)]
    private UserDto $createdBy;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;
    private ?int $position = null;

    public function __construct()
    {
        $this->uuid = Uuid::v7();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): TaskDto
    {
        $this->id = $id;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): TaskDto
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): TaskDto
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): TaskDto
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): TaskDto
    {
        $this->description = $description;

        return $this;
    }

    public function getPriority(): ?TaskPriority
    {
        return $this->priority;
    }

    public function setPriority(?TaskPriority $priority): TaskDto
    {
        $this->priority = $priority;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): TaskDto
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedBy(): ?UserDto
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserDto $createdBy): TaskDto
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): TaskDto
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): TaskDto
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): TaskDto
    {
        $this->position = $position;

        return $this;
    }
}
