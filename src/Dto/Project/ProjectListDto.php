<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use Symfony\Component\Uid\Uuid;

class ProjectListDto extends AbstractProjectDto
{
    public int $id;
    public ?Uuid $uuid;
    public string $name;
    public ?string $description;
    public ProjectType $type;
}
