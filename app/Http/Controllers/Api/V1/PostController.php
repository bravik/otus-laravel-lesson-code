<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostsResource;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Services\Repositories\PostsRepositoryInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): PostsResource
    {
        return (new PostsResource(Post::all()))
            ->additional(['meta' => ['hello' => 'world']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PostsRepositoryInterface $postsRepository): PostResource
    {
        $data = $request->validate(
            [
                'title' => ['required', 'string', 'min:6'],
                'text' => ['required', 'string', 'min:6'],
            ],
            $request->all()
        );

        /** @var User $author */
        $author = auth()->user();

        if ($author->getRole() !== Role::EDITOR) {
            abort(403, 'You are not allowed to create posts');
        }

        $post = Post::create($data['title'], $data['text'], $author->id);
        $postsRepository->save($post);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     * // post/{id}
     */
    public function show(Post $post)
    {
        return (new PostResource($post))
            ->additional(['another' => ['hello' => 'world']])
        ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
