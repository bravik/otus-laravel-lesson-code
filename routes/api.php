<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


$v1Routes = function (): void {
    Route::apiResource('posts', \App\Http\Controllers\Api\V1\PostController::class);
};

Route::group([
    'prefix' => 'v1',
    'as' => 'api.v1.',
    'middleware' => ['auth:jwt']
], function() use ($v1Routes) {
   $v1Routes();
});

Route::group([
    'prefix' => 'v2',
    'as' => 'api.v2.',
    'middleware' => ['auth:api']
], function() use ($v1Routes) {
    $v1Routes();
    Route::apiResource('comments', \App\Http\Controllers\Api\V2\CommentsController::class);
});

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});
