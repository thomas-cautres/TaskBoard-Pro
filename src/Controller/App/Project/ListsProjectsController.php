<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Pagination;
use App\Dto\Project\ProjectFiltersDto;
use App\Dto\Project\ProjectListDto;
use App\Entity\Project;
use App\Entity\User;
use App\Form\Project\FiltersType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/projects/{page<\d+>}', name: 'app_projects_list', methods: ['GET', 'POST'])]
class ListsProjectsController extends AbstractController
{
    public const int RESULTS_LENGTH = 12;

    public function __invoke(Request $request, ProjectRepository $projectRepository, int $page = 1): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $start = ($page - 1) * self::RESULTS_LENGTH;

        $filters = new ProjectFiltersDto();
        $filtersForm = $this->createForm(FiltersType::class, $filters, [
            'method' => 'GET',
        ]);

        $filtersForm->handleRequest($request);

        $projects = $projectRepository->findByUserPaginated($user, $filters, $start, self::RESULTS_LENGTH);
        $totalProjects = $projects->count();
        $totalPages = (int) ceil($totalProjects / self::RESULTS_LENGTH);

        if ($page > $totalPages && $totalPages > 0) {
            throw $this->createNotFoundException();
        }

        return $this->render('app/project/list_projects.html.twig', [
            'projects' => $this->getProjectsDtos($projects),
            'pagination' => new Pagination(
                $totalProjects > 0 ? $start + 1 : 0,
                min(self::RESULTS_LENGTH, max(0, $totalProjects - $start)),
                $totalProjects,
                $page,
                max(1, $totalPages)
            ),
            'filters_form' => $filtersForm,
        ]);
    }

    /**
     * @param Paginator<Project>|Project[] $projectsPaginated
     *
     * @return ProjectListDto[]
     */
    private function getProjectsDtos(Paginator|array $projectsPaginated): array
    {
        $projects = [];
        foreach ($projectsPaginated as $project) {
            $projects[] = ProjectListDto::fromEntity($project);
        }

        return $projects;
    }
}
