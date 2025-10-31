<?php

declare(strict_types=1);

namespace App\Dto;

class Pagination
{
    public function __construct(
        public int $start,
        public int $resultsPerPage,
        public int $totalResults,
        public int $currentPage,
        public int $totalPages,
    ) {
    }
}
