<?php

namespace App\Http\Controllers\Posts;

use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class Update
{
    /**
     * - Здесь не передаем модель, а только примитивные данные, которые необходимы для шаблона
     * - Не используем compact()
     */
    public function edit(Post $post): View
    {
        return view('posts.edit', [
            'postId'    => $post->id,
            'title'     => $post->title,
            'text'      => $post->text,
            'authorId'  => $post->author_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $requestData = $request->validated();

        $post->title = $requestData['title'];
        $post->text = $requestData['text'];
        $post->save();

        return redirect()->route('posts.show', ['post' => $post]);
    }
}
