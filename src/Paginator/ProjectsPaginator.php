<?php

declare(strict_types=1);

namespace App\Paginator;

use App\Dto\Request\Project\ProjectFiltersRequest;
use App\Dto\View\Pagination;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Symfony\Bundle\SecurityBundle\Security;

readonly class ProjectsPaginator implements PaginatorInterface
{
    public const int RESULTS_PER_PAGE = 12;

    public function __construct(
        private ProjectRepository $projectRepository,
        private Security $security,
    ) {
    }

    public function paginate(int $page, mixed $filters): Pagination
    {
        if (!$filters instanceof ProjectFiltersRequest) {
            throw new \InvalidArgumentException(sprintf('Expected filters of type %s', ProjectFiltersRequest::class));
        }

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
