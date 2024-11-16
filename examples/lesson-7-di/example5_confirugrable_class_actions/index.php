<?php

declare(strict_types=1);

use Lesson\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;

require __DIR__ . '/../../vendor/autoload.php';

$params = [
    'session_lifetime' => 3600,
    'isPolite' => $_ENV['IS_POLITE'] === 'true' ? true : false,
];

$request = Request::createFromSuperGlobals();

// Router
$controller = match ($request->getPath()) {
    '/greetings' =>  new ActionGreetings(
            isPolite: $params['isPolite'],
            sessionLifetime: $params['session_lifetime']
        ),
    '/last-visitor' => new ActionLastVisitor(
            isPolite: $_ENV['IS_POLITE'] === 'true' ? true : false,
            sessionLifetime: 3600,
    ),
    default => new ActionNotFound(),
};

$response = $controller($request);
$response->send();
