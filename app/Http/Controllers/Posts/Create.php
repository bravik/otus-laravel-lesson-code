<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Create
{
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
        ValidationFactory $validationFactory,
        Factory $auth
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
}
