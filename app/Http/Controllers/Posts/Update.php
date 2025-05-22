<?php

namespace App\Http\Controllers\Posts;

use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostNotFoundException;
use App\Services\PostsService;
use App\Services\UpdatePostDTO;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Update
{
    /**
     * - Здесь не передаем модель, а только примитивные данные, которые необходимы для шаблона
     * - Не используем compact()
     */
    public function edit(int $postId): View
    {
        $post = Post::query()->find($postId);

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

    public function update(UpdatePostRequest $request, PostsService $postsService, int $postId): RedirectResponse
    {
        $requestData = $request->validated();

        try {
            $postsService->update(new UpdatePostDTO(
                id: $postId,
                title: $requestData['title'],
                text: $requestData['text'],
            ));
        } catch (PostNotFoundException) {
            throw new NotFoundHttpException();
        }

        return redirect()->route('posts.show', ['post' => $postId]);
    }
}
