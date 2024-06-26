# Разворачивание и настройка проекта

## Содержание

- Быстрый старт
- После установки
- Доступные команды make

## Быстрый старт

1. Настраиваем окружение.
    1. Настраиваем подключение к бд. Если локальная установка, необходимо отредактировать PG_USER, PG_PASSWORD, PG_DSN чтобы можно было накатывать
       миграции
       ```
       cp .env.examle .env
       ```
2. Прописываем локальный домен в hosts файл

```
127.0.0.1 course.loc
```

### Без докера

**С докером инструкция в следующем разделе!**

1. Выполняем основные команды необходимые для первичной настройки проекта.
    - Устанавливаем composer зависимости
       ```
       composer install
       ```
   - Прописываем юзера в бд и создаем бд
     ```
     sh ./docker/postgres/docker-entrypoint-initdb.d/init-database-and-role.sh
     ```

### С докером

1. Разворачиваем docker
   ```
   make docker-install
   ```

2. Так как вся работа Yii2 теперь связана с PHP контейнером, удобно будет добавить в систему алиас, для удобства работы с докером.
   ```
   alias dphp='docker exec -it course-php-container'
   ```

   Теперь вызов всех команд в Yii2 будет выглядеть так:
   ```
   dphp ./yii your_command
   ```
   Или через make:
   ```
   make dphp cmd="./yii your_command"
   ```

3. Устанавливаем composer зависимости
   ```
   dphp composer install
   ```

4. Прописываем юзера в бд и создаем бд
   ```
   docker exec -it course-postgres-container sh /docker-entrypoint-initdb.d/init-database-and-role.sh
   ```

## После установки

1. Накатываем миграции

```
./yii migrate
```

## Доступные Make команды

| Команда               | Описание                                                 |
|-----------------------|----------------------------------------------------------|
| `make docker-install` | Установка docker контейнеров из docker-compose.local.yml |
| `make up`             | Запуск контейнеров                                       |
| `make down`           | Остановка контейнеров                                    |
| `make restart`        | Перезапуск контейнеров                                   |
| `make dphp cmd="..."` | Запуск команды в контейнере с php (укажите команду)      |
