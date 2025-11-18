<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Project\ProjectFiltersDto;
use App\Dto\Project\ProjectListItemDto;
use App\Entity\Project;
use App\Form\Project\FiltersType;
use App\Paginator\ProjectsPaginator;
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

    public function __construct(
        private readonly ProjectsPaginator $paginator,
    ) {
    }

    public function __invoke(Request $request, int $page = 1): Response
    {
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

        $pagination = $this->paginator->paginate($page, $filters);

        if ($page > $pagination->getTotalPages() && $pagination->getTotalPages() > 0) {
            throw $this->createNotFoundException();
        }

        return $this->render('app/project/list_projects.html.twig', [
            'projects' => $this->getProjectsDtos($pagination->getObjects()),
            'pagination' => $pagination,
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
