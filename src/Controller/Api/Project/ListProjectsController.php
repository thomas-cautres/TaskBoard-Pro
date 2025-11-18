<?php

declare(strict_types=1);

namespace App\Controller\Api\Project;

use App\Dto\Api\ListMetaDto;
use App\Dto\Project\Api\ProjectListItemDto;
use App\Dto\Project\ProjectFiltersDto;
use App\Entity\Project;
use App\Paginator\ProjectsPaginator;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/projects/{page<\d+>}', name: 'api_projects_list', methods: ['GET'])]
class ListProjectsController extends AbstractController
{
    public function __construct(
        private readonly ProjectsPaginator $paginator,
    ) {
    }

    public function __invoke(#[MapQueryString] ProjectFiltersDto $filters, int $page = 1): JsonResponse
    {
        $pagination = $this->paginator->paginate($page, $filters);

        return $this->json([
            'data' => $this->getProjectsDtos($pagination->getObjects()),
            'meta' => new ListMetaDto($page, $pagination->getResultsPerPage(), $pagination->getTotalResults(), $pagination->getTotalPages()),
            'filters' => $filters,
        ]);
    }

    /**
     * @param Paginator<Project>|Project[] $projectsPaginated
     *
     * @return ProjectListItemDto[]
     */
    private function getProjectsDtos(Paginator|array $projectsPaginated): array
    {
        $projects = [];
        foreach ($projectsPaginated as $project) {
            $projects[] = ProjectListItemDto::fromEntity($project);
        }

        return $projects;
    }
}
