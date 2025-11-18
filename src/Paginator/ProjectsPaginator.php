<?php

declare(strict_types=1);

namespace App\Paginator;

use App\Dto\Pagination;
use App\Dto\Project\ProjectFiltersDto;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectsPaginator
{
    public const int RESULTS_PER_PAGE = 12;

    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly Security $security,
    ) {
    }

    public function paginate(int $page, ProjectFiltersDto $filters): Pagination
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $start = ($page - 1) * self::RESULTS_PER_PAGE;
        $projects = $this->projectRepository->findByUserPaginated($user, $filters, $start, self::RESULTS_PER_PAGE);
        $totalProjects = $projects->count();
        $totalPages = (int) ceil($totalProjects / self::RESULTS_PER_PAGE);

        return new Pagination(
            (array) $projects->getIterator(),
            $totalProjects > 0 ? $start + 1 : 0,
            min(self::RESULTS_PER_PAGE, max(0, $totalProjects - $start)),
            $totalProjects,
            $page,
            max(1, $totalPages)
        );
    }
}
