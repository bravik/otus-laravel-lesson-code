<?php

namespace App\Http\Controllers\Posts;

use App\Services\UseCases\Queries\FetchPostsByAuthor\Query;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class Index
{
    public function __construct(
        private \App\Services\UseCases\Queries\FetchPostsByAuthor\Fetcher $postsFetcher,
    ) {
    }

    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function __invoke(AuthManager $auth): View|RedirectResponse
    {
        if (!$auth->check()) {
            return redirect()->route('login');
        }

        $posts = $this->postsFetcher->fetch(
            new Query(auth()->id())
        );

        return view('posts.index', [
            'posts' => $posts->posts,
        ]);
    }
}
