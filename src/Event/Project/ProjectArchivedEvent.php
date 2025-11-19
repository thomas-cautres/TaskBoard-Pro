<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\Dto\View\Project\ProjectModel;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectArchivedEvent extends Event
{
    public function __construct(private readonly ProjectModel $project)
    {
    }

    public function getProject(): ProjectModel
    {
        return $this->project;
    }
}
