# Инструкция по запуску тестов Laravel в Docker

## 1. Назначение

В проекте используются две отдельные базы данных MySQL:

- `nexora_db` — основная база для сайта, Nova, миграций и сидов;
- `nexora_test` — тестовая база только для PHPUnit / `php artisan test`.

Тесты не должны изменять данные в `nexora_db`.

## 2. Файлы конфигурации

| Файл | Назначение |
|------|------------|
| `docker-compose.yml` | Основной стек: `app`, `nginx`, `db` |
| `docker-compose.test.yml` | Дополнительный сервис `test` для запуска тестов |
| `nexora-app/.env` | Настройки dev-окружения, база `nexora_db` |
| `nexora-app/.env.testing` | Настройки тестового окружения, база `nexora_test` |
| `nexora-app/phpunit.xml` | Переменные окружения для PHPUnit |

Все команды выполняются из корня проекта:

```powershell
cd d:\D\Progrmmer\Project\Docker\Nexora
```

## 3. Как устроены базы данных

```text
MySQL (контейнер db)
├── nexora_db      ← сайт, Nova, php artisan migrate --seed
└── nexora_test    ← php artisan test, RefreshDatabase
```

Один контейнер MySQL, две логические базы.

## 4. Подготовка (один раз)

### 4.1 Запустить основной стек

```powershell
docker compose up -d
```

Используется `docker-compose.yml`. Поднимаются сервисы:

- `app` — Laravel / PHP-FPM;
- `nginx` — веб-сервер;
- `db` — MySQL 8.0.

### 4.2 Создать тестовую базу данных

```powershell
docker compose exec db mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS nexora_test;"
```

### 4.3 Проверить контейнеры

```powershell
docker compose ps
```

## 5. Запуск сайта (dev)

Для обычной работы используется только `docker-compose.yml` и файл `nexora-app/.env`.

```powershell
docker compose up -d
```

Миграции и сиды в основную базу:

```powershell
docker compose exec app php artisan migrate --seed
```

Сайт:

```text
http://nexora.loc/
```

Nova:

```text
http://nexora.loc/nova
```

## 6. Запуск тестов

Перед тестами основной стек должен быть запущен:

```powershell
docker compose up -d
```

### 6.1 Способ 1 — рекомендуемый (простой)

Запуск из уже работающего контейнера `app`:

```powershell
docker compose exec app php artisan test
```

Laravel использует:

- `APP_ENV=testing` из `phpunit.xml`;
- базу `nexora_test` из `phpunit.xml` и `.env.testing`.

Один тест или класс:

```powershell
docker compose exec app php artisan test --filter=UserModelTest
```

Только Unit-тесты:

```powershell
docker compose exec app php artisan test --testsuite=Unit
```

Только Feature-тесты:

```powershell
docker compose exec app php artisan test --testsuite=Feature
```

### 6.2 Способ 2 — через `docker-compose.test.yml`

Файл `docker-compose.test.yml` добавляет временный сервис `test` с профилем `test`.

Команда:

```powershell
docker compose -f docker-compose.yml -f docker-compose.test.yml --profile test run --rm test
```

Что происходит:

1. Используется основной `docker-compose.yml` (нужен сервис `db`);
2. Подключается `docker-compose.test.yml`;
3. Запускается одноразовый контейнер `test`;
4. Читается `nexora-app/.env.testing`;
5. Выполняется `php artisan test`;
6. Контейнер удаляется (`--rm`).

Важно: не запускайте `docker-compose.test.yml` отдельно без основного файла — сервис `test` зависит от `db` из основного compose.

## 7. Сравнение способов запуска тестов

| Способ | Команда | Когда использовать |
|--------|---------|-------------------|
| Простой | `docker compose exec app php artisan test` | Ежедневная разработка |
| Через test-сервис | `docker compose -f docker-compose.yml -f docker-compose.test.yml --profile test run --rm test` | CI, явное test-окружение |

## 8. Настройки тестового окружения

### `nexora-app/.env.testing`

```env
APP_ENV=testing
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nexora_test
DB_USERNAME=root
DB_PASSWORD=root
```

### `nexora-app/phpunit.xml`

Ключевые переменные:

```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_DATABASE" value="nexora_test"/>
<env name="DB_HOST" value="db"/>
```

`phpunit.xml` и `.env.testing` должны указывать одну и ту же тестовую базу: `nexora_test`.

## 9. Трейт RefreshDatabase

В тестах моделей и контроллеров используйте:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;
}
```

`RefreshDatabase` перед тестами выполняет миграции и очищает таблицы только в `nexora_test`.

## 10. Что можно и нельзя делать

### Можно

```powershell
docker compose exec app php artisan test
docker compose exec app php artisan test --filter=HomeControllerTest
```

### Нельзя путать с dev-командами

| Команда | База данных |
|---------|-------------|
| `php artisan migrate --seed` | `nexora_db` |
| `php artisan db:seed` | `nexora_db` |
| `php artisan test` | `nexora_test` |

Сиды для основного сайта:

```powershell
docker compose exec app php artisan db:seed
```

Сиды в тестовую базу вручную (обычно не требуется):

```powershell
docker compose exec -e APP_ENV=testing -e DB_DATABASE=nexora_test app php artisan migrate --seed
```

## 11. Проверка, что тесты не трогают `nexora_db`

Проверить количество записей в основной базе до и после тестов:

```powershell
docker compose exec db mysql -uroot -proot -e "SELECT COUNT(*) AS users FROM nexora_db.users;"
```

Запустить тесты:

```powershell
docker compose exec app php artisan test
```

Повторить проверку — число пользователей в `nexora_db` не должно меняться из-за тестов с `RefreshDatabase`.

## 12. Частые проблемы

### Тесты пишут в `nexora_db`

Проверить:

1. В `docker-compose.yml` у `app` нет `DB_DATABASE` в блоке `environment`;
2. В `phpunit.xml` указано `DB_DATABASE=nexora_test`;
3. В `.env.testing` указано `DB_DATABASE=nexora_test`.

Очистить кеш:

```powershell
docker compose exec app php artisan optimize:clear
```

### База `nexora_test` не существует

```powershell
docker compose exec db mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS nexora_test;"
```

### Сервис `test` не находит `db`

Сначала запустите основной стек:

```powershell
docker compose up -d
```

Затем команду с двумя compose-файлами.

### Ошибка подключения к MySQL в тестах

Проверить, что контейнер `db` в статусе `healthy`:

```powershell
docker compose ps
docker compose logs -f db
```

## 13. Краткая шпаргалка

```powershell
# Dev
docker compose up -d
docker compose exec app php artisan migrate --seed

# Тесты (простой способ)
docker compose exec app php artisan test

# Тесты (через docker-compose.test.yml)
docker compose -f docker-compose.yml -f docker-compose.test.yml --profile test run --rm test
```

## 14. Схема запуска

```text
docker-compose.yml
    ├── up -d          → сайт (nexora_db)
    └── exec app test  → тесты (nexora_test)

docker-compose.yml + docker-compose.test.yml
    └── --profile test run --rm test  → тесты (nexora_test)
```
