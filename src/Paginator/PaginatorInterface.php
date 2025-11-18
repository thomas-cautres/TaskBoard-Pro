<?php

declare(strict_types=1);

namespace App\Paginator;

use App\Dto\Pagination;

interface PaginatorInterface
{
    public function paginate(int $page, mixed $filters): Pagination;
}
