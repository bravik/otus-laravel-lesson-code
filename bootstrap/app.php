<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
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
            ]
        );
        /**
         * Добавление перед/после предустаановленных глобальных миддлваров Laravel
         */
        $middleware->prepend([
//            \App\Http\Middleware\ForceLogin::class,
        ]);
        $middleware->append([
            \App\Http\Middleware\Copyrighter::class
        ]);

        // Полностью перепишет глобальные мидллвары
        $middleware->use([]);

        // Добавление в группу
        $middleware->prependToGroup('web', [
//            \App\Http\Middleware\IsBanned::class,
//            \App\Http\Middleware\Authenticated::class,
        ]) ;

        /**
         * Модификация миддлваров группы.
         * В частности еще один способ избавиться от CSRF для группы роутов (тут для всех роутов классического приложения)
         */
        $middleware->removeFromGroup('web', [
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class
        ]);

        // Создание собственной группы
        $middleware->group('custom-group', [
//            \App\Http\Middleware\ForceLogin::class,
//            \App\Http\Middleware\Authenticated::class,
        ]);


        /**
         * Задание порядка применения миддлваров.
         * Не работает для глобальных миддлваров!
         */
        $middleware->priority([
//            \App\Http\Middleware\Authenticated::class,
//            \App\Http\Middleware\IsBanned::class,
        ]);

        // Задание псевдонимов
        $middleware->alias([
            'canany' => \App\Http\Middleware\AuthorizeCanAnyMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
