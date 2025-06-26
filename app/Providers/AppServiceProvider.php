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
use App\Services\Mailer\Mailer;
use App\Services\Mailer\MailerInterface;
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
        /**
         * Простой биндинг интерфейса к реализации
         * Можно передать коллбэк, можно название другого сервиса.
         * Коллбэк будет вызываться каждый раз при запросе сервиса.
         *
         * Здесь сервис PostsRepository::class Laravel уже зарегистрировал автоматически,
         * поэтому мы просто привязываем к нему интерфейс.
         */
        $this->app->bind(
            \App\Services\PostsRepositoryInterface::class,
            \App\Repositories\PostsRepository::class,
        );
        $this->app->bind(
            \App\Services\UsersRepositoryInterface::class,
            \App\Repositories\UsersRepository::class,
        );

        /**
         * Синглтон закеширует сервис.
         * Каждый раз когда вы будете запрашивать этот сервис, будет возвращаться один и тот же объект.
         */
        $this->app->singleton(NotifierInterface::class, QueuedNotifier::class);


        /**
         * Contextual Binding
         * Позволяет тонко настроить какому сервису какой класс подставлять.
         *
         * Например здесь мы хотим чтобы запланированная джоба, получала EmailNotifier,
         * который непосредственно отправит сообщение
         */
        $this->app->when(SendNotificationJob::class)
            ->needs(NotifierInterface::class)
            ->give(EmailNotifier::class)
        ;

        $this->app->when(SendNotificationJob::class)
            ->needs('$myStaticParam')
            ->give('value')
        ;


        $isTestingEnv = getenv('APP_ENV') === 'testing';

        if ($isTestingEnv) {
//            $this->app->bind(NotifierInterface::class, \App\Infrastructure\Notifier\FakeNotifier::class);
        }

        /**
         * В случае отправки через контроллер, принимающий внешние данные, которые мы не можем контролировать,
         * передаем ему нотифаер с дополнительной логикой фильтрации
         */
        $this->app->when(Send::class)
            ->needs(NotifierInterface::class)
            ->give(FilteredNotifier::class)
        ;

        /**
         * В FilteredNotifier передается вложенный сервис, который будет вызван после фильтрации.
         */
        $this->app->when(FilteredNotifier::class)
            ->needs(NotifierInterface::class)
            ->give(EmailNotifier::class)
        ;

        /**
         * В FilteredNotifier передается набор фильтров, которые будут применены к сообщению перед отправкой.
         * Контейнер соберет все сервисы помеченные тегом и передаст их в конструктор FilteredNotifier.
         *
         * За счет того, что мы используем механизм тегирования, мы можем добавлять новые фильтры без изменения кода.
         * Например если наш FilteredNotifier - часть внешней библиотеки, подключенной через composer.
         * Мы не сможем редактировать код библиотеки, но мы можем создать свои новые фильтры,
         * зарегистрировать их в контейнере, пометить тегом и они будут автоматически добавлены в FilteredNotifier.
         * Мы расширяем функционал библиотеки, не изменяя ее код.
         */
        $this->app->when(FilteredNotifier::class)
            ->needs(FilterInterface::class)
            ->giveTagged('filters')
        ;

        /**
         * Тегирование сервисов
         */
        $this->app->tag([
            BannedFilter::class,
            SpamFilter::class,
        ], 'filters');


        // App
        //$this->app->tag(MyCustomFilter::class, 'filters');

        $this->app->bind(MailerInterface::class, Mailer::class);
    }

    /**
     *
     * Здесь  можно дополнительно сконфигурировать сервисы уже после сборки контейнера.
     * Удобно потому что в методе register() мы не можем быть уверены, что все сервис провайдеры уже загрузились и зарегестрировали свои сервисы. Методы boot() вызываются уже после того, как все сервис провайдеры зарегистрировали свои сервисы.
     */
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
