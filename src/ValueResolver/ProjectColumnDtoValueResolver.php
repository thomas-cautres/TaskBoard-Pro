<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Dto\Project\ProjectColumnDto;
use App\Dto\Project\ProjectDto;
use App\Entity\ProjectColumn;
use App\Repository\ProjectColumnRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final readonly class ProjectColumnDtoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private ProjectColumnRepository $projectColumnRepository,
        private ObjectMapperInterface $objectMapper,
    ) {
    }

    /**
     * @return iterable<ProjectDto>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (ProjectColumnDto::class !== $argument->getType()) {
            return [];
        }

        $uuid = $request->attributes->get('uuid');

        $project = $this->projectColumnRepository->findOneBy(['uuid' => $uuid]);

        if (!$project instanceof ProjectColumn) {
            throw new NotFoundHttpException(sprintf('Project column with uuid %s not found', $uuid));
        }

        yield $this->objectMapper->map($project, ProjectColumnDto::class);
    }
}
