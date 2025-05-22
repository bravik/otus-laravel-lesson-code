<?php

declare(strict_types=1);

namespace App\Services\UseCases\Queries\FetchPostsByAuthor;

class Result
{
    /**
     * @param PostDTO[] $posts
     */
    public function __construct(
        public array $posts,
        public int $total,
        public int $page,
        public int $perPage,
    ) {
    }
}
