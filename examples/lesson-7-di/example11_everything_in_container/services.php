<?php

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;
use Lesson\Session;

/** @var Container $container */

$container->instance('session_lifetime', 3600);
$container->bind('isPolite', static fn(): bool => $_ENV['IS_POLITE'] === 'true' ? true : false);
$container->singleton('session', function (Container $container) {
    return new Session(cookieLifetime: $container->get('session_lifetime'));
});
//$container->set('database', new \PDO('mysql:host=localhost:3306;dbname=otus', 'username', 'password'));
$container->scoped(ActionGreetings::class, function (Container $container) {
    return new ActionGreetings(
        isPolite: $container->get('isPolite'),
        session: $container->get('session'),
    );
});
$container->scoped(ActionLastVisitor::class, function (Container $container) {
    return new ActionLastVisitor(
        isPolite: $container->get('isPolite'),
        session: $container->get('session'),
    );
});

$container->scoped(ActionNotFound::class, static fn() => new ActionNotFound());

// Request тоже в контейнер
$container->singleton(Request::class, static fn() => Request::capture());
$container->alias(Request::class, 'request');

// Router
// И даже роутер можно в контейнер
$container->bind(
    \Illuminate\Routing\Contracts\CallableDispatcher::class,
    fn () => new \Illuminate\Routing\CallableDispatcher($container)
);

$container->bind(
    \Illuminate\Events\Dispatcher::class,
    static fn(Container $container) => new \Illuminate\Events\Dispatcher($container)
);
$container->singleton('router', static fn(Container $container)  => new \Illuminate\Routing\Router(
    $container->get(\Illuminate\Events\Dispatcher::class),
    $container
));
