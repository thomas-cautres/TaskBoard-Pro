<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use App\Validator\Constraint\UniqueUserProjectName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CreateProjectFormDto implements CreateProjectInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    #[UniqueUserProjectName]
    private string $name;
    private ?string $description = null;
    #[Assert\NotBlank(message: 'validator.project.type.empty')]
    #[Assert\Choice(callback: [ProjectType::class, 'values'])]
    private ProjectType $type;
    private ?\DateTimeImmutable $startDate = null;
    #[Assert\Callback([CreateProjectFormDto::class, 'validateEndDate'])]
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

    public function setName(string $name): CreateProjectFormDto
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): CreateProjectFormDto
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ProjectType
    {
        return $this->type;
    }

    public function setType(ProjectType $type): CreateProjectFormDto
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): CreateProjectFormDto
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): CreateProjectFormDto
    {
        $this->endDate = $endDate;

        return $this;
    }

    public static function validateEndDate(?\DateTimeImmutable $value, ExecutionContextInterface $context): void
    {
        /** @var CreateProjectFormDto $project */
        $project = $context->getObject();
        $startDate = $project->getStartDate();

        if (null === $value || $value >= $startDate) {
            return;
        }

        $context->addViolation('validator.project.end.date');
    }
}
