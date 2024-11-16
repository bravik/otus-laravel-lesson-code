<?php

declare(strict_types=1);

namespace App\Services\Queries\FetchPostByAuthor;

final readonly class Query
{

    public function __construct(
        public int $authorId,
    )
    {
    }
}
