<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Dto\Task\TaskDto;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class TaskDtoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
    ) {
    }

    /**
     * @return iterable<TaskDto>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (TaskDto::class !== $argument->getType()) {
            return [];
        }

        $uuid = $request->attributes->getString('uuid');

        $task = $this->taskRepository->findOneBy(['uuid' => $uuid]);

        if (!$task instanceof Task) {
            throw new NotFoundHttpException('Task not found');
        }

        yield TaskDto::fromEntity($task);
    }
}
