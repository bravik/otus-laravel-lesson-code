<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Posts;

require __DIR__.'/auth.php';

/**
 * Редирект
 */
Route::get('/', function () {
    return redirect('/posts');
});

/**
 * Маршрут без контроллера сразу возвращает статическую страницу
 */
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/send-notification', \App\Http\Controllers\Notifications\Send::class);

Route::prefix('posts')
    ->name('posts.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', Posts\Index::class)
            ->name('index');
        Route::get('/create', [Posts\Create::class, 'create'])
            ->name('create');
        Route::post('/', [Posts\Create::class, 'store'])
            ->name('store');
        Route::get('/{post}', Posts\Show::class)
            ->name('show');
        Route::get('/{postId}/edit', [Posts\Update::class, 'edit'])
            ->name('edit')
            ->can('posts.update', 'postId')
        ;
        Route::put('/{postId}', [Posts\Update::class, 'update'])
            ->name('update');
        Route::delete('/{post}', Posts\Delete::class)
            ->name('destroy');
    });

///**
// * Пример группировки маршрутов
// */
//Route::group([
//    'prefix' => 'posts',                                // Префикс для URL маршрутов
//    'middleware' => ['auth', 'canany:admin,editor'],    // На всю группу маршрутов можно навесить middlewares
//    'as' => 'posts.',                                   // Префикс для имен маршрутов
//], static function () {
//    // Полное имя будет: posts.index
//    // URL будет: /posts
//    Route::get('/', static fn () => 'Dummy response')
//        ->name('index');
//
//    // Аналогично в остальных
//    Route::get('create', static fn () => 'Dummy response')
//        ->name('create');
//    Route::post('/', static fn () => 'Dummy response')
//        ->name('store');
//    Route::get('{postId}', static fn () => 'Dummy response')
//        ->name('show');
//    Route::get('{post}/edit', static fn () => 'Dummy response')
//        ->name('edit');
//    Route::put('{postId}', static fn () => 'Dummy response')
//        ->name('update');
//    Route::delete('{post}', static fn () => 'Dummy response')
//        ->name('destroy');
//});

