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
        $this->app->bind(
            \App\Services\PostsRepositoryInterface::class,
            \App\Repositories\PostsRepository::class,
        );
    }

    public function boot(): void
    {

    }
}
