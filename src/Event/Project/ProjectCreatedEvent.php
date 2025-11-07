<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\Dto\Project\CreateProjectDto;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectCreatedEvent extends Event
{
    public function __construct(
        private readonly CreateProjectDto $project,
    ) {
    }

    public function getProject(): CreateProjectDto
    {
        return $this->project;
    }
}
