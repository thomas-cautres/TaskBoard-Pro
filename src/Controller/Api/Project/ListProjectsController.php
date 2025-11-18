<?php

declare(strict_types=1);

namespace App\Controller\Api\Project;

use App\Dto\Project\Api\ProjectListDto;
use App\Dto\Project\ProjectFiltersDto;
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
    public function __construct(
        #[Autowire(service: ProjectsPaginator::class)]
        private readonly PaginatorInterface $paginator,
    ) {
    }

    public function __invoke(#[MapQueryString] ProjectFiltersDto $filters, int $page = 1): JsonResponse
    {
        return $this->json(ProjectListDto::fromPagination($this->paginator->paginate($page, $filters), $filters));
    }
}
