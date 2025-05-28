Controllers
=================


## Контейнер с нуля

1. Смотрим пример микроприложения:
    - Есть простейший роунтинг
    - Внутри экшна конфигурация берется напрямую из $_ENV переменных
    - используется сессия через глобальный массив $_SESSION

2. Выносим сессию в отдельный сервис
```php
<?php

declare(strict_types=1);
namespace Lesson;

class Session
{
    public function __construct(
        int $cookieLifetime = 3600
    ) {
        session_start([
            'cookie_lifetime' => $cookieLifetime,
        ]);
    }

    public function get(string $key, string $default): string
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }
}

```
3. Выносим конфигурацию и зависимости в конструктор
- Обращаем внимание, что у нас жесткая зависимость на сервис Session
- Даем пще пример, например нам нужен доступ п БД и нам нужно объект PDO создать со всеми параметрами коннекта прямо в конструкторе

```php
<?php

declare(strict_types=1);

namespace Lesson;

class ActionGreetings
{
    private Session $session;

    public function __construct(
        private bool $isPolite,
        int $sessionLifetime,
    ) {
        $this->session = new Session(
            $sessionLifetime
        );
    }

    function __invoke(Request $request): Response
    {
        // Get data from request headers
        $locale = $request->getLocale();

        // Get data from request super-globals
        $name = $request->getParameter('name');
        $lastName = $request->getBody('lastName');

        if ($locale === 'ru') {
            $greetings = ($this->isPolite ? "Добрый день" : "Здорова") . ", $name $lastName!";
        } else {
            $greetings = ($this->isPolite ? "Hello" : "Yo") . ", $name $lastName!";
        }

        $this->session->set('last_visitor', $name . ' ' . $lastName);

        return new Response($greetings, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}

```
```php
<?php

declare(strict_types=1);

namespace Lesson;

class ActionLastVisitor {
    private Session $session;

    public function __construct(
        private bool $isPolite,
        int $sessionLifetime,
    ) {
        $this->session = new Session(
            $sessionLifetime
        );
    }

    public function __invoke(Request $request): Response
    {
        // Get data from session storage
        $lastVisitor = $this->session->get('last_visitor', 'Anonymous');

        // Get data from request headers
        $locale = $request->getLocale();

        // Get data from server environment variables

        if ($locale === 'ru') {
            $report = ($this->isPolite ? "Последний посетитель: " : "Был тут один тип! Этот: ") . "$lastVisitor!";
        } else {
            $report = ($this->isPolite ? "Last visitor: " : "Last guy showed up: ") . "$lastVisitor!";
        }

        return new Response(
            json_encode([
                'report' => $report,
            ]),
            200,
            [
                'Content-Type' => 'application/json; charset=utf-8',
            ]
        );
    }
}

```

4. Выносим параметры конфигурации в единый массив:
- теперь мы все храним в одном центральном месте
```php
$params = [
    'session_lifetime' => 3600,
    'isPolite' => $_ENV['IS_POLITE'] === 'true' ? true : false,
];

// ...

// Router
$controller = match ($request->getPath()) {
    '/greetings' =>  new ActionGreetings(
            isPolite: $params['isPolite'],
            sessionLifetime: $params['session_lifetime']
    ),
    '/last-visitor' => new ActionLastVisitor(
            isPolite: $_ENV['IS_POLITE'] === 'true' ? true : false,
            sessionLifetime: 3600,
    ),
    default => new ActionNotFound(),
};
```

5. Переделываем массив на отдельный класс конфигурации:
```php
<?php

declare(strict_types=1);

namespace Lesson;

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

```

```php
<?php

declare(strict_types=1);
require __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/ActionGreetings.php';
require_once __DIR__ . '/ActionLastVisitor.php';
require_once __DIR__ . '/ActionNotFound.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Config.php';

use Lesson\Request;
use Lesson\ActionGreetings;
use Lesson\ActionLastVisitor;
use Lesson\ActionNotFound;
use Lesson\Session;
use Lesson\Config;

$config = new Config();
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
```

6. Выносим создание объекта сессии наружу классов контроллеров и передаем его в конструктор контроллеров:
```php
<?php

declare(strict_types=1);

namespace Lesson;

use Lesson\Request;
use Lesson\Response;

class ActionGreetings
{
    public function __construct(
        private bool $isPolite,
        private Session $session,
    ) {
    }

    function __invoke(Request $request): Response
    {
        // Get data from request headers
        $locale = $request->getLocale();

        // Get data from request super-globals
        $name = $request->getParameter('name');
        $lastName = $request->getBody('lastName');

        if ($locale === 'ru') {
            $greetings = ($this->isPolite ? "Добрый день" : "Здорова") . ", $name $lastName!";
        } else {
            $greetings = ($this->isPolite ? "Hello" : "Yo") . ", $name $lastName!";
        }

        $this->session->set('last_visitor', $name . ' ' . $lastName);

        return new Response($greetings, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
```
```php
<?php

declare(strict_types=1);

namespace Lesson;

use Lesson\Request;
use Lesson\Response;

class ActionLastVisitor {
    public function __construct(
        private bool $isPolite,
        private Session $session
    ) {
    }

    public function __invoke(Request $request): Response
    {
        // Get data from session storage
        $lastVisitor = $this->session->get('last_visitor', 'Anonymous');

        // Get data from request headers
        $locale = $request->getLocale();

        // Get data from server environment variables

        if ($locale === 'ru') {
            $report = ($this->isPolite ? "Последний посетитель: " : "Был тут один тип! Этот: ") . "$lastVisitor!";
        } else {
            $report = ($this->isPolite ? "Last visitor: " : "Last guy showed up: ") . "$lastVisitor!";
        }

        return new Response(
            json_encode([
                'report' => $report,
            ]),
            200,
            [
                'Content-Type' => 'application/json; charset=utf-8',
            ]
        );
    }

}
```

```php

$session = new Session(cookieLifetime: 3000);

// Router
$controller = match ($request->getPath()) {
    '/greetings' =>  new ActionGreetings(
            isPolite: $config->get('isPolite'),
            session: $session,
        ),
    '/last-visitor' => new ActionLastVisitor(
        isPolite: $config->get('isPolite'),
        session: $session,
    ),
    default => new ActionNotFound(),
};

```

6. Сервис `Session` помещаем в контейнер:
- Засовываем сессию в конфиг
- Теперь это не конфиг уже, а контейнер зависимостей
- Переименовываем в контейнер
- `Config::$params` переименовываем в `Container::$definitions`

```php
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
```

6. Ленивое инстанциирование сервисов
- Проблема: все сервисы создаются сразу, даже если они не нужны.
- Пример: подключение к базе сразу
    ```php
    $container->set('database', new \PDO('mysql:host=localhost:3306;dbname=otus', 'username', 'password'));
    ```

- Решение: оборачиваем в замыкание
```php
$container->set('session', function () {
    return new Session(
        cookieLifetime: 3600
    ););
});
```

- Модифицируем метод `Container::get()` для поддержки замыканий:
    ```php
    public function get(string $id): mixed
    {
        if (!isset($this->definitions[$id])) {
            throw new ServiceNotFoundException("Service $id is not defined.");
        }

        $definition = $this->definitions[$id];

        if ($definition instanceof \Closure) {
            return $definition($this);
        }

        return $definition;
    }
    ```

- и дополнительно исключение:
    ```php
    <?php

    declare(strict_types=1);

    namespace Lesson;

    use Exception;

    class ServiceNotFoundException extends Exception
    {

    }
    ```

7. Сервисы-синглтоны
- Проблема: каждый раз при вызове `get()` создается новый экземпляр сервиса.
- Решение: кэшируем.
  - Добавляем ;свойство `$resolved` для хранения уже созданных сервисов.
  - Обновляем метод `set()` для сброса кэша при изменении сервиса.

- Демонстрируем через dd(), что сервисы создаются только один раз.
```php

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
}
```

8. Засунем контроллеры в контейнер
- первый будет иметь строковый идентификатор
- второй будет иметь класс в качестве идентификатора
- нам нужнен доступ к контейнеру внутри коллбэков

```php
$container->set('controller.greetings', function () {
    return new ActionGreetings(
        isPolite: getenv('IS_POLITE') === 'true' ? true : false,
        session: new Session(cookieLifetype: 3600)
    );
});
$container->set(ActionLastVisitor::class, function () {
    return new ActionLastVisitor(
        isPolite: getenv('IS_POLITE') === 'true' ? true : false,
        session: new Session(cookieLifetype: 3600)
    );
});
```



9. Передадим контейнер аргументом в коллбэк сервиса
- убирается дублирование параметров
- переиспользуется один и тот же сервис сессии

```php
$container->set('controller.greetings', function (Container $container) {
    return new ActionGreetings(
        isPolite: $container->get('isPolite'),
        session: $container->get('session')
    );
});
$container->set(ActionLastVisitor::class, function (Container $container) {
    return new ActionLastVisitor(
        isPolite: $container->get('isPolite'),
        session: $container->get('session')
    );
});
$container->set(ActionNotFound::class, static fn() => new ActionNotFound());
```

10. Переделываем роутер, чтобы он работал с контейнером
```php
$controllerDefiniton = match ($request->getPath()) {
    '/greetings' => 'controller.greetings',
    '/last-visitor' => ActionLastVisitor::class,
    default => ActionNotFound::class,
};

$controller = $container->get($controllerDefiniton);
$response = $controller($request);
$response->send();

```

11. PSR-11 совместимый контейнер
- Объясняем про взаимозаменяемость и стандарт https://www.php-fig.org/psr/psr-11/
- Реализуем интерфейс `Psr\Container\ContainerInterface`
```php
class Container implements \Psr\Container\ContainerInterface {
    // ...

    public function has(string $id): bool
    {
        return isset($this->definitions[$id]);
    }
}
```

- Добавляем интерфейс для исключения:
```php
class ServiceNotFoundException extends Exception implements NotFoundExceptionInterface
```

12. Переходим на контейнер Laravel
- Убираем свой контейнер
- Создаем:
```php
$container = new \Illuminate\Container\Container();
```
- Заглядываем во внутренности контейнера Laravel

13. Показываем методы для регистрации сервисов Laravel Container
PSR-11 не задает методы для регистрации сервисов, поэтому в Laravel есть свои методы:, поэтому все делают что хотят
```php

$container->instance('session_lifetime', 3600);

$container->bind('isPolite', static fn(): bool => $_ENV['IS_POLITE'] === 'true' ? true : false);

$container->singleton('session', function (Container $container) {
    return new Session(cookieLifetime: $container->get('session_lifetime'));
});

```

14. Псевдонимы в Laravel
- Помещаем Request в контейнер
```php
$container->instance(Request::class, Request::createFromSuperGlobals());
$container->alias(Request::class, 'request');
```

15. Роутер в контейнер
- Меняем наш роутер на Ларавел:
```php
// Router
// И даже роутер можно в контейнер
$container->bind(
    \Illuminate\Routing\Contracts\CallableDispatcher::class,
    fn () => new \Illuminate\Routing\CallableDispatcher($container)
);

$container->bind(
    \Illuminate\Events\Dispatcher::class,
    static fn(Container $container) => new \Illuminate\Events\Dispatcher($container)
);
$container->singleton('router', static fn(Container $container)  => new \Illuminate\Routing\Router(
    $container->get(\Illuminate\Events\Dispatcher::class),
    $container
));
```


- Переделываем роуты (паттерн тоже меняется):
```php
/** @var \Illuminate\Routing\Router $router */
$router = $container->get('router');

$router->get('/greetings/{name}', ActionGreetings::class);
$router->get('/last-visitor', ActionLastVisitor::class);
$router->fallback(ActionNotFound::class);
```

- Организуем все по файлам `routes.php` и `services.php`
- Переделываем отправку запроса
```php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../../vendor/autoload.php';

$container = new Container();

require __DIR__ . '/services.php';
require __DIR__ . '/routes.php';

$request = $container->get('request');
$router = $container->get('router');

$response = $router->dispatch($request);
$response->send();

```

16. В итоге у нас есть:
- свой микрофреймворк с ларавелевским контейнером и роутом
- Dependency Inversion - фреймворк на несколько строк все делает и конфигурирует
- Передает управление в точки подключения - наши контроллеры с бизнс логикой


### Работаем с Laravel

1. Как регистрируются сервисы в ларавел
- Показываем AppServiceProvider
- Показываем как подключается сервис провайдер
- Объясняем метод register vs boot
- Показываем что Application является контейнером


2. Dependency Inversion Princeple
- Высочайший уровень абстрации - это ваша бизнс логика
- Низший уровень абстракции - это инфраструктурные зависимости

Для примера сервис, который зависит от конкретного репозитория. Зависимости направлены не правильно.. бизнес логика зависит от конкретной реализации репозитория, а не наоборот.
Пользуясь принципом инверсии зависимостей, мы можем сделать так, что бизнес логика будет зависеть от абстракции репозитория, а не от конкретной реализации. Это позволяет нам легко менять реализацию репозитория без изменения бизнес логики.

#### Меняется парадигма разработки
- вы вначале фокусируетесь на бизнес логике,
- а потом уже реализуете инфраструктурыне зависимости

3. Добавим нотификашки, начиная с интерфейса и бизнес-логики
Для примера работы в таком стиле добавим в контроллер `Update` отправку нотификашек
```php
class Update
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private NotifierInterface $notifier,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        // ... логика обновления поста

        // Отправляем нотификашку
        $this->notifier->send('Пост обновлен!', $post->author_id);

        return new Response('Пост обновлен!', 200);
    }
```

- Создаем интерфейс на уровне `Services`
```php
interface NotifierInterface
{
    public function send(string $text, int $recipientId): void;
}

```
- Создаем имплементацию в папке `Infrastructure\Notifier
- Ну и реализуем с помощью AI простейший `EmailNotifier`:`
    ```php
    Implement a simple email service using laravel mailer that implements NotifierInterface.
    Use UsersRepository to fetch recipient.
    ```
- Регистрируем сервис в контейнере:
```php
$container->singleton(NotifierInterface::class, EmailNotifier::class);
```

4. Создаем асинхронный вариант отправки нотификашек
- Создаем джобу `./artisan make:job SendNotificationJob`

- Имплементация:
```php
<?php

namespace App\Jobs;

use App\Services\NotifierInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Все что передано в конструктор будет сериализовано и записано в БД.
     * Когда запустится обработка очереди, эти данные будут вытащены из БД, десериализованы и переданы в метод handle()
     */
    public function __construct(
        private string $text,
        private int $recipientId
    )
    {
    }

    /**
     * Execute the job.
     * В метод handle() можно инжектить зависимости из контейнера
     */
    public function handle(NotifierInterface $notifier): void
    {
        $notifier->send($this->text, $this->recipientId);
    }
}
```

- Создаем еще одну реализацию интерфейса `NotifierInterface` - `QueuedNotifier`
```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier;

use App\Jobs\SendNotificationJob;
use App\Services\NotifierInterface;
use Illuminate\Bus\Dispatcher;

/**
 * Смотри пояснения в
 * @see NotifierInterface
 *
 * Вместо отправки уведомления сразу, добавляет задачу в очередь
 */
class QueuedNotifier implements NotifierInterface
{

    public function __construct(
        private Dispatcher $dispatcher,
    ) {
    }

    public function send(string $text, int $recipientId): void
    {
        $this->dispatcher->dispatch(
            new SendNotificationJob(
                $text,
                $recipientId
            )
        );
    }
}

```


5. Contextual Binding: Тонкая настройка сервисов, раскладываем кому что попадает:
- В контроллерах и по умолчанию нам нужна отложенная отправка нотификашек:
```php
/**
 * Синглтон закеширует сервис.
 * Каждый раз когда вы будете запрашивать этот сервис, будет возвращаться один и тот же объект.
 */
$this->app->singleton(NotifierInterface::class, QueuedNotifier::class);
```

- В джобе нам нужна реально отправляющая реализация нотификатора:
```php

/**
 * Contextual Binding
 * Позволяет тонко настроить какому сервису какой класс подставлять.
 *
 * Например здесь мы хотим чтобы запланированная джоба, получала EmailNotifier,
 * который непосредственно отправит сообщение
 */
$this->app->when(SendNotificationJob::class)
    ->needs(NotifierInterface::class)
    ->give(EmailNotifier::class)
        ;
```

- Можно запустить, посмотреть очередь, мейлтрап.. и т.д.

6. Смотрим по презе что еще осталось.. Typed Variardics
Реализуем фильтрацию сообщений с различным конфигурируемым набором фильтров.

7. Добавим контроллер. Пусть у нас микросервис, который позволяет клиентам отправлять произвольные сообщения:

```php
<?php
// app/Http/Controllers/Notifications/Send.php

declare(strict_types=1);

namespace App\Http\Controllers\Notifications;

use App\Infrastructure\Notifier\FilteredNotifier\FilteredNotifier;use App\Services\NotifierInterface;use Illuminate\Http\Request;

/**
 * Контроллер для отправки произвольного сообщения
 * Так как входящим данным доверять нельзя,
 * передаем в него с помощью контейнера @see FilteredNotifier с дополнительной фильтрацией
 */
class Send
{
    public function __construct(
        private NotifierInterface $notifier
    ) {
    }

    public function __invoke(Request $request, int $recipientId)
    {
        $this->notifier->send($request->get('message'), $recipientId);

        return response('OK');
    }
}
```

8. Создаим еще одну имплементацию `NotifierInterface` - `FilteredNotifier`
```php
<?php
// app/Infrastructure/Notifier/FilteredNotifier/FilteredNotifier.php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier;

use App\Services\NotifierInterface;

/**
 * Смотри пояснения в
 * @see NotifierInterface
 */
class FilteredNotifier implements NotifierInterface
{

    /** @var FilterInterface[]  */
    private array $filters;

    public function __construct(
        /**
         * Декоратор: принимает в конструктор объект с тем же интерфейсом, который сам реализует.
         *  как бы оборачивает его в себя, добавляя дополнительную логику и вызывая после этого вложенный сервис
         *
         * **Variadic Parameter**: The `...` syntax indicates that this parameter can accept multiple arguments, which will be collected into an array.
         */
        private NotifierInterface $notifier,
        FilterInterface ...$filters
    ) {
        $this->filters = $filters;
    }

    public function send(string $text, int $recipientId): void
    {
        // Дополнительная логика фильтрации
        foreach ($this->filters as $filter) {
            if (!$filter->filter($text, $recipientId)) {
                return;
            }
        }


        // Вызов вложенного объекта
        $this->notifier->send($text, $recipientId);
    }
}

```

9. Реализуем сами фильтры
```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier;

interface FilterInterface
{
    public function filter(string $text, int $recipientId): bool;
}
```

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier\Filters;

use App\Infrastructure\Notifier\FilteredNotifier\FilterInterface;

class BannedFilter implements FilterInterface
{

    public const BANNED = [3, 2];

    public function filter(string $text, int $recipientId): bool
    {
        if (in_array($recipientId, self::BANNED)) {
            return false;
        }

        return true;
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier\Filters;

use App\Infrastructure\Notifier\FilteredNotifier\FilterInterface;

class SpamFilter implements FilterInterface
{
    public function filter(string $text, int $recipientId): bool
    {
        $badWords = ['spam', 'bad', 'link', 'buy', 'check out'];

        foreach ($badWords as $badWord) {
            if (str_contains($text, $badWord)) {
                return false;
            }
        }

        return true;
    }
}
```

10. Регистрируем сервисы используя теги:
```php
 /**
         * В случае отправки через контроллер, принимающий внешние данные, которые мы не можем контролировать,
         * передаем ему нотифаер с дополнительной логикой фильтрации
         */
        $this->app->when(Send::class)
            ->needs(NotifierInterface::class)
            ->give(FilteredNotifier::class)
        ;

        /**
         * В FilteredNotifier передается вложенный сервис, который будет вызван после фильтрации.
         */
        $this->app->when(FilteredNotifier::class)
            ->needs(NotifierInterface::class)
            ->give(EmailNotifier::class)
        ;

        /**
         * В FilteredNotifier передается набор фильтров, которые будут применены к сообщению перед отправкой.
         * Контейнер соберет все сервисы помеченные тегом и передаст их в конструктор FilteredNotifier.
         *
         * За счет того, что мы используем механизм тегирования, мы можем добавлять новые фильтры без изменения кода.
         * Например если наш FilteredNotifier - часть внешней библиотеки, подключенной через composer.
         * Мы не сможем редактировать код библиотеки, но мы можем создать свои новые фильтры,
         * зарегистрировать их в контейнере, пометить тегом и они будут автоматически добавлены в FilteredNotifier.
         * Мы расширяем функционал библиотеки, не изменяя ее код.
         */
        $this->app->when(FilteredNotifier::class)
            ->needs(FilterInterface::class)
            ->give([
                \App\Infrastructure\Notifier\FilteredNotifier\Filters\BannedFilter::class,
                \App\Infrastructure\Notifier\FilteredNotifier\Filters\SpamFilter::class,
            ])
            ;
    }

```

10. Тегирование сервисов
- Предоставляет точку расширения функционала...
- Можно легко добавить вне вашего кода новую реализацию фильтра и просто пометить его тегом
- Особенно полезно для сторонних библиотек

```php
 /**
         * В случае отправки через контроллер, принимающий внешние данные, которые мы не можем контролировать,
         * передаем ему нотифаер с дополнительной логикой фильтрации
         */
        $this->app->when(Send::class)
            ->needs(NotifierInterface::class)
            ->give(FilteredNotifier::class)
        ;

        /**
         * В FilteredNotifier передается вложенный сервис, который будет вызван после фильтрации.
         */
        $this->app->when(FilteredNotifier::class)
            ->needs(NotifierInterface::class)
            ->give(EmailNotifier::class)
        ;

        /**
         * В FilteredNotifier передается набор фильтров, которые будут применены к сообщению перед отправкой.
         * Контейнер соберет все сервисы помеченные тегом и передаст их в конструктор FilteredNotifier.
         *
         * За счет того, что мы используем механизм тегирования, мы можем добавлять новые фильтры без изменения кода.
         * Например если наш FilteredNotifier - часть внешней библиотеки, подключенной через composer.
         * Мы не сможем редактировать код библиотеки, но мы можем создать свои новые фильтры,
         * зарегистрировать их в контейнере, пометить тегом и они будут автоматически добавлены в FilteredNotifier.
         * Мы расширяем функционал библиотеки, не изменяя ее код.
         */
        $this->app->when(FilteredNotifier::class)
            ->needs(FilterInterface::class)
            ->giveTagged('filters')
        ;

        /**
         * Тегирование сервисов
         */
        $this->app->tag([
            \App\Infrastructure\Notifier\FilteredNotifier\Filters\BannedFilter::class,
            \App\Infrastructure\Notifier\FilteredNotifier\Filters\SpamFilter::class,
        ], 'filters');
    }

```
