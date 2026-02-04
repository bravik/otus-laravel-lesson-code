<?php

namespace App\Http\Controllers\Posts;

use App\Http\Requests\UpdatePostRequest;
use App\Services\Repositories\PostsRepositoryInterface;
use App\Services\UseCases\Commands\Posts\Update\Command;
use App\Services\UseCases\Commands\Posts\Update\Handler;
use App\Services\UseCases\Commands\Posts\Update\ModelNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Update
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
    }

    /**
     * - Здесь не передаем модель, а только примитивные данные, которые необходимы для шаблона
     * - Не используем compact()
     */
    public function edit(int $postId): View
    {
        $post = $this->postsRepository->find($postId);

        if (!$post) {
            throw new NotFoundHttpException();
        }

        return view('posts.edit', [
            'postId'    => $post->id    ,
            'title'     => $post->title,
            'text'      => $post->text,
            'authorId'  => $post->author_id,
        ]);
    }

    public function update(
        UpdatePostRequest $request,
        Handler $updatePostHandler,
        int $postId
    ): RedirectResponse {
        $requestData = $request->validated();

        try {
            $updatePostHandler->handle(
                new Command(
                    postId: $postId,
                    title: $requestData['title'],
                    text: $requestData['text'],
                )
            );
        } catch (ModelNotfoundException) {
            throw new NotFoundHttpException();
        }

        return redirect()->route('posts.show', ['post' => $postId]);
    }
}
