<?php

declare(strict_types=1);

namespace App\Dto\Request\Project;

use App\AppEnum\ProjectType;
use App\Validator\Constraint\UniqueUserProjectName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CreateProjectRequest implements CreateProjectInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    #[UniqueUserProjectName]
    private string $name;
    private ?string $description = null;
    #[Assert\NotBlank(message: 'validator.project.type.empty')]
    #[Assert\Choice(callback: [ProjectType::class, 'values'])]
    private string $type;
    private ?string $startDate = null;
    #[Assert\Callback([CreateProjectRequest::class, 'validateEndDate'])]
    private ?string $endDate = null;
    private Uuid $uuid;

    public function __construct()
    {
        $this->uuid = Uuid::v7();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CreateProjectRequest
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): CreateProjectRequest
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ProjectType
    {
        return ProjectType::from($this->type);
    }

    public function setType(string $type): CreateProjectRequest
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        if (null === $this->startDate) {
            return null;
        }

        return new \DateTimeImmutable($this->startDate);
    }

    public function setStartDate(?string $startDate): CreateProjectRequest
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        if (null === $this->endDate) {
            return null;
        }

        return new \DateTimeImmutable($this->endDate);
    }

    public function setEndDate(?string $endDate): CreateProjectRequest
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public static function validateEndDate(?string $value, ExecutionContextInterface $context): void
    {
        if (null === $value) {
            return;
        }

        /** @var CreateProjectRequest $project */
        $project = $context->getObject();
        $startDate = $project->getStartDate();
        $endDate = new \DateTimeImmutable($value);

        if ($endDate >= $startDate) {
            return;
        }

        $context->addViolation('validator.project.end.date');
    }
}
