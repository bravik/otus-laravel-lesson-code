<?php

declare(strict_types=1);

namespace App\Services\UseCases\Queries\FetchPostsByAuthor;

use App\Services\Repositories\PostsRepositoryInterface;

/**
 * Пагинация не реализована. Чисто для примера доп параметры в запросе
 */
class Fetcher
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
    }

    public function fetch(Query $query): Result
    {
        $posts = $this->postsRepository->fetchByAuthor($query->authorId);

        return new Result(
            posts: array_map(
                static fn($post) => new PostDTO(
                    id: $post->id,
                    title: $post->title,
                    text: $post->text,
                    createdAt: $post->created_at,
                    updatedAt: $post->updated_at,
                    authorId: $post->author->id,
                    authorName: $post->author->name,
                ),
                $posts
            ),
            total: count($posts),
            page: $query->page,
            perPage: $query->perPage,
        );
    }
}
