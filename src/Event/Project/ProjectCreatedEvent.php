<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\Dto\Request\Project\CreateProjectInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectCreatedEvent extends Event
{
    public function __construct(
        private readonly CreateProjectInterface $project,
    ) {
    }

    public function getProject(): CreateProjectInterface
    {
        return $this->project;
    }
}
