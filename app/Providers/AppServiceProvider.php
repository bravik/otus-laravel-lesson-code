<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repositories to their interfaces
        $this->app->bind(
            \App\Services\PostsRepositoryInterface::class,
            \App\Repositories\PostsRepository::class,
        );
        $this->app->bind(
            \App\Services\UsersRepositoryInterface::class,
            \App\Repositories\UsersRepository::class,
        );
    }

    public function boot(): void
    {

    }
}
