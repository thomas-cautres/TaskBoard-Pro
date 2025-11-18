<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class Pagination
{
    /**
     * @param object[] $objects
     */
    public function __construct(
        private array $objects,
        private int $start,
        private int $resultsPerPage,
        private int $totalResults,
        private int $currentPage,
        private int $totalPages,
    ) {
    }

    /**
     * @return object[]
     */
    public function getObjects(): array
    {
        return $this->objects;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getResultsPerPage(): int
    {
        return $this->resultsPerPage;
    }

    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}
