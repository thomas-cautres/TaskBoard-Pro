<?php

declare(strict_types=1);

namespace App\Event\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

class TaskSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ObjectMapperInterface $objectMapper,
        private readonly Security $security,
        private readonly TaskRepository $taskRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TaskCreatedEvent::class => 'onTaskCreated',
        ];
    }

    public function onTaskCreated(TaskCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $taskDto = $event->getTask();

        $task = $this->objectMapper->map($taskDto, Task::class);
        $task->setCreatedBy($user);

        $this->taskRepository->save($task);
    }
}
