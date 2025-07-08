<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Post $model */
        $model = $this->resource;

        return [
            'id' => $model->id,
            'title' => $model->title,
            'text' => $model->text,
            'createdAt' => $model->created_at->format('Y-m-d'),
        ];
    }

    public function with(Request $request)
    {
        return [
            'meta' => [
                'hello' => 'world',
            ]
        ];
    }
}
