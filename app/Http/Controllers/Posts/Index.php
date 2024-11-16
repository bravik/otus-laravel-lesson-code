<?php

namespace App\Http\Controllers\Posts;

use App\Services\Repositories\PostsRepositoryInterface;
use Illuminate\View\View;

class Index
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function __invoke(): View
    {
        $posts = $this->postsRepository->fetchAll();

        return view("posts.index", compact("posts"));
    }
}
