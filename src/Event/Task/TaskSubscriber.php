<?php

declare(strict_types=1);

namespace App\Event\Task;

use App\Entity\Task;
use App\Entity\User;
use App\Generator\TaskGeneratorInterface;
use App\Repository\ProjectColumnRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class TaskSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ObjectMapperInterface $objectMapper,
        private Security $security,
        private TaskRepository $taskRepository,
        private ProjectColumnRepository $projectColumnRepository,
        private TaskGeneratorInterface $taskCodeGenerator,
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

        $projectColumn = $this->projectColumnRepository->findOneBy(['uuid' => $event->getProjectColumnUuid()]);
        $task = new Task();
        $task
            ->setUuid($taskDto->getUuid())
            ->setTitle($taskDto->getTitle())
            ->setDescription($taskDto->getDescription())
            ->setPriority($taskDto->getPriority())
            ->setEndDate($taskDto->getEndDate())
            ->setCreatedBy($user)
            ->setProjectColumn($projectColumn)
            ->setCode($this->taskCodeGenerator->generate($task))
            ->setPosition($this->taskRepository->findLastForProjectColumn($projectColumn)?->getPosition() + 1 ?? 1);

        $this->taskRepository->save($task);
    }
}
