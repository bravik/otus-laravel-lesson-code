<?php

declare(strict_types=1);

use Lesson\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;
use Lesson\ServiceNotFoundException;
use Lesson\Session;

require __DIR__ . '/../../vendor/autoload.php';

class Container
{
    /*** @var array<string,mixed> */
    private array $definitions;

    /** @var array<string,mixed> */
    private array $resolved;

    public function get(string $id): mixed
    {
        if (!isset($this->definitions[$id])) {
            throw new ServiceNotFoundException("Service $id is not defined.");
        }

        $definition = $this->definitions[$id];

        if ($definition instanceof \Closure) {
            $this->resolved[$id] = $definition($this);
            return $this->resolved[$id];
        }

        return $definition;
    }

    public function set(string $id, mixed $value): void
    {
        if ($this->resolved[$id]) {
            unset($this->resolved[$id]);
        }

        $this->definitions[$id] = $value;
    }

    public function has(string $id): bool
    {
        return isset($this->definitions[$id]);
    }
}

$container = new Container();
$container->set('session_lifetime', 3600);
$container->set('isPolite', $_ENV['IS_POLITE'] === 'true' ? true : false);
$container->set('session', function (Container $container) {
    return new Session($container->get('session_lifetime'));
});

//$container->set('database', new \PDO('mysql:host=localhost:3306;dbname=otus', 'username', 'password'));

$request = Request::createFromSuperGlobals();

// Router
$controller = match ($request->getPath()) {
    '/greetings' => new ActionGreetings(
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
