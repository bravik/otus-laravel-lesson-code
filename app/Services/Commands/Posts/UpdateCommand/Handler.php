<?php

declare(strict_types=1);

namespace App\Services\Commands\Posts\UpdateCommand;


use App\Services\NotifierInterface;
use App\Services\Repositories\PostsRepositoryInterface;

class Handler
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private NotifierInterface $notifier,
    ) {
    }

    public function __invoke(Command $command): Result
    {
        $post = $this->postsRepository->find($command->postId);

        if ($post === null) {
            throw new ModelNotfoundException('Post not found');
        }

        $post->title = $command->title;
        $post->text = $command->text;

        $this->postsRepository->save($post);

        $this->notifier->send('post updated', $post->author_id);

        return new Result($post->id);
    }
}
