<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Dto\View\Project\ProjectModel;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ProjectDtoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
    ) {
    }

    /**
     * @return iterable<ProjectModel>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (ProjectModel::class !== $argument->getType()) {
            return [];
        }

        $uuid = $request->attributes->getString('uuid');

        $project = $this->projectRepository->findOneWithColumnsAndTasks($uuid);

        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        yield ProjectModel::fromEntity($project);
    }
}
