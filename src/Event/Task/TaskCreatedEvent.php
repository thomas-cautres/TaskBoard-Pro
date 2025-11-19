<?php

declare(strict_types=1);

namespace App\Event\Task;

use App\Dto\Request\Task\CreateTaskFormData;
use Symfony\Contracts\EventDispatcher\Event;

final class TaskCreatedEvent extends Event
{
    public function __construct(private readonly CreateTaskFormData $task, private readonly string $projectColumnUuid)
    {
    }

    public function getTask(): CreateTaskFormData
    {
        return $this->task;
    }

    public function getProjectColumnUuid(): string
    {
        return $this->projectColumnUuid;
    }
}
