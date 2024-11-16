<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use function Lesson\actionGreetings;
use function Lesson\actionLastVisitor;

// POST https://example.com/greetings?name=roman
// lastName=Naumenko

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

match ($path) {
    '/greetings' => actionGreetings(),
    '/last-visitor' => actionLastVisitor(),
    default => http_response_code(404),
};

