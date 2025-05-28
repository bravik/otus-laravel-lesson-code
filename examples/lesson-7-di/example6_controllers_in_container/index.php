<?php

declare(strict_types=1);


require __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/ActionGreetings.php';
require_once __DIR__ . '/ActionLastVisitor.php';
require_once __DIR__ . '/ActionNotFound.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Session.php';


use Lesson\Request;
use Lesson\Response;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;
use Lesson\ServiceNotFoundException;
use Lesson\Session;


class Container
{
    /*** @var array<string,mixed> */
    private array $definitions;

    /** @var array<string,mixed> */
    private array $resolved = [];

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
$container->set('controller.greetings', function (Container $container) {
    return new ActionGreetings(
        isPolite: $container->get('isPolite'),
        session: $container->get('session'),
    );
});
$container->set(ActionLastVisitor::class, function (Container $container) {
    return new ActionLastVisitor(
        isPolite: $container->get('isPolite'),
        session: $container->get('session'),
    );
});

$container->set(ActionNotFound::class, static fn() => new ActionNotFound());

$request = Request::createFromSuperGlobals();

// Request тоже в контейнер
$container->set(Request::class, $request);

// Router
// И даже роутер можно в контейнер
$controllerDefiniton = match ($request->getPath()) {
    '/greetings' => 'controller.greetings',
    '/last-visitor' => ActionLastVisitor::class,
    default => ActionNotFound::class,
};

$controller = $container->get($controllerDefiniton);
$response = $controller($request);
$response->send();
