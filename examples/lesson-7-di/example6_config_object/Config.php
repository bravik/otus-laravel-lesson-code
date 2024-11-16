<?php

declare(strict_types=1);

namespace Lesson;

use Lesson\Request;
use Lesson\Response;

class Config
{
    /**
     * @var array<string,mixed>
     */
    private array $params;

    public function get(string $key): mixed
    {
        if (!array_key_exists($key, $this->params)) {
            throw new \InvalidArgumentException("Param $key not found");
        }

        return $this->params[$key];
    }

    public function set(string $key, mixed $value): void
    {
        $this->params[$key] = $value;
    }
}
