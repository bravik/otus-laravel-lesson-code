<?php

use Illuminate\Container\Container;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;

/** @var Container $container */
/** @var \Illuminate\Routing\Router $router */
$router = $container->get('router');

$router->get('/greetings/{name}', ActionGreetings::class);
$router->get('/last-visitor', ActionLastVisitor::class);
$router->fallback(ActionNotFound::class);

