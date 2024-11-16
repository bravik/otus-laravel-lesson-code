<?php

declare(strict_types=1);

namespace App\Services\Repositories;

use App\Models\Post;

/**
 * Интерфейс репозитория архитектурно принадлежит слою с вашей бизнес логикой.
 * Согласно принципу SOLID DIP - верхнеуровневый код не должен зависить от нижнеуровневого.
 * Он может зависеть от абстраций. Абстрация часть верхнеуровневого кода.
 * Нижнеуровневый код может зависить от абстраций из нижнеуровневого кода
 *
 * Поэтому:
 * - интерфейс репозитория - часть бизнес логики (папка Services)
 * - реализация репозитория - часть инфраструктуры (папка Infrastructure)
 */
interface PostsRepositoryInterface
{
    public function fetchAll(): array;

    public function find(int $id): ?Post;

    public function save(Post $post): void;

    public function add(Post $post): void;
}
