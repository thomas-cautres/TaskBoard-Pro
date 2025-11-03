<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\Dto\Project\ProjectDto;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectEditedEvent extends Event
{
    public function __construct(private readonly ProjectDto $project)
    {
    }

    public function getProject(): ProjectDto
    {
        return $this->project;
    }
}
