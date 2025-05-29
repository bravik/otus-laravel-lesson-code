<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Auth\AuthManager;
use App\Services\UseCases\Queries\FetchAll\Fetcher;
use App\Services\UseCases\Queries\FetchAll\Query;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class Index
{
    public function __construct(
        private Fetcher $postsFetcher,
    ) {
    }

    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function __invoke(AuthManager $auth): View|RedirectResponse
    {
//        if (!$auth->check()) {
//            return redirect()->route('login');
//        }

        $posts = $this->postsFetcher->fetch(
            new Query()
        );

        return view('posts.index', [
            'posts' => $posts->posts,
        ]);
    }
}
