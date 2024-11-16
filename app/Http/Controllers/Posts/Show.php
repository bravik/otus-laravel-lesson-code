<?php

namespace App\Http\Controllers\Posts;

use App\Services\Repositories\PostsRepositoryInterface;
use Illuminate\View\Factory;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Show
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    /**
     * Display the specified resource.
     */
    public function __invoke(int $postId, Factory $viewFactory): View
    {
        $post = $this->postsRepository->find($postId);

        if ($post === null) {
            throw new NotFoundHttpException();
        }

        return $viewFactory->make('posts.show', compact('post'));
    }
}
