<?php

namespace App\Providers;

use App\Http\Controllers\Notifications\Send;
use App\Infrastructure\Notifier\EmailNotifier;
use App\Infrastructure\Notifier\FilteredNotifier\FilteredNotifier;
use App\Infrastructure\Notifier\FilteredNotifier\Filters\BannedFilter;
use App\Infrastructure\Notifier\FilteredNotifier\Filters\SpamFilter;
use App\Infrastructure\Notifier\QueuedNotifier;
use App\Jobs\SendNotificationJob;
use App\Policies\PostPolicy;
use App\Policies\RolesPolicy;
use App\Services\NotifierInterface;
use Illuminate\Support\Facades\Gate;
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

        $this->app->bind(NotifierInterface::class, QueuedNotifier::class);

        // Contextual Binding
        $this->app->when(SendNotificationJob::class)
            ->needs(NotifierInterface::class)
            ->give(EmailNotifier::class);

        $this->app->when(SendNotificationJob::class)
            ->needs('$myStaticParam')
            ->give('value');


        $isTestingEnv = getenv('APP_ENV') === 'testing';

        if ($isTestingEnv) {
//            $this->app->bind(NotifierInterface::class, \App\Infrastructure\Notifier\FakeNotifier::class);
        }

        $this->app->when(Send::class)
            ->needs(NotifierInterface::class)
            ->give(FilteredNotifier::class);

        $this->app->when(FilteredNotifier::class)
            ->needs(NotifierInterface::class)
            ->giveTagged('filters');


        $this->app->tag([
            BannedFilter::class,
            SpamFilter::class,
        ], 'filters');


        // App
        //$this->app->tag(MyCustomFilter::class, 'filters');
    }

    public function boot(): void
    {
//        Gate::define('posts.update', function (User $user, int|string $postId) {
//            $postRepository = app()->get(PostsRepositoryInterface::class);
//
//            $post = $postRepository->find($postId);
//
//            return $post && ($user->id === $post->author_id);
//        });

//        Gate::policy(Post::class, \App\Policies\PostPolicy::class);
        Gate::define('posts.update', [PostPolicy::class, 'update']);

        // Регистрация нескольких Gate-проверок. Сами проверки в классе RolesPolicy
        Gate::define(RolesPolicy::ADMIN, [RolesPolicy::class, RolesPolicy::ADMIN]);
        Gate::define(RolesPolicy::EDITOR, [RolesPolicy::class, RolesPolicy::EDITOR]);
        Gate::define(RolesPolicy::USER, [RolesPolicy::class, RolesPolicy::USER]);
    }
}
