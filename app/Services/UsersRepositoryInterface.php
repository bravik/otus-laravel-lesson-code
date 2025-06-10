<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

interface UsersRepositoryInterface
{
    /**
     * @return User[]
     */
    public function fetchAll(): array;

    public function find(int $id): ?User;

    public function save(User $post): void;

    public function add(User $post): void;

    /**
     * @return User[]
     */
    public function fetchByAuthor(int $authorId): array;

    /**
     * @param int[] $ids
     * @return User[]
     */
    public function findByIds(array $ids): array;
}
