<?php

declare(strict_types=1);

namespace App\Services\UseCases\Queries\FetchPostsByAuthor;

class Query
{
    public function __construct(
        public int $authorId,
        public int $page = 1,
        public int $perPage = 10,
    ) {
    }
}
