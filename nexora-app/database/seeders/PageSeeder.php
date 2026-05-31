<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $homeContent = [
            'hero' => [
                'eyebrow' => 'Частный веб-разработчик',
                'title' => 'Создаю сайты и цифровые продукты под задачи бизнеса',
                'lead' => 'Работаю напрямую с владельцами и командами: без менеджерской прослойки, студийной наценки и шаблонных решений низкого качества. Беру проект в работу лично и довожу до запуска.',
            ],
            'stats' => [
                ['value' => '1', 'label' => 'ответственный специалист'],
                ['value' => '8+', 'label' => 'лет в веб-разработке'],
                ['value' => '150+', 'label' => 'завершённых задач и проектов'],
            ],
            'services' => [
                ['num' => '01', 'title' => 'Сайты на понятной архитектуре', 'text' => 'Создаю сайты на Laravel, WordPress, 1C-Битрикс и других CMS с учётом задач бизнеса.'],
                ['num' => '02', 'title' => 'SEO и базовое продвижение', 'text' => 'Готовлю структуру, метаданные и техническую основу для роста в Яндекс и Google.'],
                ['num' => '03', 'title' => 'Интернет-магазины и порталы', 'text' => 'Каталоги, личные кабинеты, биллинг, интеграции с CRM, складом и платёжными системами.'],
                ['num' => '04', 'title' => 'Быстрый старт', 'text' => 'Запуск на базе проверенных решений за 1–2 недели — без лишней бюрократии и долгих согласований.'],
                ['num' => '05', 'title' => 'Поддержка и развитие', 'text' => 'Доработки, техническое сопровождение, новые разделы и интеграции после запуска.'],
            ],
            'cases' => [
                [
                    'tag' => 'Недвижимость',
                    'title' => 'Портал объектов',
                    'client' => 'Клиент: агентство недвижимости',
                    'points' => ['Каталог объектов по направлениям', 'Внутренний биллинг и платные пакеты', 'Автообновление объявлений для агентств'],
                    'result' => 'портал с посещаемостью до 30 тыс. пользователей в сутки.',
                ],
                [
                    'tag' => 'Финансы',
                    'title' => 'Корпоративный портал',
                    'client' => 'Клиент: страховая компания',
                    'points' => ['Калькулятор взносов и онлайн-оплата', 'Кабинеты клиента, агента и брокера', 'Конструктор виджетов для банков'],
                    'result' => 'единая платформа с разными уровнями доступа.',
                ],
                [
                    'tag' => 'Логистика',
                    'title' => 'Доставка и таможня',
                    'client' => 'Клиент: международная логистика',
                    'points' => ['Оформление доставки онлайн', 'Интеграция со складом и таможней', 'Расчёт пошлин и стоимости доставки'],
                    'result' => 'система до 500 одновременных подключений.',
                ],
            ],
            'benefits' => [
                ['title' => 'Прямое общение', 'text' => 'Вы обсуждаете задачи сразу с исполнителем, поэтому решения принимаются быстрее и точнее.'],
                ['title' => 'Опыт 8+ лет', 'text' => 'Я постоянно развиваю навыки и выбираю технологии под задачу, а не под модный стек.'],
                ['title' => 'Без студийной наценки', 'text' => 'Бюджет уходит в работу над продуктом, а не в менеджмент, офис и лишние процессы.'],
                ['title' => 'Ответственность', 'text' => 'Один человек отвечает за результат, сроки, качество и дальнейшую поддержку проекта.'],
            ],
            'team' => [
                ['initial' => '01', 'name' => 'Бриф', 'role' => 'Разбираю цель, аудиторию, ограничения и приоритеты.'],
                ['initial' => '02', 'name' => 'Прототип', 'role' => 'Собираю структуру страниц и сценарии пользователя.'],
                ['initial' => '03', 'name' => 'Разработка', 'role' => 'Верстаю, подключаю CMS, формы, интеграции и аналитику.'],
                ['initial' => '04', 'name' => 'Запуск', 'role' => 'Проверяю адаптив, скорость, SEO-основу и выкладываю проект.'],
            ],
            'clients' => ['GoHome', 'PriorLife', 'ProStore', 'Микро Лизинг', 'Fine Adviser', 'USAPostLine', 'Айчына', 'Белджи', 'Невестам', 'Teploluxe', 'Haval', 'Йетти'],
            'departments' => [
                ['icon' => '📈', 'title' => 'Стратегия', 'text' => 'Определяю, какие страницы и функции действительно нужны для результата.'],
                ['icon' => '⚙️', 'title' => 'Разработка', 'text' => 'Делаю функционал стабильным, понятным в поддержке и готовым к росту.'],
                ['icon' => '📱', 'title' => 'Адаптив', 'text' => 'Проверяю удобство на мобильных устройствах, планшетах и десктопах.'],
                ['icon' => '🎯', 'title' => 'Коммуникация', 'text' => 'Держу вас в курсе статуса и быстро согласовываю важные решения.'],
                ['icon' => '🎨', 'title' => 'Интерфейс', 'text' => 'Собираю визуал, который выглядит аккуратно и помогает пользователю двигаться к заявке.'],
            ],
        ];

        $pricingContent = [
            'intro' => [
                'eyebrow' => 'Стоимость',
                'lead' => 'Прозрачные тарифы на разработку сайтов. Финальная цена зависит от объёма работ и согласовывается после брифа.',
            ],
            'plans' => [
                [
                    'name' => 'Старт',
                    'price' => '890',
                    'currency' => 'BYN',
                    'period' => 'от',
                    'description' => 'Лендинг или сайт-визитка для быстрого запуска.',
                    'featured' => false,
                    'features' => [
                        'До 5 страниц',
                        'Адаптивная вёрстка',
                        'Базовая SEO-настройка',
                        'Форма обратной связи',
                        '1 месяц поддержки',
                    ],
                    'cta' => 'Заказать',
                ],
                [
                    'name' => 'Бизнес',
                    'price' => '2 490',
                    'currency' => 'BYN',
                    'period' => 'от',
                    'description' => 'Корпоративный сайт с CMS и расширенным функционалом.',
                    'featured' => true,
                    'badge' => 'Популярный',
                    'features' => [
                        'До 15 страниц',
                        'Админ-панель (CMS)',
                        'Интеграция аналитики',
                        'SEO-оптимизация',
                        '3 месяца поддержки',
                    ],
                    'cta' => 'Обсудить проект',
                ],
                [
                    'name' => 'Портал',
                    'price' => '5 900',
                    'currency' => 'BYN',
                    'period' => 'от',
                    'description' => 'Интернет-магазин, портал или CRM-решение под ключ.',
                    'featured' => false,
                    'features' => [
                        'Индивидуальный дизайн',
                        'Личные кабинеты',
                        'Интеграции (CRM, оплата)',
                        'Нагрузочное тестирование',
                        '6 месяцев поддержки',
                    ],
                    'cta' => 'Получить смету',
                ],
            ],
            'extras' => [
                [
                    'title' => 'SEO-продвижение',
                    'text' => 'от 350 BYN/мес — аудит, семантика, контент, ссылки.',
                ],
                [
                    'title' => 'Техподдержка',
                    'text' => 'от 150 BYN/мес — обновления, резервные копии, мониторинг.',
                ],
                [
                    'title' => 'Доработки',
                    'text' => 'от 45 BYN/час — новые разделы, интеграции, дизайн.',
                ],
            ],
            'note' => 'Точная стоимость рассчитывается после обсуждения задач. Оставьте заявку — я подготовлю понятную оценку в течение 1–2 рабочих дней.',
        ];

        $trixBody = fn (string $html): array => ['body' => $html];

        $pages = [
            [
                'slug' => 'home',
                'title' => 'Nexora — частная разработка сайтов',
                'description' => 'Nexora — личная разработка и сопровождение веб-проектов под ключ. Современные сайты, порталы и автоматизация бизнес-процессов.',
                'content' => $homeContent,
                'active' => true,
                'show_in_menu' => false,
                'menu_order' => 0,
                'menu_type' => 'route',
                'seo_title' => 'Nexora — частная разработка сайтов',
                'seo_description' => 'Nexora — личная разработка и сопровождение веб-проектов под ключ.',
            ],
            [
                'slug' => 'nav-services',
                'title' => 'Услуги',
                'description' => null,
                'content' => null,
                'active' => true,
                'show_in_menu' => true,
                'menu_order' => 10,
                'menu_label' => 'Услуги',
                'menu_type' => 'anchor',
                'menu_hash' => '#services',
            ],
            [
                'slug' => 'projects',
                'title' => 'Портфолио — Nexora',
                'description' => 'Портфолио Nexora: сайты, интернет-магазины, порталы и CRM-решения частного веб-разработчика.',
                'content' => null,
                'active' => true,
                'show_in_menu' => true,
                'show_in_footer' => true,
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 20,
                'footer_label' => 'Портфолио',
                'menu_order' => 20,
                'menu_label' => 'Портфолио',
                'menu_type' => 'route',
                'seo_title' => 'Портфолио — Nexora',
                'seo_description' => 'Портфолио Nexora: сайты, интернет-магазины, порталы и CRM-решения частного веб-разработчика.',
            ],
            [
                'slug' => 'news',
                'title' => 'Новости — Nexora',
                'description' => 'Новости Nexora: запуски проектов, обновления сервиса и заметки из практики.',
                'content' => null,
                'active' => true,
                'show_in_menu' => true,
                'show_in_footer' => true,
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 30,
                'footer_label' => 'Новости',
                'menu_order' => 25,
                'menu_label' => 'Новости',
                'menu_type' => 'route',
                'seo_title' => 'Новости Nexora — частный веб-разработчик',
                'seo_description' => 'Актуальные новости Nexora о разработке сайтов и digital-проектах.',
            ],
            [
                'slug' => 'articles',
                'title' => 'Статьи — Nexora',
                'description' => 'Полезные материалы о создании сайтов, SEO и автоматизации бизнеса.',
                'content' => null,
                'active' => true,
                'show_in_menu' => true,
                'show_in_footer' => true,
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 40,
                'footer_label' => 'Статьи',
                'menu_order' => 26,
                'menu_label' => 'Статьи',
                'menu_type' => 'route',
                'seo_title' => 'Статьи о веб-разработке — Nexora',
                'seo_description' => 'Статьи Nexora: как выбрать подрядчика, SEO, CMS и сопровождение сайтов.',
            ],
            [
                'slug' => 'pricing',
                'title' => 'Цены — Nexora',
                'description' => 'Стоимость разработки сайтов, интернет-магазинов и порталов. Тарифы Nexora с прозрачным ценообразованием.',
                'content' => $pricingContent,
                'active' => true,
                'show_in_menu' => true,
                'show_in_footer' => true,
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 10,
                'footer_label' => 'Стоимость создания сайта',
                'menu_order' => 30,
                'menu_label' => 'Цены',
                'menu_type' => 'route',
                'seo_title' => 'Цены на разработку сайтов — Nexora',
                'seo_description' => 'Тарифы на создание сайтов, корпоративных порталов и интернет-магазинов. Стоимость от 890 BYN.',
            ],
            [
                'slug' => 'nav-team',
                'title' => 'Подход',
                'description' => null,
                'content' => null,
                'active' => true,
                'show_in_menu' => false,
                'menu_order' => 40,
                'menu_label' => 'Подход',
                'menu_type' => 'anchor',
                'menu_hash' => '#team',
            ],
            [
                'slug' => 'nav-contact',
                'title' => 'Контакты',
                'description' => null,
                'content' => null,
                'active' => true,
                'show_in_menu' => true,
                'menu_order' => 50,
                'menu_label' => 'Контакты',
                'menu_type' => 'anchor',
                'menu_hash' => '#contact',
            ],
            [
                'slug' => 'contacts',
                'title' => 'Контакты — Nexora',
                'description' => 'Контактная информация Nexora: телефон и email для связи с разработчиком.',
                'content' => $trixBody(
                    '<p><strong>Телефон:</strong> <a href="tel:+375291234567">+375 (29) 123-45-67</a></p>'.
                    '<p><strong>Email:</strong> <a href="mailto:hello@nexora.by">hello@nexora.by</a></p>'.
                    '<p>г. Минск</p>'
                ),
                'active' => true,
                'show_in_menu' => false,
                'show_in_footer' => true,
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 60,
                'footer_label' => 'Контакты',
                'menu_order' => 0,
                'menu_type' => 'route',
                'seo_title' => 'Контакты — Nexora',
                'seo_description' => 'Связаться с Nexora: телефон и email частного веб-разработчика.',
            ],
        ];

        $footerPages = [
            [
                'slug' => 'about',
                'title' => 'О разработчике — Nexora',
                'description' => 'О Nexora: опыт, подход и формат работы частного веб-разработчика.',
                'content' => $trixBody(
                    '<p><strong>Nexora</strong> — личный бренд частного веб-разработчика.</p>'.
                    '<p>Я создаю сайты и веб-приложения под ключ, работаю напрямую с заказчиком и отвечаю за результат лично: от структуры и интерфейса до запуска, аналитики и дальнейшей поддержки.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 10,
                'footer_label' => 'О разработчике',
            ],
            [
                'slug' => 'reviews',
                'title' => 'Отзывы — Nexora',
                'description' => 'Отзывы клиентов о работе Nexora.',
                'content' => $trixBody(
                    '<p>Отзывы клиентов скоро появятся здесь.</p>'.
                    '<p>Пока можно посмотреть портфолио и обсудить задачу напрямую через форму на главной странице.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 30,
                'footer_label' => 'Отзывы',
            ],
            [
                'slug' => 'faq',
                'title' => 'Вопрос-ответ — Nexora',
                'description' => 'Частые вопросы о разработке сайтов.',
                'content' => $trixBody(
                    '<h2>Частые вопросы</h2>'.
                    '<p><strong>Можно ли начать с небольшого сайта?</strong><br>Да. Часто лучше запустить первую версию быстро, проверить спрос и развивать проект поэтапно.</p>'.
                    '<p><strong>Работаете ли вы с готовыми сайтами?</strong><br>Да, я беру доработки, исправления, интеграции и техническое сопровождение существующих проектов.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 40,
                'footer_label' => 'Вопрос-ответ',
            ],
            [
                'slug' => 'blog',
                'title' => 'Блог — Nexora',
                'description' => 'Статьи о веб-разработке и digital.',
                'content' => $trixBody(
                    '<p>Блог скоро будет доступен.</p>'.
                    '<p>Здесь будут заметки о разработке, SEO, CMS, интеграциях и практических решениях для сайтов.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 50,
                'footer_label' => 'Статьи',
            ],
            [
                'slug' => 'sitemap',
                'title' => 'Карта сайта — Nexora',
                'description' => 'Карта сайта Nexora.',
                'content' => $trixBody(
                    '<p>Полный список основных разделов сайта:</p>'.
                    '<ul><li>Главная</li><li>Портфолио</li><li>Новости</li><li>Статьи</li><li>Цены</li><li>Контакты</li></ul>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SECTIONS,
                'footer_order' => 70,
                'footer_label' => 'Карта сайта',
            ],
            [
                'slug' => 'services-web',
                'title' => 'Создание сайтов — Nexora',
                'description' => 'Разработка сайтов под ключ.',
                'content' => $trixBody(
                    '<p>Создаю сайты, лендинги и порталы на Laravel, WordPress, 1C-Битрикс и других платформах.</p>'.
                    '<p>Подбираю решение под задачу: от простой посадочной страницы до сайта с личным кабинетом, каталогом и интеграциями.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 20,
                'footer_label' => 'Создание сайтов',
            ],
            [
                'slug' => 'services-mobile',
                'title' => 'Мобильные приложения — Nexora',
                'description' => 'Разработка мобильных приложений.',
                'content' => $trixBody(
                    '<p>Помогаю спроектировать мобильный сценарий и подготовить веб-основу для приложения.</p>'.
                    '<p>Если задачу можно решить адаптивным веб-интерфейсом или PWA, предложу более быстрый и экономичный путь.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 30,
                'footer_label' => 'Мобильные приложения',
            ],
            [
                'slug' => 'services-seo',
                'title' => 'Продвижение сайтов — Nexora',
                'description' => 'SEO и продвижение сайтов.',
                'content' => $trixBody(
                    '<p>Готовлю техническую основу для продвижения в Яндекс и Google: структуру страниц, метаданные, sitemap, ЧПУ и базовую оптимизацию скорости.</p>'.
                    '<p>Для дальнейшего роста можно подключать контент, аналитику и регулярные доработки.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 40,
                'footer_label' => 'Продвижение сайтов',
            ],
            [
                'slug' => 'services-design',
                'title' => 'Дизайн сайтов — Nexora',
                'description' => 'UI/UX дизайн сайтов.',
                'content' => $trixBody(
                    '<p>Проектирую интерфейсы, которые помогают пользователю быстро понять предложение и оставить заявку.</p>'.
                    '<p>Фокусируюсь на структуре, читабельности, адаптивности и аккуратном визуальном стиле.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 50,
                'footer_label' => 'Дизайн сайтов',
            ],
            [
                'slug' => 'services-refactor',
                'title' => 'Доработка сайтов — Nexora',
                'description' => 'Доработка и расширение функционала сайтов.',
                'content' => $trixBody(
                    '<p>Дорабатываю существующие сайты: добавляю новые разделы, формы, фильтры, личные кабинеты и интеграции.</p>'.
                    '<p>Перед началом смотрю код и предлагаю понятный план изменений без лишнего переписывания проекта.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 60,
                'footer_label' => 'Доработка сайтов',
            ],
            [
                'slug' => 'services-optimization',
                'title' => 'Оптимизация сайтов — Nexora',
                'description' => 'Оптимизация скорости и SEO сайтов.',
                'content' => $trixBody(
                    '<p>Провожу техническую оптимизацию сайта: скорость загрузки, базовые SEO-настройки, корректные метаданные, sitemap и проверка ошибок.</p>'.
                    '<p>Цель — сделать сайт быстрее, понятнее для поисковых систем и удобнее для пользователей.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 70,
                'footer_label' => 'Оптимизация сайтов',
            ],
            [
                'slug' => 'services-support',
                'title' => 'Поддержка сайтов — Nexora',
                'description' => 'Техническая поддержка сайтов.',
                'content' => $trixBody(
                    '<p>Беру сайты на техническое сопровождение: обновления, резервные копии, мониторинг, исправление ошибок и небольшие доработки.</p>'.
                    '<p>Формат поддержки можно подобрать под реальную нагрузку проекта.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 80,
                'footer_label' => 'Поддержка сайтов',
            ],
            [
                'slug' => 'services-hosting',
                'title' => 'Хостинг для сайтов — Nexora',
                'description' => 'Хостинг и размещение сайтов.',
                'content' => $trixBody(
                    '<p>Помогаю подобрать и настроить хостинг для сайта или веб-приложения.</p>'.
                    '<p>Настраиваю домен, SSL, окружение, деплой и базовые меры надёжности.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_SERVICES,
                'footer_order' => 90,
                'footer_label' => 'Хостинг для сайтов',
            ],
            [
                'slug' => 'privacy',
                'title' => 'Политика в отношении обработки персональных данных',
                'description' => 'Политика обработки персональных данных Nexora.',
                'content' => $trixBody(
                    '<p>Текст политики обработки персональных данных.</p>'.
                    '<p>Раздел можно отредактировать в Nova через Trix-редактор и заменить на финальную юридическую редакцию.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_LEGAL,
                'footer_order' => 10,
                'footer_label' => 'Политика в отношении обработки персональных данных',
            ],
            [
                'slug' => 'cookie-policy',
                'title' => 'Политика в отношении использования файлов cookie',
                'description' => 'Политика использования cookie.',
                'content' => $trixBody(
                    '<p>Текст политики использования файлов cookie.</p>'.
                    '<p>Раздел можно отредактировать в Nova через Trix-редактор и заменить на финальную юридическую редакцию.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_LEGAL,
                'footer_order' => 20,
                'footer_label' => 'Политика в отношении использования файлов cookie',
            ],
            [
                'slug' => 'terms',
                'title' => 'Пользовательское соглашение',
                'description' => 'Пользовательское соглашение Nexora.',
                'content' => $trixBody(
                    '<p>Текст пользовательского соглашения.</p>'.
                    '<p>Раздел можно отредактировать в Nova через Trix-редактор и заменить на финальную юридическую редакцию.</p>'
                ),
                'footer_group' => Page::FOOTER_GROUP_LEGAL,
                'footer_order' => 30,
                'footer_label' => 'Пользовательское соглашение',
            ],
        ];

        foreach ($footerPages as $footerPage) {
            $pages[] = array_merge([
                'active' => true,
                'show_in_menu' => false,
                'show_in_footer' => true,
                'menu_order' => 0,
                'menu_type' => 'route',
            ], $footerPage);
        }

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
