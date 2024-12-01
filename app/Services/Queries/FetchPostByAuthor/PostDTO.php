<?php

declare(strict_types=1);

namespace App\Services\Queries\FetchPostByAuthor;

use App\Models\Post;

final readonly class PostDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
        public AuthorDTO $author,
    ) {
    }
}
