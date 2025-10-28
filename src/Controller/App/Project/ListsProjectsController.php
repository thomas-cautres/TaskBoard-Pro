<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Pagination;
use App\Dto\Project\ProjectListDto;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/projects/{page<\d+>}', name: 'app_projects_list', methods: ['GET'])]
class ListsProjectsController extends AbstractController
{
    public const int RESULTS_LENGTH = 12;

    public function __invoke(ProjectRepository $projectRepository, int $page = 1): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $start = ($page - 1) * self::RESULTS_LENGTH;
        $projects = $projectRepository->findByUserPaginated($user, $start, self::RESULTS_LENGTH);
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
