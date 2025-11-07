<?php

declare(strict_types=1);

namespace App\Event\Task;

use App\Dto\Task\CreateTaskDto;
use Symfony\Contracts\EventDispatcher\Event;

final class TaskCreatedEvent extends Event
{
    public function __construct(private readonly CreateTaskDto $task, private readonly string $projectColumnUuid)
    {
    }

    public function getTask(): CreateTaskDto
    {
        return $this->task;
    }

    public function getProjectColumnUuid(): string
    {
        return $this->projectColumnUuid;
    }
}
