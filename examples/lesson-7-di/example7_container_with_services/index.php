<?php

declare(strict_types=1);

use Lesson\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;

require __DIR__ . '/../../vendor/autoload.php';

class Container
{
    /**
     * @var array<string,mixed>
     */
    private array $definitions;

    public function get(string $id): mixed
    {
        if (!isset($this->definitions[$id])) {
            throw new \InvalidArgumentException("Service $id is not defined.");
        }

        return $this->definitions[$id];
    }

    public function set(string $id, mixed $value): void
    {
        $this->definitions[$id] = $value;
    }
}

$container = new Container();
$container->set('session_lifetime', 3600);
$container->set('isPolite', $_ENV['IS_POLITE'] === 'true' ? true : false);
$container->set('session', new Session(cookieLifetime: 3000));
//$container->set('database', new \PDO('mysql:host=localhost:3306;dbname=otus', 'username', 'password'));

$request = Request::createFromSuperGlobals();

// Router
$controller = match ($request->getPath()) {
    '/greetings' =>  new ActionGreetings(
            isPolite: $container->get('isPolite'),
            session: $container->get('session'),
        ),
    '/last-visitor' => new ActionLastVisitor(
        isPolite: $container->get('isPolite'),
        sessionLifetime: $container->get('session_lifetime'),
    ),
    default => new ActionNotFound(),
};

$response = $controller($request);
$response->send();
