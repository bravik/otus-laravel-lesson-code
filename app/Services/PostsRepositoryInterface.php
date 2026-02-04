<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Post;

interface PostsRepositoryInterface
{
    /**
     * @return Post[]
     */
    public function fetchAll(): array;

    public function find(int $id): ?Post;

    public function save(Post $post): void;

    public function add(Post $post): void;

    /**
     * @return Post[]
     */
    public function fetchByAuthor(int $authorId): array;
}
