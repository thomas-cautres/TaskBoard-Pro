<?php

declare(strict_types=1);

namespace App\Dto\Response\Project;

use App\Dto\View\Pagination;
use App\Dto\Request\Project\ProjectFiltersRequest;
use App\Dto\Response\ListMetaResponse;
use App\Entity\Project;

final readonly class ProjectListResponse
{
    /**
     * @param ProjectResponse[] $data
     */
    public function __construct(
        private array $data,
        private ListMetaResponse $meta,
        private ProjectFiltersRequest $filters,
    ) {
    }

    public static function fromPagination(Pagination $pagination, ProjectFiltersRequest $filters): self
    {
        /** @var Project[] $projects */
        $projects = $pagination->getObjects();

        return new self(
            array_map(fn (Project $project) => ProjectResponse::fromEntity($project), $projects),
            new ListMetaResponse($pagination->getCurrentPage(), $pagination->getResultsPerPage(), $pagination->getTotalResults(), $pagination->getTotalPages()),
            $filters
        );
    }

    /**
     * @return ProjectResponse[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getMeta(): ListMetaResponse
    {
        return $this->meta;
    }

    public function getFilters(): ProjectFiltersRequest
    {
        return $this->filters;
    }
}
