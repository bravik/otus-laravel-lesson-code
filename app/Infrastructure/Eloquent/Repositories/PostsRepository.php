<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\Post;
use App\Services\Repositories\PostsRepositoryInterface;

class PostsRepository implements PostsRepositoryInterface
{
    public function fetchAll(): array
    {
        return Post::all()->all();
    }

    public function find(int $id): ?Post
    {
        return Post::query()->find($id);
    }

    public function save(Post $post): void
    {
        $post->save();
    }

    public function add(Post $post): void
    {
        $post->save();
    }

}
