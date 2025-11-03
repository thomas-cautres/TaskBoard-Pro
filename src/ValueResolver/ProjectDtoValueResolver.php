<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Dto\Project\ProjectDto;
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

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (ProjectDto::class !== $argument->getType()) {
            return [];
        }

        $uuid = $request->attributes->get('uuid');

        $project = $this->projectRepository->findOneBy(['uuid' => $uuid]);

        if (!$project instanceof Project) {
            throw new NotFoundHttpException('Project not found');
        }

        yield ProjectDto::fromEntity($project);
    }
}
