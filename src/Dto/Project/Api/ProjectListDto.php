<?php

declare(strict_types=1);

namespace App\Dto\Project\Api;

use App\Dto\Api\ListMetaDto;
use App\Dto\Pagination;
use App\Dto\Project\ProjectFiltersDto;
use App\Entity\Project;
use Doctrine\ORM\Tools\Pagination\Paginator;

final readonly class ProjectListDto
{
    /**
     * @param ProjectListItemDto[] $data
     */
    public function __construct(
        private array $data,
        private ListMetaDto $meta,
        private ProjectFiltersDto $filters,
    ) {
    }

    public static function fromPagination(Pagination $pagination, ProjectFiltersDto $filters): self
    {
        return new self(
            self::getProjectsDtos($pagination->getObjects()),
            new ListMetaDto($pagination->getCurrentPage(), $pagination->getResultsPerPage(), $pagination->getTotalResults(), $pagination->getTotalPages()),
            $filters
        );
    }

    /**
     * @return ProjectListItemDto[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getMeta(): ListMetaDto
    {
        return $this->meta;
    }

    public function getFilters(): ProjectFiltersDto
    {
        return $this->filters;
    }

    /**
     * @param Paginator<Project>|Project[] $projectsPaginated
     *
     * @return ProjectListItemDto[]
     */
    private static function getProjectsDtos(Paginator|array $projectsPaginated): array
    {
        $projects = [];
        foreach ($projectsPaginated as $project) {
            $projects[] = ProjectListItemDto::fromEntity($project);
        }

        return $projects;
    }
}
