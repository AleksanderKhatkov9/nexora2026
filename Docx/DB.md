# База данных Nexora

Документация построена по миграциям в `database/migrations/` и связям Eloquent в `app/Models/`.

**СУБД:** MySQL 8.0 (Docker-сервис `db`)  
**Имя БД:** `nexora_db` (см. `nexora-app/.env`)

---

## Обзор доменов

| Домен | Таблицы | Назначение |
|-------|---------|------------|
| **Аутентификация и роли** | `users`, `user_roles`, `password_reset_tokens`, `personal_access_tokens` | Пользователи Nova/API, роли, 2FA, Sanctum |
| **Контент** | `pages`, `projects`, `tags`, `page_tags`, `project_tags`, `project_images` | Страницы сайта, портфолио, теги, галереи |
| **Заявки** | `orders` | Обратная связь / заказы (без FK на другие сущности) |
| **Инфраструктура Laravel** | `failed_jobs` | Очередь неуспешных задач |

---

## ER-диаграмма (основная предметная область)

```mermaid
erDiagram
    user_roles ||--o{ users : "user_role_id"
    users ||--o{ personal_access_tokens : "tokenable (morph)"

    pages ||--o{ page_tags : ""
    tags ||--o{ page_tags : ""
    pages }o--o{ tags : "M:N через page_tags"

    projects ||--o{ project_tags : ""
    tags ||--o{ project_tags : ""
    projects }o--o{ tags : "M:N через project_tags"

    projects ||--|{ project_images : "project_id"

    orders {
        bigint id PK
        string name
        string phone
        string email
        text message
        string status
    }
```

---

## ER-диаграмма (полная схема)

```mermaid
erDiagram
    user_roles {
        bigint id PK
        string title
        timestamp created_at
        timestamp updated_at
    }

    users {
        bigint id PK
        bigint user_role_id FK "nullable"
        string name
        string email UK
        timestamp email_verified_at
        string password
        text two_factor_secret
        text two_factor_recovery_codes
        timestamp two_factor_confirmed_at
        string remember_token
        timestamp created_at
        timestamp updated_at
    }

    pages {
        bigint id PK
        string title
        text description
        text content
        string image
        string link
        string slug
        boolean active
        string seo_title
        text seo_description
        text seo_keywords
        timestamp created_at
        timestamp updated_at
    }

    tags {
        bigint id PK
        string name UK
        string slug UK
        timestamp created_at
        timestamp updated_at
    }

    page_tags {
        bigint page_id PK_FK
        bigint tag_id PK_FK
        timestamp created_at
        timestamp updated_at
    }

    projects {
        bigint id PK
        string slug UK
        string title
        string type
        smallint year
        string initial
        text short_description
        text full_description
        string cover_image
        string site_url
        boolean active
        string seo_title
        text seo_description
        text seo_keywords
        timestamp created_at
        timestamp updated_at
    }

    project_tags {
        bigint project_id PK_FK
        bigint tag_id PK_FK
        timestamp created_at
        timestamp updated_at
    }

    project_images {
        bigint id PK
        bigint project_id FK
        string path
        string alt
        int sort_order
        timestamp created_at
        timestamp updated_at
    }

    orders {
        bigint id PK
        string name
        string phone
        string email
        text message
        string status
        timestamp created_at
        timestamp updated_at
    }

    personal_access_tokens {
        bigint id PK
        string tokenable_type
        bigint tokenable_id
        string name
        string token UK
        text abilities
        timestamp last_used_at
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }

    password_reset_tokens {
        string email PK
        string token
        timestamp created_at
    }

    failed_jobs {
        bigint id PK
        string uuid UK
        text connection
        text queue
        longtext payload
        longtext exception
        timestamp failed_at
    }

    user_roles ||--o{ users : "1:N"
    users ||--o{ personal_access_tokens : "polymorphic 1:N"

    pages ||--|{ page_tags : "1:N"
    tags ||--|{ page_tags : "1:N"
    pages }o--o{ tags : "M:N"

    projects ||--|{ project_tags : "1:N"
    tags ||--|{ project_tags : "1:N"
    projects }o--o{ tags : "M:N"

    projects ||--|{ project_images : "1:N"
```

---

## Связи (кардинальность)

| От | К | Тип | FK / pivot | ON DELETE |
|----|---|-----|------------|-----------|
| `user_roles` | `users` | 1:N | `users.user_role_id` → `user_roles.id` | `SET NULL` |
| `pages` | `tags` | M:N | `page_tags` (`page_id`, `tag_id`) | `CASCADE` (обе стороны pivot) |
| `projects` | `tags` | M:N | `project_tags` (`project_id`, `tag_id`) | `CASCADE` |
| `projects` | `project_images` | 1:N | `project_images.project_id` | `CASCADE` |
| `users` | `personal_access_tokens` | 1:N (polymorphic) | `tokenable_type`, `tokenable_id` | — (Laravel default) |
| `orders` | — | — | Изолированная таблица | — |

**Общий тег:** сущность `tags` используется и для страниц, и для проектов через разные pivot-таблицы.

---

## Таблицы (детально)

### `user_roles`

Роли пользователей (админка Nova).

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `id` | `bigint` | PK, auto increment |
| `title` | `string` | — |
| `created_at`, `updated_at` | `timestamp` | — |

**Модель:** `App\Models\UserRole` → `hasMany(User)`

---

### `users`

Пользователи системы (Fortify / Nova / Sanctum).

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `id` | `bigint` | PK |
| `name` | `string` | — |
| `email` | `string` | UNIQUE |
| `email_verified_at` | `timestamp` | nullable |
| `password` | `string` | — |
| `two_factor_secret` | `text` | nullable (Fortify) |
| `two_factor_recovery_codes` | `text` | nullable |
| `two_factor_confirmed_at` | `timestamp` | nullable |
| `user_role_id` | `bigint` | FK → `user_roles.id`, nullable |
| `remember_token` | `string` | nullable |
| `created_at`, `updated_at` | `timestamp` | — |

**Модель:** `App\Models\User` → `belongsTo(UserRole)`, `HasApiTokens`

---

### `pages`

CMS-страницы сайта.

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `id` | `bigint` | PK |
| `title` | `string` | nullable |
| `description` | `text` | nullable |
| `content` | `text` | nullable |
| `image` | `string` | nullable |
| `link` | `string` | nullable |
| `slug` | `string` | nullable (route key в модели) |
| `active` | `boolean` | default `false`, **index** |
| `seo_title` | `string` | nullable |
| `seo_description` | `text` | nullable |
| `seo_keywords` | `text` | nullable |
| `created_at`, `updated_at` | `timestamp` | — |

**Модель:** `App\Models\Page` → `belongsToMany(Tags)` через `page_tags`

---

### `tags`

Справочник тегов (категории портфолио / метки страниц).

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `id` | `bigint` | PK |
| `name` | `string` | UNIQUE |
| `slug` | `string` | UNIQUE |
| `created_at`, `updated_at` | `timestamp` | — |

**Модель:** `App\Models\Tags` → `projects()`, `pages()` (M:N)

**Сидер:** `TagsSeeder` (данные из `App\Support\PortfolioData::tags()`)

---

### `page_tags` (pivot)

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `page_id` | `bigint` | PK, FK → `pages.id` CASCADE |
| `tag_id` | `bigint` | PK, FK → `tags.id` CASCADE |
| `created_at`, `updated_at` | `timestamp` | — |

**Модель pivot:** `App\Models\PageTags`

---

### `projects`

Проекты портфолио.

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `id` | `bigint` | PK |
| `slug` | `string` | UNIQUE (добавлено миграцией `2026_05_26_100000`) |
| `title` | `string` | nullable |
| `type` | `string` | nullable (тип сайта: «Корпоративный сайт» и т.д.) |
| `year` | `unsignedSmallInteger` | nullable |
| `initial` | `string(8)` | nullable (буква на превью) |
| `short_description` | `text` | nullable |
| `full_description` | `text` | nullable |
| `cover_image` | `string` | nullable |
| `site_url` | `string` | nullable |
| `active` | `boolean` | default `false`, **index** |
| `seo_title` | `string` | nullable |
| `seo_description` | `text` | nullable |
| `seo_keywords` | `text` | nullable |
| `created_at`, `updated_at` | `timestamp` | — |

**Модель:** `App\Models\Project` → `tags()`, `images()`; route key: `slug`

**Сидер:** `ProjectSeeder` (`PortfolioData::projects()`)

---

### `project_tags` (pivot)

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `project_id` | `bigint` | PK, FK → `projects.id` CASCADE |
| `tag_id` | `bigint` | PK, FK → `tags.id` CASCADE |
| `created_at`, `updated_at` | `timestamp` | — |

**Модель pivot:** `App\Models\ProjectTag`

---

### `project_images`

Галерея скриншотов проекта.

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `id` | `bigint` | PK |
| `project_id` | `bigint` | FK → `projects.id` CASCADE |
| `path` | `string` | — |
| `alt` | `string` | nullable |
| `sort_order` | `unsignedInteger` | default `0` |
| `created_at`, `updated_at` | `timestamp` | — |

**Индекс:** `(project_id, sort_order)`

**Модель:** `App\Models\ProjectImage` → `belongsTo(Project)`

---

### `orders`

Заявки с формы обратной связи.

| Колонка | Тип | Ограничения |
|---------|-----|-------------|
| `id` | `bigint` | PK |
| `name` | `string` | nullable |
| `phone` | `string` | nullable |
| `email` | `string` | nullable |
| `message` | `text` | nullable |
| `status` | `string` | default `'new'` |
| `created_at`, `updated_at` | `timestamp` | — |

**Статусы (модель):** `new`, `in_progress`, `done`, `cancelled` — см. `Order::statuses()`

**Связей с другими таблицами нет.**

---

## Служебные таблицы Laravel

### `personal_access_tokens` (Sanctum)

| Колонка | Тип | Примечание |
|---------|-----|------------|
| `tokenable_type`, `tokenable_id` | morph | Обычно `App\Models\User` |
| `name`, `token`, `abilities` | — | API-токены |
| `last_used_at`, `expires_at` | `timestamp` | nullable |

### `password_reset_tokens`

| Колонка | Тип | Примечание |
|---------|-----|------------|
| `email` | `string` | PK (не FK на `users`) |
| `token` | `string` | — |
| `created_at` | `timestamp` | nullable |

### `failed_jobs`

Очередь: `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`.

---

## Порядок миграций

```
2014_10_12_000000_create_users_table
2014_10_12_100000_create_password_reset_tokens_table
2014_10_12_200000_add_two_factor_columns_to_users_table
2019_08_19_000000_create_failed_jobs_table
2019_12_14_000001_create_personal_access_tokens_table
2026_05_19_090016_create_user_roles_table
2026_05_19_101500_add_user_role_id_to_users_table
2026_05_25_082128_create_pages_table
2026_05_25_084350_create_tags_table
2026_05_25_084610_create_page_tags_table
2026_05_25_085047_create_projects_table
2026_05_25_085147_create_project_images_table
2026_05_25_085244_create_project_tags_table
2026_05_25_085533_create_orders_table
2026_05_26_100000_add_slug_to_projects_table
```

---

## Сидеры

```
DatabaseSeeder
├── UserSeeder
├── TagsSeeder      → tags
├── ProjectSeeder   → projects + project_tags
└── PageSeeder      → pages
```

---

## Диаграмма зависимостей (текст)

```
user_roles
    └── users ──► personal_access_tokens (morph)

tags ◄──► pages      (page_tags)
tags ◄──► projects   (project_tags)
projects ──► project_images

orders (standalone)

password_reset_tokens  (по email, без FK)
failed_jobs            (standalone)
```

---

## Замечания по схеме

1. **`tags` — общий справочник** для `pages` и `projects`; удаление тега каскадно убирает связи в pivot-таблицах.
2. **`projects.slug`** обязателен и уникален после миграции `2026_05_26`; сидер должен заполнять `slug` при создании записей.
3. **`pages.slug`** в миграции nullable и без UNIQUE — при необходимости стоит добавить уникальный индекс отдельной миграцией.
4. **`orders`** не привязаны к `users` или `projects` — только статус в строковом поле.
5. В модели `UserRole` в `$fillable` указано поле `permission`, но **в миграциях колонки `permission` нет** — расхождение модели и БД.

---

## Полезные команды

```bash
# из корня репозитория (Docker)
docker compose exec app php artisan migrate:status
docker compose exec app php artisan db:show
docker compose exec app php artisan db:table projects
```

---

*Сгенерировано по состоянию миграций проекта Nexora (май 2026).*
