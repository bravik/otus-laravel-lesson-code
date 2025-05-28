<?php

declare(strict_types=1);


require __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/ActionGreetings.php';
require_once __DIR__ . '/ActionLastVisitor.php';
require_once __DIR__ . '/ActionNotFound.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Session.php';


use Illuminate\Container\Container;
use Lesson\Request;
use Lesson\Response;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;
use Lesson\ServiceNotFoundException;
use Lesson\Session;


$container = new Container();

// Configure services
$container->instance('session_lifetime', 3600);

$container->bind('isPolite', static fn(): bool => $_ENV['IS_POLITE'] === 'true' ? true : false);

$container->singleton('session', function (Container $container) {
    return new Session(cookieLifetime: $container->get('session_lifetime'));
});

//$container->set('database', new \PDO('mysql:host=localhost:3306;dbname=otus', 'username', 'password'));
$container->bind('controller.greetings', function (Container $container) {
    return new ActionGreetings(
        isPolite: $container->get('isPolite'),
        session: $container->get('session'),
    );
});
$container->bind(ActionLastVisitor::class, function (Container $container) {
    return new ActionLastVisitor(
        isPolite: $container->get('isPolite'),
        session: $container->get('session'),
    );
});

$container->bind(ActionNotFound::class, static fn() => new ActionNotFound());

$request = Request::createFromSuperGlobals();

// Router
// И даже роутер можно в контейнер
$controllerDefiniton = match ($request->getPath()) {
    '/greetings' => 'controller.greetings',
    '/last-visitor' => ActionLastVisitor::class,
    default => ActionNotFound::class,
};

$controller = $container->get($controllerDefiniton);
$response = $controller($request);
$response->send();
