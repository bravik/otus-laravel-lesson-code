<?php

declare(strict_types=1);

namespace App\Services\Queries\FetchPostByAuthor;

use App\Infrastructure\Eloquent\Repositories\PostsRepository;

class Fetcher
{
    public function __construct(
        private PostsRepository $repository
    ) {
    }

    public function __invoke(Query $query): Result
    {
        $entities = $this->repository->fetchByAuthor();

        foreach ($entities as $entity) {
            $result[] = new Result(
                $entity->id,
                $entity->title,
                $entity->text,
                $entity->author
            );
        }

        return new Result();
    }
}
