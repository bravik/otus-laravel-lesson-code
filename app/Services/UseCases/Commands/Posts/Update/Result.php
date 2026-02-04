<?php

declare(strict_types=1);

namespace App\Services\UseCases\Commands\Posts\Update;

final readonly class Result
{

    public function __construct(
        public int $id,

    )
    {
    }
}
