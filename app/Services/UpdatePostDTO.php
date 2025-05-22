<?php

declare(strict_types=1);

namespace App\Services;

class UpdatePostDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
    ) {
    }
}
