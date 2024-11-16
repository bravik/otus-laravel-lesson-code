<?php

declare(strict_types=1);

namespace App\Services\Commands\Posts\UpdateCommand;

use App\Models\User;

final readonly class Command
{
    public function __construct(
        public int $postId,
        public string $title,
        public string $text
    ) {
    }
}
