<?php

declare(strict_types=1);

namespace App\Controller\Api\Project;

use App\Dto\Api\ListMetaDto;
use App\Dto\Project\Api\ProjectListItemDto;
use App\Dto\Project\ProjectFiltersDto;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/projects/{page<\d+>}', name: 'api_projects_list', methods: ['GET'])]
class ListProjectsController extends AbstractController
{
    public const int RESULTS_PER_PAGE = 12;

    public function __construct(
        private readonly ProjectRepository $projectRepository,
    ) {
    }

    public function __invoke(#[MapQueryString] ProjectFiltersDto $filters, int $page = 1): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $start = ($page - 1) * self::RESULTS_PER_PAGE;
        $projects = $this->projectRepository->findByUserPaginated($user, $filters, $start, self::RESULTS_PER_PAGE);
        $totalProjects = $projects->count();
        $totalPages = (int) ceil($totalProjects / self::RESULTS_PER_PAGE);

        return $this->json([
            'data' => $this->getProjectsDtos($projects),
            'meta' => new ListMetaDto($page, self::RESULTS_PER_PAGE, $totalProjects, $totalPages),
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
