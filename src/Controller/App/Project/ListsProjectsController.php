<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Pagination;
use App\Dto\Project\ProjectFiltersDto;
use App\Dto\Project\ProjectListDto;
use App\Dto\Project\ProjectListItemDto;
use App\Entity\Project;
use App\Entity\User;
use App\Form\Project\FiltersType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/projects/{page<\d+>}', name: 'app_projects_list', methods: ['GET'])]
class ListsProjectsController extends AbstractController
{
    public const int RESULTS_PER_PAGE = 12;

    public function __invoke(Request $request, ProjectRepository $projectRepository, int $page = 1): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $filters = new ProjectFiltersDto();
        $filtersForm = $this->createForm(FiltersType::class, $filters, [
            'method' => 'GET',
        ]);

        $filtersForm->handleRequest($request);

        if (true === $this->shouldRedirectToFirstPage($filtersForm, $page)) {
            return $this->redirectToRoute('app_projects_list', [
                'filters' => $this->getCleanFiltersFromRequest($request),
                'page' => 1,
            ]);
        }

        $start = ($page - 1) * self::RESULTS_PER_PAGE;
        $projects = $projectRepository->findByUserPaginated($user, $filters, $start, self::RESULTS_PER_PAGE);
        $totalProjects = $projects->count();
        $totalPages = (int) ceil($totalProjects / self::RESULTS_PER_PAGE);

        if ($page > $totalPages && $totalPages > 0) {
            throw $this->createNotFoundException();
        }

        return $this->render('app/project/list_projects.html.twig', [
            'projects' => $this->getProjectsDtos($projects),
            'pagination' => $this->createPagination($start, $totalProjects, $page, $totalPages),
            'filters_form' => $filtersForm,
            'filters' => $this->getCleanFiltersFromRequest($request),
        ]);
    }

    /**
     * @param FormInterface<ProjectFiltersDto> $filtersForm
     */
    private function shouldRedirectToFirstPage(FormInterface $filtersForm, int $page): bool
    {
        /** @var SubmitButton $submitButton */
        $submitButton = $filtersForm->get('submit');

        return $filtersForm->isSubmitted() && $submitButton->isClicked() && $page > 1;
    }

    /**
     * @return array<mixed>
     */
    private function getCleanFiltersFromRequest(Request $request): array
    {
        $filters = $request->query->all('filters');
        unset($filters['submit']);

        return $filters;
    }

    private function createPagination(int $start, int $totalProjects, int $page, int $totalPages): Pagination
    {
        return new Pagination(
            $totalProjects > 0 ? $start + 1 : 0,
            min(self::RESULTS_PER_PAGE, max(0, $totalProjects - $start)),
            $totalProjects,
            $page,
            max(1, $totalPages)
        );
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
            $projects[] = ProjectListItemDto::fromEntity($project);
        }

        return $projects;
    }
}
