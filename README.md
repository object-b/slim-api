Директория logs/ должна быть доступна для записи

В запросах обязательно передавать заголовок `X-Authentication: апи ключ пользователя`

```
curl -sS https://getcomposer.org/installer | php

php composer.phar install

chmod 777 logs

cp .env.example .env

php vendor/bin/phinx migrate
```

Создание миграций

```
php vendor/bin/phinx create CreateObjectsTable
```
