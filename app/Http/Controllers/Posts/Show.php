<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\Contracts\View\Factory;

class Show
{
    /**
     * - Используется Route Model Binding
     * - Вместо глобальных функций для рендеринга используется сервис из DI-контейнера
     * - Модель прокидывается прямо в шаблон (не рекомендуется так делать)
     */
    public function __invoke(Post $post, Factory $viewFactory)
    {
        return $viewFactory->make('posts.show', compact('post'));
    }
}
