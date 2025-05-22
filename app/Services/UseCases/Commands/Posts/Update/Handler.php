<?php

declare(strict_types=1);

namespace App\Services\UseCases\Commands\Posts\Update;

use App\Services\PostsRepositoryInterface;

class Handler
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
    }

    public function handle(Command $command): void
    {
        $post = $this->postsRepository->find($command->id);

        if (!$post) {
            throw new PostNotFoundException();
        }

        $post->title = $command->title;
        $post->text = $command->text;
        $post->save();
    }
}
