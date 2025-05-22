<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;

class PostsService
{
    public function update(UpdatePostDTO $updatePostDTO): void
    {
        $post = Post::query()->find($updatePostDTO->id);

        if (!$post) {
            throw new PostNotFoundException();
        }

        $post->title = $updatePostDTO->title;
        $post->text = $updatePostDTO->text;
        $post->save();
    }
}
