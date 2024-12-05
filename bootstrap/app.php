<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
//        api:
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function() {
            Route::group([
                'prefix' => '/v1',
                'as' => 'v1.',
                'middleware' => ['can:see_catalog', 'auth']
            ], function () {
//                require __DIR__ . '/apiv1.php';
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(
            except: [
//                '/send-money',
                '/users/*',
//                '/posts*',
            ]
        );
        $middleware->prepend([
//             Авторизует первого пользователя в БД на сайте (чисто для урока)
            \App\Http\Middleware\ForceLogin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
