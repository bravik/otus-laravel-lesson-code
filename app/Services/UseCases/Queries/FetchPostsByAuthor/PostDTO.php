<?php

declare(strict_types=1);

namespace App\Services\UseCases\Queries\FetchPostsByAuthor;

class PostDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt,
        public string $authorName,
    ) {
    }
}
