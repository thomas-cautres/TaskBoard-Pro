<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Dto\Project\EditProjectDto;
use App\Dto\Project\ProjectDto;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class EditProjectDtoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
    ) {
    }

    /**
     * @return iterable<ProjectDto>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (EditProjectDto::class !== $argument->getType()) {
            return [];
        }

        $uuid = $request->attributes->get('uuid');

        $project = $this->projectRepository->findOneWithColumnsAndTasks($uuid);

        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        yield EditProjectDto::fromEntity($project);
    }
}
