<?php

namespace App\Http\Controllers\Posts;

use App\Services\PostsRepositoryInterface;
use Illuminate\Contracts\View\View;

class Index
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
    }

    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function __invoke(): View
    {
        $posts = $this->postsRepository->fetchAll();

        return view('posts.index', compact('posts'));
    }
}
