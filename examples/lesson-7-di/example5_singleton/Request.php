<?php

declare(strict_types=1);

namespace Lesson;

class Request {
    public function __construct(
        private array $get,
        private array $post,
        private array $server,
    ) {
    }

    public static function createFromSuperGlobals(): static
    {
        // process get, post, server

        return new static($_GET, $_POST, $_SERVER);
    }

    public function getPath(): string
    {
        return parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getParameter(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    public function getBody(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    public function getLocale(): string
    {
        $locale = 'en';
        if (isset($this->server['HTTP_ACCEPT_LANGUAGE'])) {
            $locale = substr($this->server['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        return $locale;
    }
}
