<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\Project;

interface ProjectDtoInterface
{
    public static function fromEntity(Project $project): self;
}
