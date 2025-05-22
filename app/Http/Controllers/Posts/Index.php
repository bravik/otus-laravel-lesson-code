<?php

namespace App\Http\Controllers\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class Index
{
    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function __invoke(): View
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }
}
