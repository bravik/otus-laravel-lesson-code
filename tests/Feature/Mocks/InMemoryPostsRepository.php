<?php

declare(strict_types=1);

namespace Tests\Feature\Mocks;

use App\Models\Post;
use App\Services\Repositories\PostsRepositoryInterface;

class InMemoryPostsRepository implements PostsRepositoryInterface
{
    /**
     * @param Post[] $posts
     */
    public function __construct(
        public array $posts = []
    ) {
    }

    public function fetchByAuthor(int $authorId): array
    {
        // TODO: Implement fetchByAuthor() method.
        throw new \RuntimeException('Not implemented');
    }

    public function fetchAll(): array
    {
        return $this->posts;
    }

    public function find(int $id): ?Post
    {
        // TODO: Implement find() method.
        throw new \RuntimeException('Not implemented');
    }

    public function save(Post $post): void
    {
        // TODO: Implement save() method.
        throw new \RuntimeException('Not implemented');
    }

    public function add(Post $post): void
    {
        // TODO: Implement add() method.
        throw new \RuntimeException('Not implemented');
    }
}
