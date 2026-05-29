# Анализ проекта Nexora

Дата анализа: 2026-05-29  
Путь проекта: `d:\Projects\nexora2026`  
Основа анализа: файлы репозитория, `composer.json`, `package.json`, маршруты, Docker-конфиги, тесты и результаты локальных команд.

---

## Краткий итог

Проект — Laravel 10 приложение с Vue 3 SPA, админ-панелью Laravel Nova и Docker-окружением. Публичная часть работает как SPA: Laravel отдаёт Blade-оболочку, Vue Router управляет клиентскими страницами, данные приходят через JSON API.

Критичный проверенный блокер на момент анализа: `npm run build` падает, потому что текущий `node_modules` не содержит `vue-router`, хотя зависимость указана в `package.json` и `package-lock.json`.

PHP-тесты с хоста не запущены: команда `php artisan test` недоступна, потому что `php` отсутствует в PATH текущей shell-среды.

---

## Стек

| Слой | Проверенные данные |
|------|--------------------|
| Backend | PHP `^8.2`, Laravel `^10.10`, Laravel Nova `5.4.2`, Sanctum `^3.3`, Guzzle `^7.2` |
| Frontend | Vue `^3.5.34`, Vue Router `^4.6.4`, Vite `^5.0.0`, Axios `^1.6.4` |
| Dev/Test | PHPUnit `^10.1`, Laravel Pint, Laravel Sail, Faker, Mockery |
| Docker | `php:8.2-fpm`, Node.js 22, Composer 2, `nginx:1.27-alpine`, `mysql:8.0`, `phpmyadmin:5.2` |
| База данных | MySQL; основная БД `nexora_db`, тестовая БД `nexora_test` |

---

## Архитектура backend

Основной паттерн backend: Controller → Service → Repository → Eloquent Model.

| Слой | Путь | Назначение |
|------|------|------------|
| Controllers | `nexora-app/app/Http/Controllers/Backend/` | HTTP-запросы, JSON-ответы, SPA shell |
| Services | `nexora-app/app/Services/` | бизнес-логика и сборка данных |
| Repositories | `nexora-app/app/Repositories/` | доступ к данным через Eloquent |
| Resources | `nexora-app/app/Http/Resources/` | форматирование JSON API |
| Models | `nexora-app/app/Models/` | Eloquent-модели домена |
| Nova | `nexora-app/app/Nova/` | админ-панель CMS/CRM |

Привязка repository-интерфейсов находится в `app/Providers/AppServiceProvider.php`.

### Основные доменные области

| Область | Файлы / классы | Назначение |
|---------|----------------|------------|
| CMS-страницы | `Page`, `PageService`, `PageRepository`, `PageResource`, `PageNavResource`, `PageFooterResource` | страницы сайта, меню, footer, SEO-поля, JSON-контент |
| Заявки | `OrderController`, `StoreOrderRequest`, `OrderService`, `OrderRepository`, `NewOrderMail` | отправка формы заявки, сохранение заказа, email-уведомление |
| Портфолио | `ProjectController`, `ProjectService`, `ProjectRepository`, `ProjectResource` | список проектов, детальная страница проекта, sitemap |
| Блог | `BlogController`, `BlogPostRepository`, `BlogPostResource` | новости и статьи |
| SEO | `SeoService`, `SitemapController` | meta-теги для Blade shell, sitemap URLs |
| Интеграции | `IntegrationManager`, `YandexWebmasterService`, `YandexWebmasterClient` | API-интеграции, сейчас настроен Яндекс.Вебмастер |

---

## Маршруты

### Web routes

Файл: `nexora-app/routes/web.php`

| Метод | URI | Назначение |
|-------|-----|------------|
| `GET` | `/` | SPA shell для главной |
| `GET` | `/pricing` | SPA shell для страницы цен |
| `GET` | `/projects/{any?}` | SPA shell для портфолио |
| `GET` | `/news/{any?}` | SPA shell для новостей |
| `GET` | `/articles/{any?}` | SPA shell для статей |
| `GET` | `/{slug}` | SPA shell для CMS-страниц |
| `GET` | `/sitemap.xml` | sitemap |
| `POST` | `/api/orders` | отправка заявки через web middleware, с CSRF и throttle `10,1` |

### API routes

Файл: `nexora-app/routes/api.php`

| Группа | URI |
|--------|-----|
| Pages | `/api/page/navigation`, `/api/page/footer`, `/api/page/home`, `/api/page/pricing`, `/api/page/{slug}` |
| Projects | `/api/project/`, `/api/projects`, `/api/projects/{slug}` |
| Blog | `/api/news`, `/api/news/{slug}`, `/api/articles`, `/api/articles/{slug}` |
| Auth | `/api/user` через `auth:sanctum` |

Публичные read-only API маршруты не требуют авторизации.

---

## Архитектура frontend

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

Структура frontend:

| Каталог / файл | Назначение |
|----------------|------------|
| `router/index.js` | Vue Router маршруты |
| `api/httpClient.js` | обёртка над Axios |
| `api/createSiteApi.js` | фабрика API-модулей |
| `api/modules/*.js` | page/blog/project/order API |
| `composables/*.js` | загрузка данных, CMS-страницы, навигация, footer, портфолио, форма заказа |
| `components/Page/*.vue` | страницы SPA |
| `components/Layouts/*.vue` | Header, Footer, FooterNav |
| `components/contact/ContactForm.vue` | форма заявки |
| `components/ui/AsyncState.vue` | loading/error wrapper |

### SPA маршруты

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

---

## Nova

Nova подключена через `app/Providers/NovaServiceProvider.php`.

Доступ к Nova определяется gate `viewNova`: пользователь должен проходить `$user->isAdmin()`.

Меню Nova содержит:

| Раздел | Содержимое |
|--------|------------|
| Dashboards | `Main`, `OrdersAnalytics`, `SiteAnalytics` |
| Пользователи | `User`, `UserRole` |
| Сайт | `Page`, `BlogPost`, `Project`, `Tags` |
| CRM | `Order` |

Для страниц используется редактор контента по slug через файлы `app/Nova/PageContent/*`.

Проверенный риск: ресурс `Nova\ApiIntegration` описан, но `authorizedToViewAny()` и `authorizedToCreate()` возвращают `false`, поэтому управление API-интеграциями через Nova UI закрыто.

---

## Интеграции

Единственный драйвер в `config/integrations.php`: `yandex_webmaster`.

Цепочка работы:

```text
ApiIntegration
→ IntegrationManager::resolve()
→ YandexWebmasterClient
→ YandexWebmasterService
→ Nova metrics / yandex:webmaster:status
```

Данные интеграции берутся из БД. Если запись не включена или пустая, используется fallback из env-переменных:

| Env | Назначение |
|-----|------------|
| `YANDEX_WEBMASTER_ENABLED` | включение fallback-интеграции |
| `YANDEX_WEBMASTER_OAUTH_TOKEN` | OAuth-токен |
| `YANDEX_WEBMASTER_HOST_ID` | Host ID |
| `YANDEX_WEBMASTER_SITE_URL` | URL сайта |
| `YANDEX_WEBMASTER_CACHE_TTL` | TTL кеша |

Риск: в `IntegrationManager::resolveFromEnv()` используется `env()` во время runtime. При `config:cache` это может дать некорректное поведение для fallback-настроек.

---

## Docker и инфраструктура

Файлы:

| Файл | Назначение |
|------|------------|
| `docker-compose.yml` | основной compose stack |
| `docker-compose.test.yml` | test overlay с profile `test` |
| `docker/Dockerfile` | PHP-FPM образ |
| `docker/entrypoint.sh` | автоустановка зависимостей, build frontend, storage link |
| `docker/nginx/default.conf` | Nginx конфигурация |

Сервисы основного compose:

| Сервис | Назначение |
|--------|------------|
| `app` | Laravel / PHP-FPM |
| `nginx` | web server, порт `80:80` |
| `db` | MySQL 8.0, порт `127.0.0.1:8102:3306` |
| `phpmyadmin` | phpMyAdmin, порт `127.0.0.1:8080:80` |

В `docker/entrypoint.sh` есть проверка:

```sh
if [ ! -d node_modules/vue ]; then
  npm ci --no-audit --no-fund
fi
```

Проверенный риск: если `node_modules/vue` есть, но других зависимостей нет, `npm ci` не запустится. Именно такая ситуация сейчас видна для `vue-router`: `vue` установлен, `vue-router` отсутствует.

---

## Тесты

Файл конфигурации: `nexora-app/phpunit.xml`.

Suites:

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

Покрытые темы по тестовым файлам:

| Тема | Файлы |
|------|-------|
| Home / routing | `HomeControllerTest.php`, `ExampleTest.php` |
| Pages / navigation | `PageNavigationTest.php`, `PricingPageTest.php` |
| Blog | `BlogTest.php` |
| Orders | `OrderStoreTest.php` |
| SEO | `SeoTest.php` |
| Nova Page | `NovaPageResourceTest.php` |
| Yandex Webmaster | `YandexWebmasterServiceTest.php` |
| IntegrationManager | `IntegrationManagerTest.php` |
| Users / roles | `UserModelTest.php`, `UserRoleModelTest.php` |
| Public assets | `PublicAssetUrlTest.php` |

Проверенный факт: локальная команда `php artisan test` с хоста не выполнена, потому что `php` не найден в PATH.

---

## Проверки, выполненные во время анализа

| Команда | Результат |
|---------|-----------|
| `npm run build` | Ошибка: Rollup не может resolve `vue-router` из `resources/js/router/index.js` |
| `npm ls vue-router` | Ошибка / пустое дерево: `vue-router` не установлен в текущем `node_modules` |
| `npm ls vue` | Успешно: `vue@3.5.34` установлен |
| `php artisan test` | Не выполнено: `php` отсутствует в PATH |
| `git status --short` | Есть modified и untracked файлы |

---

## Основные риски

| Приоритет | Риск | Файл / область |
|-----------|------|----------------|
| Высокий | Frontend build сейчас не проходит из-за отсутствующего `vue-router` в `node_modules` | `resources/js/router/index.js`, `package.json`, `node_modules` |
| Высокий | Entrypoint Docker может пропустить `npm ci` при частично установленном `node_modules` | `docker/entrypoint.sh` |
| Высокий | `v-html` используется без клиентской санитизации | `GenericPage.vue`, `BlogPostViewPage.vue` |
| Средний | `POST /api/orders` находится в `web.php`, поэтому зависит от CSRF/session | `routes/web.php`, `StoreOrderRequest.php` |
| Средний | Read-only API публичные и без auth | `routes/api.php` |
| Средний | `env()` используется в runtime для integration fallback | `IntegrationManager.php` |
| Средний | Nova-ресурс API-интеграций закрыт, хотя поля и action описаны | `Nova/ApiIntegration.php` |
| Средний | `httpClient.get()` и `httpClient.post()` возвращают разные структуры ответа | `resources/js/api/httpClient.js` |
| Средний | SEO meta в SPA может сохранять старое значение при пустом description/content | `resources/js/services/seo.js` |
| Низкий | README проекта не описывает Nexora, используется стандартный Laravel README | `nexora-app/README.md` |
| Низкий | В документации есть устаревшие пути к проекту | `Docx/Docker.md`, `Docx/Git.md` |

---

## Git-состояние на момент анализа

Рабочее дерево не чистое. Зафиксированы modified и untracked файлы:

```text
 M Docx/Promt.md
 M docker/Dockerfile
 M nexora-app/app/Nova/ApiIntegration.php
 M nexora-app/app/Nova/BlogPost.php
 M nexora-app/app/Nova/Page.php
 M nexora-app/app/Providers/NovaServiceProvider.php
 M nexora-app/resources/css/landing.css
 M nexora-app/resources/js/components/Page/BlogPostViewPage.vue
 M nexora-app/resources/js/components/Page/GenericPage.vue
?? .gitattributes
?? nexora-app/app/Nova/Filters/PageSlugFilter.php
?? nexora-app/app/Nova/PageContent/
?? nexora-app/tests/Feature/NovaPageResourceTest.php
```

Анализ относится к текущему рабочему дереву, а не к чистому состоянию последнего commit.

---

## Рекомендуемый порядок действий

1. Восстановить frontend-зависимости:

```powershell
cd nexora-app
npm ci
npm run build
```

2. Усилить проверку зависимостей в `docker/entrypoint.sh`: проверять не только `node_modules/vue`, но и полную установку по lockfile или выполнять `npm ci` при отсутствии ключевых runtime-зависимостей.

3. Запустить PHP-тесты внутри Docker, где доступен PHP и MySQL:

```powershell
docker compose up -d db
docker compose run --rm test
```

4. Проверить и закрыть риски `v-html`: либо санитизация HTML на backend до сохранения/выдачи, либо безопасный whitelist sanitizer.

5. Исправить runtime `env()` в `IntegrationManager`: читать значения через config, а env использовать только в config-файлах.

6. Обновить документацию: заменить стандартный `nexora-app/README.md`, исправить устаревшие пути в `Docx/Docker.md` и `Docx/Git.md`.

