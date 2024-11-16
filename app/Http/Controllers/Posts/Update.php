<?php

namespace App\Http\Controllers\Posts;

use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\Commands\Posts\UpdateCommand\Command;
use App\Services\Commands\Posts\UpdateCommand\Handler;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class Update
{
    public function form(Post $post): View
    {
        return view('posts.edit',
            [
                'postId' => $post->id,
                'title' => $post->title,
                'text' => $post->text,
                'authorId' => $post->author_id,
            ]
        );
    }

    public function update(
        UpdatePostRequest $request, // Валидация запроса с помощью Laravel Form Request
        Handler $useCase, // Сервис с бизнес логикой
        int $postId
    ): RedirectResponse {
        $data = $request->validated();

        $useCase(
            new Command(
                $postId,
                $data['title'],
                $data['text'],
            )
        );

        return redirect()->route('posts.show', ['postId' => $postId]);
    }
}
