<?php

declare(strict_types=1);

namespace App\Services\Queries\FetchPostByAuthor;

use App\Services\Repositories\PostsRepositoryInterface;

class Fetcher
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    public function __invoke(Query $query): Result
    {
        $entities = $this->postsRepository->fetchByAuthor($query->authorId);

        $posts = [];

        foreach ($entities as $entity) {
            $posts[] = new PostDTO(
                $entity->id,
                $entity->title,
                $entity->text,
                new AuthorDTO(
                    $entity->author->email,
                    $entity->author->name,
                )
            );
        }

        return new Result($posts);
    }
}
