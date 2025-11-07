<?php

declare(strict_types=1);

namespace App\Event\Task;

use App\Entity\ProjectColumn;
use App\Entity\Task;
use App\Entity\User;
use App\Generator\TaskGeneratorInterface;
use App\Repository\ProjectColumnRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class TaskSubscriber implements EventSubscriberInterface
{
    public function __construct(
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

        if (!$projectColumn instanceof ProjectColumn) {
            throw new EntityNotFoundException(sprintf('Project column with uuid %s not found', $event->getProjectColumnUuid()));
        }

        $task = new Task();
        /** @var string $taskCode */
        $taskCode = $this->taskCodeGenerator->generate($task);
        $task
            ->setUuid($taskDto->getUuid())
            ->setTitle($taskDto->getTitle())
            ->setDescription($taskDto->getDescription())
            ->setPriority($taskDto->getPriority())
            ->setEndDate($taskDto->getEndDate())
            ->setCreatedBy($user)
            ->setProjectColumn($projectColumn)
            ->setCode($taskCode)
            ->setPosition((int) $this->taskRepository->findLastForProjectColumn($projectColumn)?->getPosition() + 1);

        $this->taskRepository->save($task);
    }
}
