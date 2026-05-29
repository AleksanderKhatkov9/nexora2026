# Анализ архитектуры БД и Laravel-моделей

Дата анализа: 2026-05-29  
Путь проекта: `d:\Projects\nexora2026\nexora-app`  
Основа анализа: `database/migrations`, `app/Models`, `app/Repositories`, `app/Nova`, `tests`, `Docx/DB.md`.

Важно: фактическое состояние применённой MySQL-схемы не проверялось через подключение к БД. Ниже анализ схемы, которую задают миграции, и соответствия этой схемы Laravel-моделям.

---

## Итог

Архитектура БД и моделей в целом согласована для текущего приложения: основные связи заданы корректно, модели покрывают реальные таблицы, repository-запросы соответствуют существующим колонкам, FK есть для ролей пользователей, изображений проектов и pivot-таблицы проектов/тегов.

Главная проблема — слабые DB-инварианты. Многие поля, которые приложение считает обязательными или перечисляемыми, на уровне БД остаются nullable string/text без ограничений. Это не ломает текущий happy path через FormRequest/Nova, но позволяет записать некорректные данные через seeders, tinker, repository, импорт или будущий код.

Вторая подтверждённая проблема — `Docx/DB.md` устарел: он описывает `page_tags`, но эта таблица удаляется миграцией, и не описывает часть текущих таблиц/полей.

---

## Фактическая схема по миграциям

### Основные таблицы

| Таблица | Назначение | Ключевые ограничения |
|---------|------------|----------------------|
| `users` | пользователи Laravel/Nova/Sanctum | `email` unique |
| `user_roles` | роли пользователей | PK `id`; unique на `title` нет |
| `pages` | CMS-страницы, меню, footer, SEO | `slug` unique, `active` index |
| `projects` | портфолио | `slug` unique, `active` index |
| `project_images` | изображения проекта | FK `project_id` cascade, index `project_id, sort_order` |
| `tags` | теги проектов | `name` unique, `slug` unique |
| `project_tags` | связь projects/tags | composite PK `project_id, tag_id`, оба FK cascade |
| `orders` | заявки | PK `id`, `status` default `new` |
| `blog_posts` | новости и статьи | `slug` unique, index `kind, active`, index `published_at` |
| `api_integrations` | API-интеграции | `slug` unique, `driver` unique |
| `personal_access_tokens` | Sanctum tokens | Laravel default morphs + token unique |
| `password_reset_tokens` | password reset | `email` primary |
| `failed_jobs` | failed queue jobs | `uuid` unique |

### Удалённая таблица

Миграция `2026_05_27_120000_drop_page_tags_table.php` удаляет `page_tags`. В актуальной модели `Page` связи с `Tags` нет.

---

## Соответствие моделей таблицам

| Модель | Таблица | Соответствие |
|--------|---------|--------------|
| `User` | `users` | корректно: `fillable`, casts, `belongsTo(UserRole)`, `HasApiTokens` |
| `UserRole` | `user_roles` | корректно: `hasMany(User)` |
| `Page` | `pages` | в целом корректно: fillable/casts покрывают поля; нет DB-ограничений для menu/footer enum |
| `Project` | `projects` | корректно: fillable/casts, `belongsToMany(Tags)`, `hasMany(ProjectImage)` |
| `ProjectImage` | `project_images` | корректно: fillable/cast, `belongsTo(Project)` |
| `ProjectTag` | `project_tags` | соответствует pivot-таблице, но явно не используется через `using(ProjectTag::class)` в relation |
| `Tags` | `tags` | корректно: table задан явно, `belongsToMany(Project)` |
| `Order` | `orders` | соответствует таблице, но статусы/каналы не защищены DB-ограничениями |
| `BlogPost` | `blog_posts` | соответствует таблице, но `kind` не защищён DB-ограничением |
| `ApiIntegration` | `api_integrations` | корректно: `credentials` cast `encrypted:array`, `settings` cast `array`, `enabled` boolean |

---

## Сильные стороны

1. **Связи проектов и изображений заданы правильно.**  
   `project_images.project_id` имеет FK на `projects` с `cascadeOnDelete()`, модель `ProjectImage` имеет `belongsTo(Project)`, модель `Project` имеет `hasMany(ProjectImage)`.

2. **Связь проектов и тегов нормализована.**  
   `project_tags` имеет composite PK `project_id, tag_id`, оба FK настроены с cascade delete. В `Project` и `Tags` есть симметричные `belongsToMany()` с `withTimestamps()`.

3. **Slug для ключевых публичных сущностей уникален.**  
   `pages.slug`, `projects.slug`, `blog_posts.slug`, `tags.slug`, `api_integrations.slug`, `api_integrations.driver` имеют unique-ограничения.

4. **Casts в моделях соответствуют типам, которые использует приложение.**  
   `Page.content` cast `array`, `Page.active/show_in_menu/show_in_footer` boolean, `Project.active` boolean, `Project.year` integer, `BlogPost.published_at` datetime, `ApiIntegration.credentials` encrypted array.

5. **Repository-запросы используют существующие поля.**  
   Запросы в `PageRepository`, `ProjectRepository`, `BlogPostRepository`, `OrderRepository`, `TagRepository` соответствуют колонкам из миграций.

---

## Проблемы и риски

### 1. `pages.slug` nullable при использовании как route key

Факт по миграции: `pages.slug` создаётся как nullable string, затем на него добавляется unique index.

Факт по модели: `Page::getRouteKeyName()` возвращает `slug`; `PageRepository::getActiveBySlug()` ищет активную страницу по slug; Nova требует slug через rules.

Проблема: БД допускает записи `pages` без slug. В MySQL unique index не запрещает несколько `NULL` значений. Такие записи не являются валидными публичными страницами, но БД это не фиксирует.

Решение: сделать `pages.slug` not null, если страницы всегда должны быть адресуемыми или системно идентифицируемыми.

### 2. `orders` допускает пустые обязательные поля

Факт по миграции: `orders.name`, `orders.phone`, `orders.email`, `orders.message` nullable.

Факт по `StoreOrderRequest`: `name`, `phone`, `email` required.

Факт по Nova: `name` и `phone` required, `email` nullable.

Проблема: правила приложения и схема БД расходятся. Через HTTP-заявку пустые `name/phone/email` не проходят, но через Nova, seeder, tinker, import или прямой repository-вызов БД принимает неполную заявку.

Решение: привести БД к реальным требованиям: `name`, `phone`, `email` сделать not null, если email действительно обязателен для заявки. Если email в CRM должен быть nullable, нужно изменить `StoreOrderRequest`.

### 3. Enum-значения не закреплены в БД

Факт: в моделях заданы константы:

| Модель | Поля |
|--------|------|
| `Order` | `status`, `channel` |
| `BlogPost` | `kind` |
| `Page` | `menu_type`, `footer_group` |
| `ApiIntegration` | `last_test_status` |

Факт по миграциям: эти поля являются string/text без DB-check constraints.

Проблема: приложение ожидает ограниченный набор значений, но БД принимает любые строки. Это влияет на фильтры, метрики, маршрутизацию, footer/menu и публичные URL.

Решение: добавить DB-level ограничения там, где набор значений стабилен. Для MySQL 8 допустимы `CHECK` constraints. Альтернатива — отдельные lookup-таблицы, но для текущих небольших enum-наборов check constraints проще.

### 4. `user_roles.title` не уникален

Факт: `user_roles.title` не имеет unique index.

Факт по модели: роли определяются строками `Администратор`, `Модератор`, `Оператор`, `Клиент`; `User::isAdmin()` проверяет `userRole.title`.

Проблема: БД допускает несколько ролей с одинаковым `title`. Это не ломает `isAdmin()`, но создаёт дубли в справочнике ролей.

Решение: добавить unique index на `user_roles.title`.

### 5. Индексы не полностью покрывают реальные query-паттерны

Факты по repository-запросам:

| Запрос | Текущие индексы | Недостаток |
|--------|-----------------|------------|
| pages navigation: `active`, `show_in_menu`, order by `menu_order`, `id` | есть только `active` и unique `slug` | нет composite index под меню |
| pages footer: `active`, `show_in_footer`, order by `footer_group`, `footer_order`, `id` | есть только `active` | нет composite index под footer |
| projects listing: `active`, order by `year`, `id` | есть только `active` | нет composite index под сортировку |
| blog feed: `active`, `kind`, order by `published_at`, `id` | есть index `kind, active` и отдельный `published_at` | нет composite index под фильтр + сортировку |
| orders metrics/filter: `status`, `channel`, date grouping by `created_at` | нет индексов на `status`, `channel`, `created_at` | метрики будут сканировать таблицу при росте данных |

Для текущего небольшого сайта это не является функциональной ошибкой. Для роста данных это станет проблемой производительности.

### 6. `blog_posts.slug` уникален глобально, хотя маршруты разделены по kind

Факт: маршруты различают `/news/{slug}` и `/articles/{slug}`.

Факт: `blog_posts.slug` unique глобально для всей таблицы.

Последствие: нельзя иметь новость и статью с одинаковым slug, хотя URL технически не конфликтуют из-за разных префиксов.

Это не ошибка, если глобальная уникальность slug — сознательное правило редакции. Если нужно разрешить одинаковые slug в разных разделах, индекс должен быть composite unique `kind, slug`.

### 7. `ProjectTag` модель есть, но relation не использует custom pivot model

Факт: есть модель `ProjectTag extends Pivot`.

Факт: `Project::tags()` и `Tags::projects()` не вызывают `->using(ProjectTag::class)`.

Последствие: при обычной работе `belongsToMany()` Laravel использует стандартный pivot object, а не `ProjectTag`. Методы `ProjectTag::project()` и `ProjectTag::tag()` не используются relation-слоем.

Это не функциональная ошибка для текущего кода, потому что pivot не содержит собственной логики. Если модель `ProjectTag` не нужна, её можно удалить. Если нужна, relation нужно явно подключить через `using(ProjectTag::class)`.

### 8. `Docx/DB.md` не соответствует текущим миграциям

Подтверждённые расхождения:

| В `DB.md` | В текущих миграциях |
|-----------|---------------------|
| описана таблица `page_tags` | таблица удаляется миграцией `drop_page_tags_table` |
| не описана `blog_posts` | таблица создана |
| не описана `api_integrations` | таблица создана |
| `orders.channel` отсутствует в схеме документа | колонка добавлена миграцией |
| menu/footer поля `pages` отсутствуют в полной ER-таблице | поля добавлены миграциями |

Решение: обновить `Docx/DB.md` по фактическим миграциям.

---

## Рекомендации по исправлению

### Высокий приоритет

1. Обновить `Docx/DB.md`, чтобы документация не противоречила миграциям.

2. Зафиксировать обязательность ключевых полей на уровне БД:
   - `pages.slug`
   - `orders.name`
   - `orders.phone`
   - `orders.email`, если email действительно обязателен
   - `blog_posts.kind`
   - `blog_posts.title`
   - `projects.slug`

3. Добавить ограничения для enum-полей:
   - `orders.status`
   - `orders.channel`
   - `blog_posts.kind`
   - `pages.menu_type`
   - `pages.footer_group`
   - `api_integrations.last_test_status`

### Средний приоритет

4. Добавить unique index на `user_roles.title`.

5. Добавить composite indexes под реальные запросы:
   - `pages(active, show_in_menu, menu_order, id)`
   - `pages(active, show_in_footer, footer_group, footer_order, id)`
   - `projects(active, year, id)`
   - `blog_posts(kind, active, published_at, id)`
   - `orders(status)`
   - `orders(channel)`
   - `orders(created_at)`

6. Принять решение по `blog_posts.slug`:
   - оставить глобальный unique, если slug должен быть уникален во всём блоге;
   - заменить на `unique(kind, slug)`, если одинаковые slug допустимы в разных разделах.

### Низкий приоритет

7. Удалить `ProjectTag`, если custom pivot model не используется.

8. Если `ProjectTag` должен быть частью модели домена, подключить его в relation:

```php
return $this->belongsToMany(Tags::class, 'project_tags', 'project_id', 'tag_id')
    ->using(ProjectTag::class)
    ->withTimestamps();
```

---

## Проверки, которые не выполнены

Не выполнена проверка фактически применённой схемы через MySQL (`SHOW CREATE TABLE`, `INFORMATION_SCHEMA`, `php artisan migrate:status`). Для точного сравнения runtime-БД с миграциями нужны:

1. доступный MySQL-контейнер;
2. применённые миграции;
3. вывод `php artisan migrate:status`;
4. дамп `SHOW CREATE TABLE` для ключевых таблиц.

