<?php

declare(strict_types=1);

namespace App\Services\Queries\FetchPostByAuthor;

final readonly class Result
{
    /**
     * @param PostDTO[] $posts
     */
    public function __construct(
        public array $posts,
    ) {
    }
}
