Директория для доступа извне - public/

Директория logs/ должна быть доступна для записи

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