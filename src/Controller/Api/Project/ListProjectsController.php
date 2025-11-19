<?php

declare(strict_types=1);

namespace App\Controller\Api\Project;

use App\Dto\Request\Project\ProjectFiltersRequest;
use App\Dto\Response\Project\ProjectListResponse;
use App\Paginator\PaginatorInterface;
use App\Paginator\ProjectsPaginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/projects/{page<\d+>}', name: 'api_projects_list', methods: ['GET'])]
class ListProjectsController extends AbstractController
{
    public function __invoke(
        #[MapQueryString] ProjectFiltersRequest $filters,
        #[Autowire(service: ProjectsPaginator::class)] PaginatorInterface $paginator,
        int $page = 1,
    ): JsonResponse {
        return $this->json(ProjectListResponse::fromPagination($paginator->paginate($page, $filters), $filters));
    }
}
