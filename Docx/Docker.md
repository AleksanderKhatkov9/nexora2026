# Инструкция по запуску проекта через Docker

## 1. Требования

Перед запуском проекта должны быть установлены:

- Docker Desktop;
- Docker Compose;
- Git - опционально;
- доступ к терминалу PowerShell.

Проект запускается из корневой папки:

```powershell
d:\D\Progrmmer\Project\Docker\Nexora
```

## 2. Структура Docker-файлов

В проекте используются следующие файлы:

- `docker-compose.yml` - описание контейнеров проекта;
- `docker/Dockerfile` - PHP-FPM контейнер для Laravel;
- `docker/nginx/default.conf` - конфигурация Nginx;
- `nexora-app/.env` - переменные окружения Laravel.

В `docker-compose.yml` поднимаются сервисы:

- `app` - Laravel / PHP-FPM;
- `nginx` - веб-сервер;
- `db` - MySQL 8.0.

## 3. Настройка .env

Если файла `nexora-app/.env` нет, создать его из примера:

```powershell
Copy-Item nexora-app\.env.example nexora-app\.env
```

В файле `nexora-app/.env` должны быть указаны настройки подключения к базе данных:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nexora_db
DB_USERNAME=root
DB_PASSWORD=root
```

Важно: внутри Docker нельзя использовать `DB_HOST=127.0.0.1` для подключения Laravel к MySQL. Нужно использовать имя сервиса базы данных из `docker-compose.yml`: `db`.

## 4. Запуск контейнеров

Перейти в корень проекта:

```powershell
cd d:\D\Progrmmer\Project\Docker\Nexora
```

Собрать и запустить контейнеры:

```powershell
docker compose up -d --build
```

Проверить статус контейнеров:

```powershell
docker compose ps
```

После запуска сайт будет доступен по адресу:

```text
http://nexora.loc/
```

Если домен `nexora.loc` не настроен в файле `hosts`, сайт также можно открыть по адресу:

```text
http://localhost/
```

## 5. Установка зависимостей Laravel

В текущей конфигурации `composer install` запускается автоматически при старте контейнера `app`, если в проекте отсутствует `vendor/autoload.php`.

Если нужно выполнить установку вручную:

```powershell
docker compose exec app composer install
```

## 5.1. Сборка фронтенда (Vite + Vue)

В контейнере `app` установлены Node.js и npm. При первом запуске, если нет `public/build/manifest.json`, выполняется `npm install` и `npm run build` автоматически.

Пересобрать фронтенд вручную после изменений в `resources/js` или `resources/css`:

```powershell
docker compose exec app npm run build
```

Режим разработки с hot-reload (запускать в отдельном терминале):

```powershell
docker compose exec app npm run dev
```

Если сборка падает из-за пустого `node_modules`, переустановить зависимости:

```powershell
docker compose exec app sh -c "rm -rf node_modules && npm ci && npm run build"
```

Сборка на хосте (если установлен Node.js 20+):

```powershell
cd nexora-app
npm install
npm run build
```

## 6. Команды Laravel

Сгенерировать ключ приложения, если он отсутствует:

```powershell
docker compose exec app php artisan key:generate
```

Запустить миграции:

```powershell
docker compose exec app php artisan migrate
```

Очистить кеш Laravel:

```powershell
docker compose exec app php artisan optimize:clear
```

Открыть shell внутри контейнера Laravel:

```powershell
docker compose exec app sh
```

## 7. Подключение к контейнерам

Все команды выполняются из корневой папки проекта:

```powershell
cd d:\D\Progrmmer\Project\Docker\Nexora
```

Проверить список контейнеров:

```powershell
docker compose ps
```

Зайти в контейнер Laravel / PHP-FPM:

```powershell
docker compose exec app sh
```

После входа в контейнер `app` можно выполнять команды Laravel:

```sh
php artisan migrate
php artisan optimize:clear
composer install
```

Выйти из контейнера:

```sh
exit
```

Зайти в контейнер Nginx:

```powershell
docker compose exec nginx sh
```

Проверить конфигурацию Nginx внутри контейнера:

```powershell
docker compose exec nginx nginx -t
```

Перезагрузить Nginx внутри контейнера:

```powershell
docker compose exec nginx nginx -s reload
```

Зайти в контейнер MySQL:

```powershell
docker compose exec db sh
```

Подключиться к базе данных MySQL из контейнера `db`:

```powershell
docker compose exec db mysql -uroot -proot nexora_db
```

Подключиться к MySQL одной командой без входа в shell:

```powershell
docker compose exec db mysql -uroot -proot
```

Если контейнер не запускается или команда `exec` не работает, можно посмотреть все контейнеры:

```powershell
docker ps -a
```

## 8. Работа с базой данных

MySQL доступен:

- внутри Docker-сети по хосту `db` и порту `3306`;
- с компьютера разработчика по порту `8102`.

Данные для подключения с локального клиента базы данных:

```text
Host: 127.0.0.1
Port: 8102
Database: nexora_db
User: root
Password: root
```

Подключиться к MySQL из контейнера:

```powershell
docker compose exec db mysql -uroot -proot nexora_db
```

## 9. Просмотр логов

Посмотреть логи всех контейнеров:

```powershell
docker compose logs -f
```

Посмотреть логи Laravel/PHP:

```powershell
docker compose logs -f app
```

Посмотреть логи Nginx:

```powershell
docker compose logs -f nginx
```

Посмотреть логи MySQL:

```powershell
docker compose logs -f db
```

## 10. Остановка проекта

Остановить контейнеры:

```powershell
docker compose down
```

Остановить контейнеры и удалить volumes с данными:

```powershell
docker compose down -v
```

Команду `docker compose down -v` нужно использовать осторожно, так как она удаляет данные MySQL из volume `db_data`.

## 11. Пересборка контейнеров

Если изменился `Dockerfile` или системные зависимости:

```powershell
docker compose up -d --build
```

Если нужно пересоздать контейнеры:

```powershell
docker compose up -d --force-recreate
```

## 12. Частые проблемы

### Laravel не подключается к MySQL

Проверить в `nexora-app/.env`:

```env
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nexora_db
DB_USERNAME=root
DB_PASSWORD=root
```

Также проверить, что контейнер базы данных запущен:

```powershell
docker compose ps
```

### Сайт не открывается

Проверить, что контейнер `nginx` запущен:

```powershell
docker compose logs -f nginx
```

Проверить адрес:

```text
http://nexora.loc/
```

### Ошибка прав на storage или cache

Выполнить:

```powershell
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Нужно полностью пересоздать окружение

```powershell
docker compose down -v
docker compose up -d --build
docker compose exec app php artisan migrate
```

## 13. Основной сценарий запуска

Для первого запуска проекта обычно достаточно выполнить:

```powershell
cd d:\D\Progrmmer\Project\Docker\Nexora
docker compose up -d --build
docker compose exec app php artisan migrate
```

После этого открыть сайт:

```text
http://nexora.loc/
```

Подключению к контенеру app

```powershell
docker compose exec app sh
```



Подключение к контенеру app
```powershell
docker compose up -d
docker compose down
docker compose exec app php artisan db:seed

```


## 14. Пересборка проекта Vue 
```powershell
docker compose exec app npm run build
```

## 15. Очистка кеша 

```powershell
docker compose exec app php artisan optimize:clear
```