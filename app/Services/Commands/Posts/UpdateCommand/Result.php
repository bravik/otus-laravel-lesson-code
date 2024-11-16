<?php

declare(strict_types=1);

namespace App\Services\Commands\Posts\UpdateCommand;

final readonly class Result
{

    public function __construct(
        public int $id,

    )
    {
    }
}
