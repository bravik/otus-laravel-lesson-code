<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;

class Delete
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(Post $post)
    {
        throw new \RuntimeException("Not implemented");
    }
}
