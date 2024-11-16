<?php

declare(strict_types=1);

use Lesson\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;
use Lesson\Session;

require __DIR__ . '/../../vendor/autoload.php';

class Config
{
    /**
     * @var array<string,mixed>
     */
    private array $params;

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->params[$key] = $value;
    }
}

$config = new Container();
$config->set('session_lifetime', 3600);
$config->set('isPolite', $_ENV['IS_POLITE'] === 'true' ? true : false);
$config->set('session', new Session(cookieLifetime: 3000));

$request = Request::createFromSuperGlobals();

// Router
$controller = match ($request->getPath()) {
    '/greetings' =>  new ActionGreetings(
            isPolite: $config->get('isPolite'),
            sessionLifetime: $config->get('session_lifetime')
        ),
    '/last-visitor' => new ActionLastVisitor(
        isPolite: $config->get('isPolite'),
        sessionLifetime: $config->get('session_lifetime')
    ),
    default => new ActionNotFound(),
};

$response = $controller($request);
$response->send();
