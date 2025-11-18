<?php

declare(strict_types=1);

namespace App\Dto\Api;

final readonly class ListMetaDto
{
    public function __construct(
        private int $currentPage,
        private int $perPage,
        private int $total,
        private int $totalPages,
    ) {
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}
