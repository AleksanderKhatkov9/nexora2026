# Nexora App

Laravel + Vue приложение для публичного сайта Nexora, CMS/CRM в Laravel Nova и интеграции с внешними API.

## Стек

| Слой | Технологии |
|------|------------|
| Backend | PHP 8.2, Laravel 10, Laravel Nova 5, Sanctum |
| Frontend | Vue 3, Vue Router, Vite, Axios |
| Database | MySQL 8 |
| Infra | Docker Compose, Nginx, PHP-FPM, phpMyAdmin |

## Быстрый старт через Docker

Команды выполняются из корня репозитория `d:\Projects\nexora2026`.

```powershell
docker compose up -d --build
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

Сайт доступен по `http://localhost/` или `http://nexora.loc/`, если домен настроен в hosts.

## Frontend

```powershell
npm ci
npm run build
```

В Docker frontend собирается через `docker/entrypoint.sh`, если нет `public/build/manifest.json`.

## Тесты

Тестовая БД: `nexora_test`.

```powershell
docker compose up -d db
docker compose run --rm test
```

Локальный запуск с хоста требует установленный PHP и доступ к MySQL.

## Основные разделы

| Раздел | Файлы |
|--------|-------|
| Public SPA | `resources/js`, `resources/views/index.blade.php` |
| API | `routes/api.php`, `app/Http/Controllers/Backend` |
| CMS/CRM | `app/Nova` |
| Models/DB | `app/Models`, `database/migrations` |
| Integrations | `config/integrations.php`, `app/Services/Integrations`, `app/Services/YandexWebmaster` |

## Документация

Подробная документация находится в `../Docx/`:

| Файл | Назначение |
|------|------------|
| `info.md` | структура и архитектура |
| `DB.md` | база данных и связи |
| `Docker.md` | Docker-запуск и команды |
| `Test.md` | тестовая БД и запуск тестов |
| `FullProjectAnalysis.md` | полный анализ проекта и рисков |
