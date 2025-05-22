<?php

declare(strict_types=1);

namespace App\Services\UseCases\Commands\Posts\Update;

class Command
{
    public function __construct(
        public int $id,
        public string $title,
        public string $text,
    ) {
    }
}
