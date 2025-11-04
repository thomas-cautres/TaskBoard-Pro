<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\ProjectColumn;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: ProjectColumn::class, source: ProjectColumn::class)]
class ProjectColumnDto
{
    public int $id;
    public string $name;
    public int $position;
}
