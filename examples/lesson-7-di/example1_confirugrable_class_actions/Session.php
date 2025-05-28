<?php

declare(strict_types=1);
namespace Lesson;

class Session
{
    public function __construct(
        int $cookieLifetime = 3600
    ) {
        session_start([
            'cookie_lifetime' => $cookieLifetime,
        ]);
    }

    public function get(string $key, string $default): string
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }
}
