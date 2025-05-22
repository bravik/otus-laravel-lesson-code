<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PostsRepository;

class PostsService
{
    public function __construct(
        private PostsRepository $postsRepository,
    ) {
    }

    public function update(UpdatePostDTO $updatePostDTO): void
    {
        $post = $this->postsRepository->find($updatePostDTO->id);

        if (!$post) {
            throw new PostNotFoundException();
        }

        $post->title = $updatePostDTO->title;
        $post->text = $updatePostDTO->text;
        $post->save();
    }
}
