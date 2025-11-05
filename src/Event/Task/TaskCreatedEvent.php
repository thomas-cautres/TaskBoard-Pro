<?php

declare(strict_types=1);

namespace App\Event\Task;

use App\Dto\Task\TaskDto;
use Symfony\Contracts\EventDispatcher\Event;

class TaskCreatedEvent extends Event
{
    public function __construct(private readonly TaskDto $task)
    {
    }

    public function getTask(): TaskDto
    {
        return $this->task;
    }
}
