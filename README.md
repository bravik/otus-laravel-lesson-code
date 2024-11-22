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


Midleware: 
https://www.youtube.com/watch?v=VKMQwMyH3mw&ab_channel=%D0%94%D0%BC%D0%B8%D1%82%D1%80%D0%B8%D0%B9%D0%95%D0%BB%D0%B8%D1%81%D0%B5%D0%B5%D0%B2
