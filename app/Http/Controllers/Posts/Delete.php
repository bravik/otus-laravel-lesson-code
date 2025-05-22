<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class Delete
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Пост успешно удален');
    }
}
