<?php

namespace App\Http\Controllers\Posts;

use App\Services\UseCases\Queries\FetchAll\Fetcher;
use App\Services\UseCases\Queries\FetchAll\Query;
use Illuminate\Contracts\View\View;

class Index
{
    public function __construct(
        private Fetcher $postsFetcher,
    ) {
    }

    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function __invoke(): View
    {
        $posts = $this->postsFetcher->fetch(
            new Query()
        );

        return view('posts.index', [
            'posts' => $posts->posts,
        ]);
    }
}
