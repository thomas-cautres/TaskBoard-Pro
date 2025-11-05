<?php

declare(strict_types=1);

namespace App\Dto\Task;

use App\AppEnum\TaskPriority;
use Symfony\Component\Uid\Uuid;

class TaskDto
{
    private ?int $id = null;
    private ?Uuid $uuid;
    private ?string $code = null;
    private ?string $title = null;
    private ?string $description = null;
    private ?TaskPriority $priority = null;
    private ?\DateTime $endDate = null;
    private ?string $createdByEmail = null;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;
    private ?int $position = null;

    public function __construct()
    {
        $this->uuid = Uuid::v7();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getPriority(): ?TaskPriority
    {
        return $this->priority;
    }

    public function setPriority(?TaskPriority $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedByEmail(): ?string
    {
        return $this->createdByEmail;
    }

    public function setCreatedByEmail(?string $createdByEmail): static
    {
        $this->createdByEmail = $createdByEmail;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
