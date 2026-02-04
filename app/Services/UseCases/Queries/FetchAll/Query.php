<?php

declare(strict_types=1);

namespace App\Services\UseCases\Queries\FetchAll;

class Query
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 10,
    ) {
    }
}
