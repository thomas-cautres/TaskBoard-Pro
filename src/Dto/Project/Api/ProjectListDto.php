<?php

declare(strict_types=1);

namespace App\Dto\Project\Api;

use App\Dto\Api\ListMetaDto;
use App\Dto\Pagination;
use App\Dto\Project\ProjectFiltersDto;
use App\Entity\Project;

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
        /** @var Project[] $projects */
        $projects = $pagination->getObjects();

        return new self(
            array_map(fn (Project $project) => ProjectListItemDto::fromEntity($project), $projects),
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
}
