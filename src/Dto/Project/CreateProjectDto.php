<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use App\Validator\Constraint\UniqueUserProjectName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CreateProjectDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    #[UniqueUserProjectName]
    private string $name;
    private ?string $description = null;
    #[Assert\NotBlank(message: 'validator.project.type.empty')]
    private ProjectType $type;
    private ?\DateTimeImmutable $startDate = null;
    #[Assert\Callback([CreateProjectDto::class, 'validateEndDate'])]
    private ?\DateTimeImmutable $endDate = null;
    private Uuid $uuid;

    public function __construct()
    {
        $this->uuid = Uuid::v7();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): CreateProjectDto
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): CreateProjectDto
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ProjectType
    {
        return $this->type;
    }

    public function setType(ProjectType $type): CreateProjectDto
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable|string|null $startDate): CreateProjectDto
    {
        $this->startDate = is_string($startDate) ? new \DateTimeImmutable($startDate) : $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable|string|null $endDate): CreateProjectDto
    {
        $this->endDate = is_string($endDate) ? new \DateTimeImmutable($endDate) : $endDate;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public static function validateEndDate(?\DateTimeImmutable $value, ExecutionContextInterface $context): void
    {
        /** @var CreateProjectDto $project */
        $project = $context->getObject();
        $startDate = $project->getStartDate();

        if (null === $value || $value >= $startDate) {
            return;
        }

        $context->addViolation('validator.project.end.date');
    }
}
