<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/ActionGreetings.php';
require_once __DIR__ . '/ActionLastVisitor.php';
require_once __DIR__ . '/ActionNotFound.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Session.php';

/**
 * php -S localhost:8000 index.php
 */
use Lesson\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;



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
