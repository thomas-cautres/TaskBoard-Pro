<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\Dto\Project\EditProjectDto;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectEditedEvent extends Event
{
    public function __construct(private readonly EditProjectDto $project)
    {
    }

    public function getProject(): EditProjectDto
    {
        return $this->project;
    }
}
