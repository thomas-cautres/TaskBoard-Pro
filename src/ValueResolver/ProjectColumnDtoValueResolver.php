<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Dto\Project\ProjectColumnDto;
use App\Entity\ProjectColumn;
use App\Repository\ProjectColumnRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ProjectColumnDtoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private ProjectColumnRepository $projectColumnRepository,
    ) {
    }

    /**
     * @return iterable<ProjectColumnDto>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (ProjectColumnDto::class !== $argument->getType()) {
            return [];
        }

        $uuid = $request->attributes->getString('uuid');

        $projectColumn = $this->projectColumnRepository->findOneBy(['uuid' => $uuid]);

        if (!$projectColumn instanceof ProjectColumn) {
            throw new NotFoundHttpException(sprintf('Project column with uuid %s not found', $uuid));
        }

        yield ProjectColumnDto::fromEntity($projectColumn);
    }
}
