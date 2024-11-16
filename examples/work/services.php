<?php

declare(strict_types=1);

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;
use Lesson\Session;



$container->instance('session_lifetime', 3600);

$container->instance('isPolite', $_ENV['IS_POLITE'] === 'true' ? true : false);
$container->singleton('session', function (Container $container) {
    return new Session($container->get('session_lifetime'));
});

$container->singleton(Request::class, static fn() => Request::capture());
$container->alias(Request::class, 'request');

$container->bind(ActionGreetings::class, function (Container $container) {
    return new ActionGreetings(
        isPolite: $container->get('isPolite'),
        session: $container->get('session')
    );
});

$container->bind(ActionLastVisitor::class, function (Container $container) {
    return new ActionLastVisitor(
        isPolite: $container->get('isPolite'),
        session: $container->get('session')
    );
});

$container->bind(ActionNotFound::class, static fn() => new ActionNotFound());

$container->bind(
    \Illuminate\Routing\Contracts\CallableDispatcher::class,
    fn () => new \Illuminate\Routing\CallableDispatcher($container)
);
$container->bind(
    \Illuminate\Events\Dispatcher::class,
    static fn(Container $container) => new \Illuminate\Events\Dispatcher($container),
);

$container->singleton('router', static function(Container $container)  {
    return new \Illuminate\Routing\Router(
        $container->get(\Illuminate\Events\Dispatcher::class),
        $container
    );
});

