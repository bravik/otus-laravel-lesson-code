<?php

declare(strict_types=1);

namespace App\Services\UseCases\Commands\Posts\Update;

final readonly class AuthorDTO
{
    public function __construct(
        public string $email,
        public string $name,
    ) {
    }
}
