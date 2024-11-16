<?php

declare(strict_types=1);

use example4_work_final\Request;
use example4_work_final\Response;
use function Lesson\actionGreetings;
use function Lesson\actionLastVisitor;

require __DIR__ . '/../../vendor/autoload.php';

$request = Request::createFromSuperGlobals();

// Router
$controller = match ($request->getPath()) {
    '/greetings' => static fn (Request $request) => actionGreetings($request),
    '/last-visitor' => static fn (Request $request) => actionLastVisitor(),
    default => static fn () => new Response('', 404),
};

$response = $controller($request);
$response->send();
