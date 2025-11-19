<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use Symfony\Component\Uid\Uuid;

interface CreateProjectInterface
{
    public function getName(): string;

    public function getDescription(): ?string;

    public function getType(): ProjectType;

    public function getStartDate(): ?\DateTimeImmutable;

    public function getEndDate(): ?\DateTimeImmutable;

    public function getUuid(): Uuid;
}
