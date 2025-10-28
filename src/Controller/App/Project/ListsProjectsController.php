<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Dto\Project\ProjectListDto;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/projects/{page<\d>}', name: 'app_projects_list', methods: ['GET'])]
class ListsProjectsController extends AbstractController
{
    public const int RESULTS_LENGTH = 12;

    public function __invoke(ProjectRepository $projectRepository, int $page = 1): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('app/project/list_projects.html.twig', [
            'projects' => $this->getProjectsDtos($projectRepository->findPaginated($user, ($page - 1) * self::RESULTS_LENGTH, self::RESULTS_LENGTH)),
        ]);
    }

    /**
     * @param Paginator<Project> $projectsPaginated
     *
     * @return ProjectListDto[]
     */
    private function getProjectsDtos(Paginator $projectsPaginated): array
    {
        $projects = [];
        foreach ($projectsPaginated as $project) {
            $projects[] = ProjectListDto::fromEntity($project);
        }

        return $projects;
    }
}
