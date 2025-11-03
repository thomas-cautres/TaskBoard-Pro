<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\Entity\Project;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectArchivedEvent extends Event
{
    public function __construct(private readonly Project $project)
    {
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
