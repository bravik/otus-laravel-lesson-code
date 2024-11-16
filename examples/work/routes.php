<?php

declare(strict_types=1);

/** @var \Illuminate\Container\Container $container */

/** @var \Illuminate\Routing\Router $router */

use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;

$router = $container->get('router');

$router->get('/greetings/{name}', ActionGreetings::class);
$router->get('/last-visitor', ActionLastVisitor::class);
$router->fallback(ActionNotFound::class);
