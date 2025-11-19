<?php

declare(strict_types=1);

namespace App\Controller\Api\Project;

use App\Dto\Project\Api\CreateProjectApiDto;
use App\Event\Project\ProjectCreatedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/project', name: 'api_project_create', methods: 'POST')]
class CreateProjectController extends AbstractController
{
    public function __invoke(
        #[MapRequestPayload] CreateProjectApiDto $project,
        EventDispatcherInterface $dispatcher,
    ): JsonResponse {
        $dispatcher->dispatch(new ProjectCreatedEvent($project));

        return $this->json($project, Response::HTTP_CREATED);
    }
}
