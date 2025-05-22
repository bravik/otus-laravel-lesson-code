<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController
{
    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function index(): View
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('posts.edit');
    }

    /**
     * - Используется валидатор напрямую в контроллере
     * - Используется DI для внедрения зависимостей
     * - Явно обрабатываются ошибки валидации
     */
    public function store(
        Request $request,
        \Illuminate\Contracts\Validation\Factory $validationFactory,
        \Illuminate\Contracts\Auth\Factory $auth
    ): RedirectResponse
    {
        $validator = $validationFactory->make(request()->all(), [
            'title' => ['required', 'min:10', 'max: 255'],
            'text' => ['required', 'min:10'],
        ]);

        try {
            $validator->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
            ;
        }

        $post = new Post();
        $post->title = $request->input('title');
        $post->text = $request->input('text');

        $post->author_id = $auth->guard()->id();

        $post->save();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Пост успешно создан')
        ;
    }

    /**
     * - Используется Route Model Binding
     * - Вместо глобальных функций для рендеринга используется сервис из DI-контейнера
     * - Модель прокидывается прямо в шаблон (не рекомендуется так делать)
     */
    public function show(Post $post, Factory $viewFactory)
    {
        return $viewFactory->make('posts.show', compact('post'));
    }

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
    public function update(UpdatePostRequest $request, Post $post)
    {
        $requestData = $request->validated();

        $post->title = $requestData['title'];
        $post->text = $requestData['text'];
        $post->save();

        return redirect()->route('posts.show', ['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
