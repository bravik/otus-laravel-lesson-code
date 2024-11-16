<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Create
{
    /**
     * Show the form for creating a new resource.
     */
    public function form(): View
    {
        return view('posts.edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(
        Request $request,
        \Illuminate\Validation\Factory $validationFactory
    ): RedirectResponse|View {
        $validator = $validationFactory->make(
            $request->all(),
            [
                'title' => ['required', 'string', 'min:10', 'max:255'],
                'text' => ['required', 'string', 'min:10'],
            ]
        );

        if ($validator->fails()) {
            return view('posts.edit', ['errors' => $validator->errors()]);
        }


        $post = new Post();
        $post->text = $request->get('text');
        $post->title = $request->get('title');

        $post->author()->associate($request->user());

        $post->save();

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully');
    }
}
