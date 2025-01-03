<?php

declare(strict_types=1);

namespace App\Services\Commands\Posts\UpdateCommand;

final readonly class AuthorDTO
{
    public function __construct(
        public string $email,
        public string $name,
    ) {
    }
}
