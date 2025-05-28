<?php

declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/ActionGreetings.php';
require_once __DIR__ . '/ActionLastVisitor.php';
require_once __DIR__ . '/ActionNotFound.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Session.php';

use Lesson\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;
use Lesson\Session;
use Lesson\Config;

$config = new Config();
$config->set('session_lifetime', 3600);
$config->set('isPolite', $_ENV['IS_POLITE'] === 'true' ? true : false);
$config->set('session', new Session(cookieLifetime: 3000));

$request = Request::createFromSuperGlobals();

// Router
$controller = match ($request->getPath()) {
    '/greetings' =>  new ActionGreetings(
            isPolite: $config->get('isPolite'),
            sessionLifetime: $config->get('session_lifetime')
        ),
    '/last-visitor' => new ActionLastVisitor(
        isPolite: $config->get('isPolite'),
        sessionLifetime: $config->get('session_lifetime')
    ),
    default => new ActionNotFound(),
};

$response = $controller($request);
$response->send();
