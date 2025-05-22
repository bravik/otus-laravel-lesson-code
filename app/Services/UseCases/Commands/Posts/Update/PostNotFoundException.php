<?php

declare(strict_types=1);

namespace App\Services\UseCases\Commands\Posts\Update;

class PostNotFoundException extends \RuntimeException
{

    public function __construct(
        string $message = 'Post not found',
        int $code = 404,
        \Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
