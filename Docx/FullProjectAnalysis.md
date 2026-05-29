# Полный анализ проекта Nexora

Дата анализа: 2026-05-29  
Путь проекта: `d:\Projects\nexora2026`  
Формат: проверенные факты по файлам проекта и выполненным командам.  
Основа анализа: `composer.json`, `package.json`, маршруты, Laravel-код, Vue-код, миграции, модели, тесты, Docker-конфигурация, документы из `Docx/`.

---

## 1. Резюме

Nexora — Laravel 10 приложение с Vue 3 SPA, MySQL, Laravel Nova и Docker-окружением. Laravel отдаёт Blade-оболочку, Vue Router управляет публичными страницами, данные приходят через JSON API. Nova используется как CMS/CRM для страниц, блога, проектов, тегов, заявок и пользователей.

Главные подтверждённые проблемы:

| Приоритет | Проблема | Подтверждение |
|-----------|----------|---------------|
| Критичный | Frontend build не проходит | `npm run build` падает на unresolved `vue-router` |
| Высокий | Docker entrypoint пропускает неполную установку npm-зависимостей | проверяется только `node_modules/vue`, при этом `vue-router` отсутствует |
| Высокий | `Docx/DB.md` устарел относительно миграций | описывает `page_tags`, не описывает `blog_posts`, `api_integrations`, новые поля |
| Высокий | БД допускает некорректные данные для части бизнес-инвариантов | nullable и string-поля без DB-check constraints |
| Средний | `v-html` используется для CMS/Blog HTML | `GenericPage.vue`, `BlogPostViewPage.vue` |
| Средний | `POST /api/orders` находится в `web.php` | требует CSRF/session, хотя URL выглядит как API |
| Средний | PHP-тесты не запущены с хоста | `php` отсутствует в PATH |

---

## 2. Стек проекта

### Backend

| Компонент | Данные |
|-----------|--------|
| PHP | `^8.2` |
| Laravel | `^10.10` |
| Laravel Nova | `5.4.2` |
| Sanctum | `^3.3` |
| Guzzle | `^7.2` |
| Tinker | `^2.8` |

Файл: `nexora-app/composer.json`.

### Frontend

| Компонент | Данные |
|-----------|--------|
| Vue | `^3.5.34` |
| Vue Router | `^4.6.4` |
| Vite | `^5.0.0` |
| Axios | `^1.6.4` |
| Laravel Vite Plugin | `^1.0.0` |
| @vitejs/plugin-vue | `^6.0.7` |

Файл: `nexora-app/package.json`.

### Docker

| Компонент | Данные |
|-----------|--------|
| App image | `php:8.2-fpm` |
| Node.js | устанавливается Node.js 22 |
| Composer | Composer 2 |
| Nginx | `nginx:1.27-alpine` |
| MySQL | `mysql:8.0` |
| phpMyAdmin | `phpmyadmin:5.2` |

Файлы: `docker-compose.yml`, `docker/Dockerfile`.

---

## 3. Структура репозитория

```text
nexora2026/
├── docker-compose.yml
├── docker-compose.test.yml
├── docker/
│   ├── Dockerfile
│   ├── entrypoint.sh
│   └── nginx/default.conf
├── Docx/
│   ├── Analysis.md
│   ├── DatabaseModelAnalysis.md
│   ├── DB.md
│   ├── Docker.md
│   ├── Git.md
│   ├── GitHub.md
│   ├── info.md
│   ├── Promt.md
│   ├── Test.md
│   ├── Vue.md
│   ├── Yandex.md
│   └── ТЗ.md
└── nexora-app/
    ├── app/
    ├── config/
    ├── database/
    ├── public/
    ├── resources/
    ├── routes/
    └── tests/
```

`nexora-app/README.md` является стандартным Laravel README и не описывает проект Nexora.

---

## 4. Backend-архитектура

Основной паттерн backend:

```text
Route
→ Controller
→ Service
→ Repository
→ Eloquent Model
→ Database
```

| Слой | Путь | Назначение |
|------|------|------------|
| Controllers | `app/Http/Controllers/Backend/` | обработка HTTP-запросов |
| Requests | `app/Http/Requests/` | валидация входных данных |
| Services | `app/Services/` | бизнес-логика |
| Repositories | `app/Repositories/` | запросы к Eloquent |
| Resources | `app/Http/Resources/` | JSON-структуры API |
| Models | `app/Models/` | Eloquent-модели |
| Nova | `app/Nova/` | админ-панель |

Привязка repository-интерфейсов находится в `app/Providers/AppServiceProvider.php`.

### Доменные области backend

| Область | Основные классы | Назначение |
|---------|-----------------|------------|
| CMS-страницы | `Page`, `PageService`, `PageRepository`, `PageResource`, `PageNavResource`, `PageFooterResource` | страницы, меню, footer, SEO, JSON-контент |
| Портфолио | `Project`, `ProjectImage`, `Tags`, `ProjectService`, `ProjectRepository` | проекты, изображения, теги |
| Блог | `BlogPost`, `BlogController`, `BlogPostRepository`, `BlogPostResource` | новости и статьи |
| Заявки | `Order`, `StoreOrderRequest`, `OrderController`, `OrderService`, `OrderRepository` | форма обратной связи и CRM-заявки |
| SEO | `SeoService`, `SitemapController` | meta-теги и sitemap |
| Интеграции | `ApiIntegration`, `IntegrationManager`, `YandexWebmasterService`, `YandexWebmasterClient` | внешние API, сейчас Яндекс.Вебмастер |
| Пользователи | `User`, `UserRole`, Nova Gate | доступ к Nova |

---

## 5. Маршруты

### Web routes

Файл: `nexora-app/routes/web.php`.

| Метод | URI | Назначение |
|-------|-----|------------|
| `GET` | `/` | Blade shell для Vue SPA |
| `GET` | `/pricing` | Blade shell для SPA |
| `GET` | `/projects/{any?}` | Blade shell для SPA |
| `GET` | `/news/{any?}` | Blade shell для SPA |
| `GET` | `/articles/{any?}` | Blade shell для SPA |
| `GET` | `/{slug}` | Blade shell для CMS-страниц |
| `GET` | `/sitemap.xml` | sitemap |
| `POST` | `/api/orders` | создание заявки через web middleware |

Особенность: `POST /api/orders` расположен в `web.php`, а не в `api.php`. Поэтому маршрут использует web middleware, CSRF и session.

### API routes

Файл: `nexora-app/routes/api.php`.

| Группа | URI |
|--------|-----|
| Pages | `/api/page/navigation`, `/api/page/footer`, `/api/page/home`, `/api/page/pricing`, `/api/page/{slug}` |
| Projects | `/api/project/`, `/api/projects`, `/api/projects/{slug}` |
| Blog | `/api/news`, `/api/news/{slug}`, `/api/articles`, `/api/articles/{slug}` |
| Auth | `/api/user` через `auth:sanctum` |

Read-only API для страниц, проектов и блога публичный.

---

## 6. Frontend-архитектура

Frontend находится в `nexora-app/resources/js`.

Точка входа: `resources/js/app.js`.

Цепочка запуска:

```text
app.js
→ bootstrap.js
→ createAppConfigFromRoot()
→ createHttpClient()
→ createSiteApi()
→ preload navigation/footer
→ createSitePlugin()
→ createAppRouter()
→ App.vue
```

### Основные каталоги frontend

| Путь | Назначение |
|------|------------|
| `resources/js/router/index.js` | Vue Router маршруты |
| `resources/js/api/httpClient.js` | обёртка над Axios |
| `resources/js/api/createSiteApi.js` | фабрика API |
| `resources/js/api/modules/` | API-модули pages/projects/blog/orders |
| `resources/js/composables/` | загрузка данных и состояние страниц |
| `resources/js/components/Page/` | Vue-страницы |
| `resources/js/components/Layouts/` | Header/Footer |
| `resources/js/components/contact/ContactForm.vue` | форма заявки |
| `resources/js/components/ui/AsyncState.vue` | loading/error состояние |

### SPA routes

| Route | Component |
|-------|-----------|
| `/` | `IndexPage.vue` |
| `/pricing` | `PricingPage.vue` |
| `/projects` | `ProjectsPage.vue` |
| `/projects/:slug` | `ProjectViewPage.vue` |
| `/news` | `NewsPage.vue` |
| `/news/:slug` | `BlogPostViewPage.vue` |
| `/articles` | `ArticlesPage.vue` |
| `/articles/:slug` | `BlogPostViewPage.vue` |
| `/:slug` | `GenericPage.vue` |

### Frontend-риски

| Риск | Файл / область | Подтверждение |
|------|----------------|---------------|
| `v-html` для CMS/Blog контента | `GenericPage.vue`, `BlogPostViewPage.vue` | HTML выводится напрямую |
| Неполная установка npm-зависимостей | `node_modules`, `package.json` | `vue-router` отсутствует при наличии `vue` |
| Разный контракт `get`/`post` | `api/httpClient.js` | `get` возвращает `data.data ?? data`, `post` возвращает `data` |
| Неиспользуемый CSRF token из config | `appConfig.js`, Blade data attributes | POST полагается на Axios cookie |
| Нет frontend-тестов | `resources/js` | не обнаружены `*.test.js`, `*.spec.js`, `*.test.vue` |

---

## 7. Nova

Nova подключена через `app/Providers/NovaServiceProvider.php`.

Доступ к Nova:

```text
Gate viewNova
→ User::isAdmin()
→ UserRole::ADMINS
```

В `UserRole::ADMINS` входят:

| Роль |
|------|
| `Администратор` |
| `Модератор` |
| `Оператор` |

### Nova menu

| Раздел | Ресурсы |
|--------|---------|
| Dashboards | `Main`, `OrdersAnalytics`, `SiteAnalytics` |
| Пользователи | `User`, `UserRole` |
| Сайт | `Page`, `BlogPost`, `Project`, `Tags` |
| CRM | `Order` |

### Nova Page CMS

Ресурс `Nova\Page` использует slug-зависимые поля из `app/Nova/PageContent/`:

| Файл | Назначение |
|------|------------|
| `HomePageFields.php` | поля главной |
| `PricingPageFields.php` | поля страницы цен |
| `ListingPageFields.php` | поля листингов |
| `GenericPageFields.php` | поля обычных страниц |
| `PageContentField.php` | сборка поля content |

### Риск по API-интеграциям

`Nova\ApiIntegration` описывает поля и action `TestApiIntegration`, но методы `authorizedToViewAny()` и `authorizedToCreate()` закрывают доступ. Управление интеграцией через Nova UI недоступно.

---

## 8. База данных и модели

Фактическая схема задана миграциями в `nexora-app/database/migrations`.

### Таблицы

| Таблица | Назначение | Ключевые ограничения |
|---------|------------|----------------------|
| `users` | пользователи Laravel/Nova/Sanctum | `email` unique |
| `user_roles` | роли пользователей | PK `id` |
| `pages` | CMS-страницы, меню, footer, SEO | `slug` unique, `active` index |
| `projects` | портфолио | `slug` unique, `active` index |
| `project_images` | изображения проекта | FK `project_id` cascade |
| `tags` | теги проектов | `name` unique, `slug` unique |
| `project_tags` | связь проектов и тегов | composite PK, FK cascade |
| `orders` | заявки | `status` default `new` |
| `blog_posts` | новости и статьи | `slug` unique, indexes `kind, active`, `published_at` |
| `api_integrations` | API-интеграции | `slug` unique, `driver` unique |
| `personal_access_tokens` | Sanctum tokens | Laravel default |
| `password_reset_tokens` | password reset | `email` primary |
| `failed_jobs` | failed queue jobs | `uuid` unique |

### Соответствие моделей таблицам

| Модель | Таблица | Оценка |
|--------|---------|--------|
| `User` | `users` | соответствует: fillable, casts, `belongsTo(UserRole)`, `HasApiTokens` |
| `UserRole` | `user_roles` | соответствует: `hasMany(User)` |
| `Page` | `pages` | соответствует полям; enum-инварианты не закреплены в БД |
| `Project` | `projects` | соответствует: `belongsToMany(Tags)`, `hasMany(ProjectImage)` |
| `ProjectImage` | `project_images` | соответствует: `belongsTo(Project)` |
| `ProjectTag` | `project_tags` | соответствует pivot, но не подключён через `using(ProjectTag::class)` |
| `Tags` | `tags` | соответствует: table `tags`, `belongsToMany(Project)` |
| `Order` | `orders` | соответствует таблице; статусы и каналы не закреплены в БД |
| `BlogPost` | `blog_posts` | соответствует таблице; `kind` не закреплён в БД |
| `ApiIntegration` | `api_integrations` | соответствует: encrypted credentials, array settings |

### DB-риски

| Риск | Подтверждение |
|------|---------------|
| `pages.slug` nullable при использовании как route key | migration создаёт nullable, модель использует slug |
| `orders.name/phone/email` nullable в БД | FormRequest требует `name`, `phone`, `email` |
| Enum-поля принимают любые строки | `orders.status/channel`, `blog_posts.kind`, `pages.menu_type/footer_group` |
| `user_roles.title` не unique | роли определяются строками |
| Индексы не покрывают все query-паттерны | navigation/footer/blog/orders metrics |
| `Docx/DB.md` устарел | `page_tags` удалён, `blog_posts` и `api_integrations` не описаны |

---

## 9. Интеграции

Единственный драйвер в `config/integrations.php`: `yandex_webmaster`.

Цепочка:

```text
ApiIntegration
→ IntegrationManager::resolve()
→ YandexWebmasterClient
→ YandexWebmasterService
→ Nova SiteAnalytics metrics
```

Env fallback:

| Env | Назначение |
|-----|------------|
| `YANDEX_WEBMASTER_ENABLED` | включение |
| `YANDEX_WEBMASTER_OAUTH_TOKEN` | OAuth-токен |
| `YANDEX_WEBMASTER_HOST_ID` | Host ID |
| `YANDEX_WEBMASTER_SITE_URL` | URL сайта |
| `YANDEX_WEBMASTER_CACHE_TTL` | TTL кеша |

Подтверждённый риск: `IntegrationManager::resolveFromEnv()` читает `env()` в runtime. Для production-конфигурации с `config:cache` корректнее читать значения через config.

---

## 10. Docker и инфраструктура

### Основной compose

Файл: `docker-compose.yml`.

| Сервис | Назначение | Порты |
|--------|------------|-------|
| `app` | Laravel / PHP-FPM | внутренний `9000` |
| `nginx` | web server | `80:80` |
| `db` | MySQL 8.0 | `127.0.0.1:8102:3306` |
| `phpmyadmin` | phpMyAdmin | `127.0.0.1:8080:80` |

### Dockerfile

Файл: `docker/Dockerfile`.

Устанавливает:

| Компонент |
|-----------|
| PHP extensions: `bcmath`, `mbstring`, `pdo_mysql`, `zip`, `exif`, `pcntl` |
| Node.js 22 |
| Composer 2 |
| `docker/entrypoint.sh` |

### Entrypoint

Файл: `docker/entrypoint.sh`.

Действия:

1. `composer install`, если нет `vendor/autoload.php`;
2. `npm ci` или `npm install`, если нет `node_modules/vue`;
3. `npm run build`, если нет `public/build/manifest.json`;
4. `php artisan storage:link`, если нет `public/storage`;
5. запуск `php-fpm`.

Проблема: проверка `node_modules/vue` не гарантирует полную установку зависимостей. Текущее состояние подтверждает дефект: `vue` установлен, `vue-router` отсутствует.

---

## 11. Тесты

Файл: `nexora-app/phpunit.xml`.

| Suite | Каталог |
|-------|---------|
| Unit | `tests/Unit` |
| Feature | `tests/Feature` |

Тестовая БД:

| Параметр | Значение |
|----------|----------|
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `db` |
| `DB_DATABASE` | `nexora_test` |
| `DB_USERNAME` | `root` |
| `DB_PASSWORD` | `root` |

Покрытые области:

| Область | Файлы |
|---------|-------|
| Users/Roles | `UserModelTest.php`, `UserRoleModelTest.php` |
| Pages/Navigation | `PageNavigationTest.php`, `PricingPageTest.php`, `HomeControllerTest.php` |
| Blog | `BlogTest.php` |
| Orders | `OrderStoreTest.php` |
| SEO | `SeoTest.php` |
| Nova Page | `NovaPageResourceTest.php` |
| Integrations | `IntegrationManagerTest.php`, `YandexWebmasterServiceTest.php` |
| Public assets | `PublicAssetUrlTest.php` |

Локальная проверка `php artisan test` не выполнена: `php` отсутствует в PATH текущей shell-среды.

---

## 12. Выполненные проверки

| Проверка | Результат |
|----------|-----------|
| `npm run build` | ошибка: Rollup не resolve `vue-router` |
| `npm ls vue-router` | пустое дерево зависимости |
| `npm ls vue` | `vue@3.5.34` установлен |
| `php artisan test` | не выполнено: нет `php` в PATH |
| `git status --short` | рабочее дерево не чистое |

---

## 13. Состояние git

Текущее рабочее дерево содержит modified и untracked файлы:

```text
 M Docx/Promt.md
 M Docx/info.md
 M docker/Dockerfile
 M nexora-app/app/Nova/ApiIntegration.php
 M nexora-app/app/Nova/BlogPost.php
 M nexora-app/app/Nova/Page.php
 M nexora-app/app/Providers/NovaServiceProvider.php
 M nexora-app/resources/css/landing.css
 M nexora-app/resources/js/components/Page/BlogPostViewPage.vue
 M nexora-app/resources/js/components/Page/GenericPage.vue
?? .gitattributes
?? Docx/Analysis.md
?? Docx/DatabaseModelAnalysis.md
?? nexora-app/app/Nova/Filters/PageSlugFilter.php
?? nexora-app/app/Nova/PageContent/
?? nexora-app/tests/Feature/NovaPageResourceTest.php
```

Этот документ анализирует текущее рабочее дерево, а не чистое состояние последнего commit.

---

## 14. Документация

| Файл | Состояние |
|------|-----------|
| `Docx/info.md` | описывает архитектуру, но часть frontend-структуры устарела относительно текущих файлов |
| `Docx/DB.md` | устарел относительно миграций |
| `Docx/Docker.md` | содержит рабочие инструкции, но содержит устаревшие пути к проекту |
| `Docx/Test.md` | описывает тестовую БД и запуск тестов |
| `nexora-app/README.md` | стандартный Laravel README, не проектная документация |
| `Docx/Analysis.md` | общий анализ проекта |
| `Docx/DatabaseModelAnalysis.md` | анализ БД и моделей |

---

## 15. Рекомендуемый порядок работ

### 15.1. Восстановить frontend build

```powershell
cd nexora-app
npm ci
npm run build
```

После успешной сборки повторно проверить `public/build/manifest.json`.

### 15.2. Исправить Docker entrypoint

Текущая проверка:

```sh
if [ ! -d node_modules/vue ]; then
  npm ci --no-audit --no-fund
fi
```

Нужно заменить на проверку полной установки зависимостей или выполнять `npm ci` при отсутствии lockfile-согласованного `node_modules`.

### 15.3. Запустить PHP-тесты в Docker

```powershell
docker compose up -d db
docker compose run --rm test
```

Перед запуском нужна созданная БД `nexora_test`.

### 15.4. Обновить документацию БД

`Docx/DB.md` нужно привести к фактическим миграциям:

| Удалить / изменить | Добавить |
|--------------------|----------|
| `page_tags` | `blog_posts` |
| старую схему `pages` | menu/footer поля |
| старую схему `orders` | `orders.channel` |
| неполный список доменов | `api_integrations` |

### 15.5. Укрепить DB-инварианты

Добавить миграции для:

| Инвариант | Поля |
|-----------|------|
| not null | `pages.slug`, `orders.name`, `orders.phone`, `orders.email`, `blog_posts.kind`, `blog_posts.title` |
| unique | `user_roles.title` |
| check constraints | `orders.status`, `orders.channel`, `blog_posts.kind`, `pages.menu_type`, `pages.footer_group`, `api_integrations.last_test_status` |
| indexes | navigation/footer/blog/orders query-паттерны |

### 15.6. Закрыть security-риск `v-html`

Файлы:

| Файл | Поле |
|------|------|
| `GenericPage.vue` | `page.content.body` |
| `BlogPostViewPage.vue` | `post.content` |

Требуется выбрать один источник sanitization:

1. sanitization HTML на backend перед сохранением или выдачей;
2. sanitization на frontend перед `v-html`;
3. запрет HTML в этих полях и рендер безопасного plain text/markdown.

### 15.7. Перенести integration fallback в config

`IntegrationManager::resolveFromEnv()` должен читать значения из config, а env-вызовы должны остаться в config-файлах.

### 15.8. Привести README к проекту

Заменить стандартный `nexora-app/README.md` на проектный README:

| Раздел |
|--------|
| назначение проекта |
| стек |
| Docker start |
| миграции и seed |
| frontend build |
| тесты |
| Nova |
| API-интеграции |

---

## 16. Проверки, которые не выполнены

Недостаточно данных/контекста для точного ответа по runtime-состоянию MySQL и полному статусу PHPUnit. Пожалуйста, предоставьте:

1. вывод `docker compose ps`;
2. вывод `docker compose exec app php artisan migrate:status`;
3. вывод `docker compose exec app php artisan test`;
4. дамп `SHOW CREATE TABLE` для `pages`, `orders`, `blog_posts`, `projects`, `api_integrations`;
5. результат `npm ci && npm run build` после восстановления `node_modules`.

