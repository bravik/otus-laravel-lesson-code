<?php

namespace App\Http\Controllers\Posts;

use App\Services\UseCases\Queries\FetchPostsByAuthor\Fetcher;
use App\Services\UseCases\Queries\FetchPostsByAuthor\Query;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\View\View;

class Index
{
    public function __construct(
        private Fetcher $postsFetcher,
        private Factory $auth,
    ) {
    }

    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function __invoke(): View
    {
        $posts = $this->postsFetcher->fetch(
            new Query(
                $this->auth->guard()->id()
            )
        );

        return view('posts.index', [
            'posts' => $posts->posts,
        ]);
    }
}
