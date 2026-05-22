<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    homeUrl: {
        type: String,
        default: '/',
    },
    projectsUrl: {
        type: String,
        default: '/projects',
    },
    faviconUrl: {
        type: String,
        default: '/favicon.svg',
    },
    loginUrl: {
        type: String,
        default: '',
    },
    dashboardUrl: {
        type: String,
        default: '/nova',
    },
    isAuthenticated: {
        type: Boolean,
        default: false,
    },
    csrfToken: {
        type: String,
        default: '',
    },
    currentYear: {
        type: [String, Number],
        default: new Date().getFullYear(),
    },
});

const mobileMenuOpen = ref(false);

const authLink = computed(() => {
    if (props.isAuthenticated) {
        return {
            href: props.dashboardUrl,
            label: 'Панель',
        };
    }

    if (props.loginUrl) {
        return {
            href: props.loginUrl,
            label: 'Вход',
        };
    }

    return null;
});

const stats = [
    ['12+', 'специалистов в команде'],
    ['8', 'лет на рынке'],
    ['150+', 'реализованных проектов'],
];

const services = [
    ['01', 'Различные платформы', 'Создание сайтов на Laravel, WordPress, 1C-Битрикс и других CMS с учётом задач бизнеса.'],
    ['02', 'SEO и продвижение', 'Оптимизация под Яндекс и Google. Вывод в ТОП-10 — стабильный поток клиентов и рост прибыли.'],
    ['03', 'Интернет-магазины и порталы', 'Каталоги, личные кабинеты, биллинг, интеграции с CRM, складом и платёжными системами.'],
    ['04', 'Быстрый старт', 'Запуск на базе проверенных решений за 1–2 недели — оптимально по срокам и бюджету.'],
    ['05', 'Поддержка и развитие', 'Доработка, раскрутка, SMM, техническое сопровождение действующих проектов.'],
];

const cases = [
    {
        tag: 'Недвижимость',
        title: 'Портал объектов',
        client: 'Клиент: агентство недвижимости',
        points: ['Каталог объектов по направлениям', 'Внутренний биллинг и платные пакеты', 'Автообновление объявлений для агентств'],
        result: 'портал с посещаемостью до 30 тыс. пользователей в сутки.',
    },
    {
        tag: 'Финансы',
        title: 'Корпоративный портал',
        client: 'Клиент: страховая компания',
        points: ['Калькулятор взносов и онлайн-оплата', 'Кабинеты клиента, агента и брокера', 'Конструктор виджетов для банков'],
        result: 'единая платформа с разными уровнями доступа.',
    },
    {
        tag: 'Логистика',
        title: 'Доставка и таможня',
        client: 'Клиент: международная логистика',
        points: ['Оформление доставки онлайн', 'Интеграция со складом и таможней', 'Расчёт пошлин и стоимости доставки'],
        result: 'система до 500 одновременных подключений.',
    },
];

const benefits = [
    ['Договор и ТЗ', 'Подписание технического задания даёт уверенность, что результат будет именно таким, как вы ожидаете.'],
    ['Опыт 8+ лет', 'Команда постоянно совершенствует навыки и осваивает новые технологии в веб-разработке.'],
    ['150+ клиентов', 'Большинство заказчиков остаются с нами на сопровождение и развитие проектов.'],
    ['Сроки', '95% проектов запущены в срок или раньше оговорённой даты.'],
];

const team = [
    ['А', 'Алексей', 'Руководитель'],
    ['М', 'Мария', 'Project-менеджер'],
    ['Д', 'Дмитрий', 'PHP-разработчик'],
    ['Е', 'Елена', 'Frontend'],
    ['О', 'Ольга', 'Дизайнер'],
    ['И', 'Игорь', 'SEO-специалист'],
    ['К', 'Кирилл', 'Backend'],
    ['А', 'Анна', 'Аккаунт-менеджер'],
];

const clients = ['GoHome', 'PriorLife', 'ProStore', 'Микро Лизинг', 'Fine Adviser', 'USAPostLine', 'Айчына', 'Белджи', 'Невестам', 'Teploluxe', 'Haval', 'Йетти'];

const departments = [
    ['📈', 'SEO и маркетинг', 'Продвижение в ТОП поисковых систем и удержание позиций надолго.'],
    ['⚙️', 'Разработка', 'Воплощение проектов с нужным функционалом — стабильно и без сбоев.'],
    ['📱', 'Вёрстка', 'Удобство во всех браузерах и на мобильных устройствах.'],
    ['🎯', 'Менеджмент', 'Координация команды, сроки и выполнение поставленных задач.'],
    ['🎨', 'Дизайн', 'Визуал, который запоминается и удобен для посетителей сайта.'],
];

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
};
</script>

<template>
    <header class="landing-header">
        <div class="landing-container">
            <div class="landing-header__inner">
                <a :href="homeUrl" class="landing-logo">
                    <img :src="faviconUrl" alt="" class="landing-logo__icon" width="32" height="32">
                    Nex<span>ora</span>
                </a>

                <nav class="landing-nav" aria-label="Основное меню">
                    <a href="#services">Услуги</a>
                    <a :href="projectsUrl">Портфолио</a>
                    <a href="#team">Команда</a>
                    <a href="#contact">Контакты</a>
                </nav>

                <div class="landing-header__actions">
                    <a href="tel:+375291234567" class="landing-phone">+375 (29) 123-45-67</a>

                    <a v-if="authLink" :href="authLink.href" class="landing-auth">{{ authLink.label }}</a>

                    <a href="#contact" class="landing-btn landing-btn--primary">Обсудить проект</a>

                    <button
                        type="button"
                        class="landing-burger"
                        aria-label="Меню"
                        :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>

            <nav
                class="landing-mobile-nav"
                :class="{ 'is-open': mobileMenuOpen }"
                aria-label="Мобильное меню"
            >
                <a href="#services" @click="closeMobileMenu">Услуги</a>
                <a :href="projectsUrl" @click="closeMobileMenu">Портфолио</a>
                <a href="#team" @click="closeMobileMenu">Команда</a>
                <a href="#contact" @click="closeMobileMenu">Контакты</a>
                <a href="tel:+375291234567" @click="closeMobileMenu">+375 (29) 123-45-67</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="landing-hero">
            <div class="landing-container landing-hero__grid">
                <div>
                    <span class="landing-hero__eyebrow">Веб-студия</span>
                    <h1>Создание сайтов и цифровых продуктов</h1>
                    <p class="landing-hero__lead">
                        Разработка и сопровождение веб-проектов — важный шаг в развитии вашего бизнеса.
                        Мы заботимся об имидже компании и не используем шаблонные решения низкого качества.
                        Имидж — это долгосрочная инвестиция.
                    </p>
                    <div class="landing-hero__cta">
                        <a href="#contact" class="landing-btn landing-btn--primary">Заказать разработку</a>
                        <a :href="projectsUrl" class="landing-btn landing-btn--outline">Смотреть кейсы</a>
                    </div>
                </div>

                <div class="landing-stats">
                    <div v-for="stat in stats" :key="stat[1]" class="landing-stat">
                        <span class="landing-stat__value">{{ stat[0] }}</span>
                        <span class="landing-stat__label">{{ stat[1] }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="landing-section landing-section--alt">
            <div class="landing-container">
                <h2 class="landing-section__title">Услуги по разработке сайтов</h2>
                <p class="landing-section__subtitle">
                    Полный цикл: от проектирования и дизайна до запуска, SEO и технической поддержки.
                </p>

                <div class="landing-services">
                    <article v-for="service in services" :key="service[0]" class="landing-service">
                        <span class="landing-service__num">{{ service[0] }}</span>
                        <div>
                            <h3>{{ service[1] }}</h3>
                            <p>{{ service[2] }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="portfolio" class="landing-section">
            <div class="landing-container">
                <h2 class="landing-section__title">Автоматизация бизнес-процессов — наш профиль</h2>
                <p class="landing-section__subtitle">
                    Реальные кейсы: порталы, кабинеты клиентов, интеграции и высоконагруженные системы.
                </p>

                <div class="landing-portfolio">
                    <article v-for="item in cases" :key="item.title" class="landing-case">
                        <span class="landing-case__tag">{{ item.tag }}</span>
                        <h3>{{ item.title }}</h3>
                        <p class="landing-case__client">{{ item.client }}</p>
                        <ul>
                            <li v-for="point in item.points" :key="point">{{ point }}</li>
                        </ul>
                        <p class="landing-case__result"><strong>Результат:</strong> {{ item.result }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="landing-section landing-section--alt">
            <div class="landing-container">
                <h2 class="landing-section__title">Почему выбирают Nexora</h2>
                <p class="landing-section__subtitle">
                    Качественный сайт — лицо компании. Те, кто заботится об имидже, доверяют разработку нам.
                </p>

                <div class="landing-benefits">
                    <article v-for="benefit in benefits" :key="benefit[0]" class="landing-benefit">
                        <h3>{{ benefit[0] }}</h3>
                        <p>{{ benefit[1] }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="team" class="landing-section">
            <div class="landing-container">
                <h2 class="landing-section__title">Команда, которая создаст ваш проект</h2>
                <p class="landing-section__subtitle">
                    Разработчики, дизайнеры, SEO-специалисты и менеджеры — каждый отвечает за свой участок работы.
                </p>

                <div class="landing-team">
                    <article v-for="member in team" :key="`${member[1]}-${member[2]}`" class="landing-member">
                        <div class="landing-member__avatar">{{ member[0] }}</div>
                        <p class="landing-member__name">{{ member[1] }}</p>
                        <p class="landing-member__role">{{ member[2] }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="landing-section landing-section--alt">
            <div class="landing-container">
                <h2 class="landing-section__title">Нам доверяют</h2>
                <p class="landing-section__subtitle">Компании из e-commerce, финансов, недвижимости, ритейла и B2B.</p>

                <div class="landing-clients">
                    <span v-for="client in clients" :key="client" class="landing-client">{{ client }}</span>
                </div>
            </div>
        </section>

        <section class="landing-section">
            <div class="landing-container">
                <h2 class="landing-section__title">Каждый занимается своим делом</h2>
                <p class="landing-section__subtitle">Слаженная работа отделов — залог качественного результата.</p>

                <div class="landing-anatomy">
                    <article v-for="department in departments" :key="department[1]" class="landing-brain-item">
                        <div class="landing-brain-item__icon">{{ department[0] }}</div>
                        <div>
                            <h3>{{ department[1] }}</h3>
                            <p>{{ department[2] }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="contact" class="landing-section landing-section--alt">
            <div class="landing-container landing-contact">
                <div>
                    <h2 class="landing-section__title">Доверьте нам ваш проект</h2>
                    <p class="landing-section__subtitle" style="margin-bottom: 0;">
                        Оставьте заявку — свяжемся в удобное время и обсудим задачи, сроки и бюджет.
                    </p>
                </div>

                <form class="landing-form" action="#" method="post">
                    <input v-if="csrfToken" type="hidden" name="_token" :value="csrfToken">

                    <div class="landing-form__row landing-form__row--2">
                        <div>
                            <label for="name">Ваше имя</label>
                            <input type="text" id="name" name="name" required placeholder="Иван">
                        </div>
                        <div>
                            <label for="phone">Телефон</label>
                            <input type="tel" id="phone" name="phone" required placeholder="+375 (29) 000-00-00">
                        </div>
                    </div>
                    <div>
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required placeholder="mail@example.com">
                    </div>
                    <div>
                        <label for="message">Описание проекта</label>
                        <textarea id="message" name="message" placeholder="Расскажите о задаче…"></textarea>
                    </div>
                    <div>
                        <span style="display:block;margin-bottom:10px;font-size:0.85rem;font-weight:500;color:var(--nx-text-muted);">Как удобнее связаться?</span>
                        <div class="landing-form__channels">
                            <label><input type="radio" name="channel" value="email" checked> E-mail</label>
                            <label><input type="radio" name="channel" value="phone"> Телефон</label>
                            <label><input type="radio" name="channel" value="telegram"> Telegram</label>
                            <label><input type="radio" name="channel" value="viber"> Viber</label>
                        </div>
                    </div>
                    <button type="submit" class="landing-btn landing-btn--primary">Отправить заявку</button>
                    <p class="landing-form__note">* Мы делаем проекты, которые работают на ваш бизнес: продают, приносят прибыль и укрепляют имидж.</p>
                </form>
            </div>
        </section>
    </main>

    <footer class="landing-footer">
        <div class="landing-container">
            <p>&copy; {{ currentYear }} Nexora. Разработка сайтов и веб-приложений.</p>
        </div>
    </footer>
</template>
