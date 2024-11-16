<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('notifications/send/{userId}', \App\Http\Controllers\Notifications\Send::class);

Route::get('/test', function (Request $request, \Illuminate\View\Factory $viewFactory) {
//    return response('Hello World', 200);
//    return new \Illuminate\Http\Response('Hello World', 200);
//    return new \Illuminate\Http\JsonResponse(['message' => 'Hello World'], 200, [
//        'headers' => ['X-Header' => 'Value'],
//    ]);
    return $viewFactory->make('test');
});

Route::group(['prefix' => 'posts'], function () {
    Route::get('/', \App\Http\Controllers\Posts\Index::class)
        ->name('posts.index');
    Route::get('create', [\App\Http\Controllers\Posts\Create::class, 'form'])
        ->name('posts.create');
    Route::post('/', [\App\Http\Controllers\Posts\Create::class, 'create'])
        ->name('posts.store');
    Route::get('{postId}', \App\Http\Controllers\Posts\Show::class)
        ->name('posts.show');
    Route::get('{post}/edit', [\App\Http\Controllers\Posts\Update::class, 'form'])
        ->name('posts.edit');
    Route::put('{postId}', [\App\Http\Controllers\Posts\Update::class, 'update'])
        ->name('posts.update');
    Route::delete('{post}', \App\Http\Controllers\Posts\Delete::class)
        ->name('posts.destroy');
});






//Route::group(['prefix' => 'posts'], function () {
//    Route::get('/', [\App\Http\Controllers\PostsController::class, 'index'])->name('posts.index');
//    Route::get('create', [\App\Http\Controllers\PostsController::class, 'create'])->name('posts.create');
//    Route::post('/', [\App\Http\Controllers\PostsController::class, 'store'])->name('posts.store');
//    Route::get('{post}', [\App\Http\Controllers\PostsController::class, 'show'])->name('posts.show');
//    Route::get('{post}/edit', [\App\Http\Controllers\PostsController::class, 'edit'])->name('posts.edit');
//    Route::put('{post}', [\App\Http\Controllers\PostsController::class, 'update'])->name('posts.update');
//    Route::delete('{post}', [\App\Http\Controllers\PostsController::class, 'destroy'])->name('posts.destroy');
//});
//
//Route::group([
//    'prefix' => '/catalog',
//    'as' => 'catalog.',
//    'middleware' => ['can:see_catalog', 'auth']
//], function () {
//    Route::get('view-item', function () {
//        echo route("catalog.create");
//        // add your logic for viewing an item
//    })->name('view-item');
//
//    Route::post('create', function () {
//        // add your logic for creating an item
//    })->name('create');
//
//    Route::post('update', function () {
//        // add your logic for creating an item
//    })->name('create');
//
//    Route::delete('delete', function () {
//        // add your logic for deleting an item
//    })->name('delete');
//});
//
