<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class PostsResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Collection<array-key,Post> $model */
        $collection = $this->resource;

        $models = [];

        foreach ($collection as $model) {
            $models[] = $model;
        }


        return array_map(fn(Post $post) => new PostResource($post), $models);
    }
}
