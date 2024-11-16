<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function Lesson\actionGreetings;
use function Lesson\actionLastVisitor;

require __DIR__ . '/../../vendor/autoload.php';

// POST https://example.com /greetings ?name=roman
// lastName=Naumenko




// Router
$container = new \Illuminate\Container\Container();
$container->bind(\Illuminate\Routing\Contracts\CallableDispatcher::class, fn () => new \Illuminate\Routing\CallableDispatcher($container));
$router = new \Illuminate\Routing\Router(
    new \Illuminate\Events\Dispatcher($container),
    $container
);

$router->post('/greetings/{name}', function (Request $request, string $name) {
    return actionGreetings($request, $name);
});

$router->get('/last-visitor', function (Request $request) {
    return actionLastVisitor($request);
});

$router->fallback(function () {
    return new Response('', 404);
});


$request = Request::capture();
$container->instance(Request::class, $request);

$response = $router->dispatch($request);
$response->send();


