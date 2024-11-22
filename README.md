## OTUS
Код учебного проекта к темам маршрутизация, контроллеры и DI.

Ищите комментарии в коде.
Смотрите `docker/docker-compose.yml` и `Makefile`, чтобы разобраться как упростить развертывание проекта для разработчика (1 командой все что нужно для старта проекта).

# Запуск
Убедитесь, что в вашем Linux дистрибутиве есть команда make, либо смотрите соответствующие команды в Makefile.

Разрешения оптимизированы для Windows WSL, на маке и линукс не тестировалось.

Первый запуск:
```shell
make init
```
- соберет контейнеры
- сгенериует ключ
- накатит миграции и сиды
- установит зависимости composer
- запустит контейнеры

Команду необходимо запускать только один раз.

## Работа с проектом

Последующие запуски:
```shell
# Поднимет контейнеры
make up
```

```shell
# Остановка контейнеров
make down
```

Работа с composer и npm дожна быть внутри контейнера:
```shell
# Вход в контейнер laravel
make cli

# Вход в контейнер node
make cli-node
```
