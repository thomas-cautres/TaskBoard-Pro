<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\Dto\Request\Project\EditProjectFormData;
use Symfony\Contracts\EventDispatcher\Event;

class ProjectEditedEvent extends Event
{
    public function __construct(private readonly EditProjectFormData $project)
    {
    }

    public function getProject(): EditProjectFormData
    {
        return $this->project;
    }
}
