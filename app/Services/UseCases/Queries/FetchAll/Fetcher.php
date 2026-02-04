<?php

declare(strict_types=1);

namespace App\Services\UseCases\Queries\FetchAll;

use App\Models\Post;
use App\Services\Repositories\PostsRepositoryInterface;
use App\Services\Repositories\UsersRepositoryInterface;

/**
 * Пагинация не реализована. Чисто для примера доп параметры в запросе
 */
class Fetcher
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function fetch(Query $query): Result
    {
        $posts = $this->postsRepository->fetchAll();
        $userIds = array_map(
            static fn(Post $post) => $post->author_id,
            $posts
        );

        $authors = array_column(
            $this->usersRepository->findByIds($userIds),
            null,
            'id'
        );

        return new Result(
            posts: array_map(
                static fn($post) => new PostDTO(
                    id: $post->id,
                    title: $post->title,
                    text: $post->text,
                    createdAt: $post->created_at,
                    updatedAt: $post->updated_at,
                    authorId: $authors[$post->author_id]->id,
                    authorName: $authors[$post->author_id]->name,
                ),
                $posts
            ),
            total: count($posts),
            page: $query->page,
            perPage: $query->perPage,
        );
    }
}
