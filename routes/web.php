<?php

use Illuminate\Support\Facades\Route;

/**
 * Маршрут без контроллера сразу возвращает статическую страницу
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Пример группировки маршрутов
 */
Route::group([
    'prefix' => 'posts',                                // Префикс для URL маршрутов
    'middleware' => ['auth', 'canany:admin,editor'],    // На всю группу маршрутов можно навесить middlewares
    'as' => 'posts.',                                   // Префикс для имен маршрутов
], static function () {
    // Полное имя будет: posts.index
    // URL будет: /posts
    Route::get('/', static fn () => 'Dummy response')
        ->name('index');

    // Аналогично в остальных
    Route::get('create', static fn () => 'Dummy response')
        ->name('create');
    Route::post('/', static fn () => 'Dummy response')
        ->name('store');
    Route::get('{postId}', static fn () => 'Dummy response')
        ->name('show');
    Route::get('{post}/edit', static fn () => 'Dummy response')
        ->name('edit');
    Route::put('{postId}', static fn () => 'Dummy response')
        ->name('update');
    Route::delete('{post}', static fn () => 'Dummy response')
        ->name('destroy');
});
