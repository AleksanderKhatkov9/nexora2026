<script setup>
import { computed, ref } from 'vue';
import { RouterLink } from 'vue-router';
import ContactForm from '../contact/ContactForm.vue';
import AsyncState from '../ui/AsyncState.vue';
import { useCmsPage } from '../../composables/useCmsPage.js';
import { useSiteApi } from '../../composables/useInjections.js';

const api = useSiteApi();

const { loading, error, page } = useCmsPage(
    () => api.pages.getHome(),
    { errorMessage: 'Не удалось загрузить данные главной страницы.' },
);

const sections = computed(() => page.value?.content ?? null);
const clientSliderItems = computed(() => sections.value?.clients ?? []);
const processStages = computed(() => sections.value?.team ?? []);

const processMeta = [
    {
        status: 'Старт проекта',
        color: '#f97316',
        soft: 'rgba(249, 115, 22, 0.12)',
    },
    {
        status: 'UX-схема',
        color: '#2563eb',
        soft: 'rgba(37, 99, 235, 0.12)',
    },
    {
        status: 'Production',
        color: '#16a34a',
        soft: 'rgba(22, 163, 74, 0.12)',
    },
    {
        status: 'Quality check',
        color: '#7c3aed',
        soft: 'rgba(124, 58, 237, 0.12)',
    },
    {
        status: 'Релиз',
        color: '#0891b2',
        soft: 'rgba(8, 145, 178, 0.12)',
    },
];

const heroStages = [
    {
        label: 'Аналитика',
        title: 'Разбираю задачу лично',
        text: 'Вникаю в продукт, аудиторию и цели бизнеса до первого прототипа.',
    },
    {
        label: 'Дизайн',
        title: 'Собираю живой интерфейс',
        text: 'Прорабатываю визуальный ритм, сценарии и состояния ключевых экранов.',
    },
    {
        label: 'Запуск',
        title: 'Довожу до результата',
        text: 'Подключаю интеграции, аналитику и помогаю улучшать показатели после релиза.',
    },
];

const activeHeroStage = ref(0);
const activeProcessStage = ref(0);
const heroTiltStyle = ref({});

const currentHeroStage = computed(() => heroStages[activeHeroStage.value]);
const processItems = computed(() => processStages.value.map((stage, index) => ({
    ...stage,
    ...(processMeta[index] ?? processMeta[processMeta.length - 1]),
})));
const currentProcessStage = computed(() => processItems.value[activeProcessStage.value] ?? processItems.value[0]);

function setHeroStage(index) {
    activeHeroStage.value = index;
}

function setProcessStage(index) {
    activeProcessStage.value = index;
}

function handleHeroPointerMove(event) {
    const bounds = event.currentTarget.getBoundingClientRect();
    const x = (event.clientX - bounds.left) / bounds.width - 0.5;
    const y = (event.clientY - bounds.top) / bounds.height - 0.5;

    heroTiltStyle.value = {
        transform: `perspective(900px) rotateX(${y * -6}deg) rotateY(${x * 8}deg) translateY(-4px)`,
    };
}

function resetHeroTilt() {
    heroTiltStyle.value = {};
}
</script>

<template>
    <main>
        <AsyncState :loading="loading" :error="error" loading-text="Загрузка…">
            <template v-if="sections">
                <section class="landing-hero">
                    <div class="landing-container landing-hero__grid">
                        <div class="landing-hero__copy">
                            <span class="landing-hero__eyebrow">{{ sections.hero?.eyebrow }}</span>
                            <h1>{{ sections.hero?.title }}</h1>
                            <p class="landing-hero__lead">{{ sections.hero?.lead }}</p>
                            <div class="landing-hero__cta">
                                <RouterLink :to="{ name: 'home', hash: '#contact' }" class="landing-btn landing-btn--primary">
                                    Заказать разработку
                                </RouterLink>
                                <RouterLink :to="{ name: 'projects' }" class="landing-btn landing-btn--outline">
                                    Смотреть кейсы
                                </RouterLink>
                            </div>
                        </div>

                        <div
                            class="landing-hero__showcase"
                            :style="heroTiltStyle"
                            @pointermove="handleHeroPointerMove"
                            @pointerleave="resetHeroTilt"
                        >
                            <div class="landing-dashboard">
                                <div class="landing-dashboard__top">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <div class="landing-dashboard__body">
                                    <p class="landing-dashboard__eyebrow">Проект в работе</p>
                                    <h2>{{ currentHeroStage.title }}</h2>
                                    <p>{{ currentHeroStage.text }}</p>

                                    <div class="landing-dashboard__progress" aria-hidden="true">
                                        <span :style="{ width: `${(activeHeroStage + 1) * 33.34}%` }"></span>
                                    </div>

                                    <div class="landing-dashboard__steps" role="tablist" aria-label="Этапы разработки">
                                        <button
                                            v-for="(stage, index) in heroStages"
                                            :key="stage.label"
                                            type="button"
                                            :class="{ 'is-active': activeHeroStage === index }"
                                            role="tab"
                                            :aria-selected="activeHeroStage === index ? 'true' : 'false'"
                                            @click="setHeroStage(index)"
                                        >
                                            {{ stage.label }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="landing-stats">
                                <div v-for="stat in sections.stats" :key="stat.label" class="landing-stat">
                                    <span class="landing-stat__value">{{ stat.value }}</span>
                                    <span class="landing-stat__label">{{ stat.label }}</span>
                                </div>
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
                            <article v-for="service in sections.services" :key="service.num" class="landing-service">
                                <span class="landing-service__num">{{ service.num }}</span>
                                <div>
                                    <h3>{{ service.title }}</h3>
                                    <p>{{ service.text }}</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section id="portfolio" class="landing-section">
                    <div class="landing-container">
                        <h2 class="landing-section__title">Автоматизация бизнес-процессов — мой профиль</h2>
                        <p class="landing-section__subtitle">
                            Реальные кейсы: порталы, кабинеты клиентов, интеграции и сложные веб-системы.
                        </p>

                        <div class="landing-portfolio">
                            <article v-for="item in sections.cases" :key="item.title" class="landing-case">
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
                        <h2 class="landing-section__title">Почему со мной удобно работать</h2>
                        <p class="landing-section__subtitle">
                            Вы общаетесь напрямую с разработчиком: без менеджерской прослойки, лишних согласований и студийной наценки.
                        </p>

                        <div class="landing-benefits">
                            <article v-for="benefit in sections.benefits" :key="benefit.title" class="landing-benefit">
                                <h3>{{ benefit.title }}</h3>
                                <p>{{ benefit.text }}</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section v-if="processStages.length" class="landing-section">
                    <div class="landing-container">
                        <h2 class="landing-section__title">Этапы создания приложения</h2>
                        <p class="landing-section__subtitle">
                            От идеи до запуска: каждый этап понятен по задачам, результату и следующему шагу.
                        </p>

                        <div class="landing-process-shell">
                            <div
                                v-if="currentProcessStage"
                                class="landing-process-preview"
                                :style="{
                                    '--stage-color': currentProcessStage.color,
                                    '--stage-soft': currentProcessStage.soft,
                                }"
                            >
                                <div class="landing-process-preview__copy">
                                    <span class="landing-process-preview__status">
                                        {{ currentProcessStage.status }}
                                    </span>
                                    <h3>{{ currentProcessStage.name }}</h3>
                                    <p>{{ currentProcessStage.role }}</p>
                                </div>

                            </div>

                            <div class="landing-process" role="tablist" aria-label="Процесс создания приложения">
                                <button
                                    v-for="(stage, index) in processItems"
                                    :key="stage.initial"
                                    type="button"
                                    class="landing-process__stage"
                                    :class="{ 'is-active': activeProcessStage === index }"
                                    :style="{ '--stage-color': stage.color, '--stage-soft': stage.soft }"
                                    role="tab"
                                    :aria-selected="activeProcessStage === index ? 'true' : 'false'"
                                    @click="setProcessStage(index)"
                                >
                                    <span class="landing-process__num">{{ stage.initial }}</span>
                                    <span class="landing-process__content">
                                        <span class="landing-process__status">{{ stage.status }}</span>
                                        <span class="landing-process__title">{{ stage.name }}</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="landing-section landing-section--alt">
                    <div class="landing-container">
                        <h2 class="landing-section__title">Мне доверяют</h2>
                        <p class="landing-section__subtitle">Проекты из e-commerce, финансов, недвижимости, ритейла и B2B.</p>

                        <div v-if="clientSliderItems.length" class="landing-clients-slider" aria-label="Компании и проекты">
                            <div class="landing-clients-slider__track">
                                <div
                                    v-for="copyIndex in 2"
                                    :key="`clients-copy-${copyIndex}`"
                                    class="landing-clients"
                                    aria-hidden="true"
                                >
                                    <span
                                        v-for="client in clientSliderItems"
                                        :key="`${copyIndex}-${client}`"
                                        class="landing-client"
                                    >
                                        {{ client }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="landing-section">
                    <div class="landing-container">
                        <h2 class="landing-section__title">Как я веду проект</h2>
                        <p class="landing-section__subtitle">Прозрачный процесс без передачи задач между разными исполнителями.</p>

                        <div class="landing-anatomy">
                            <article v-for="department in sections.departments" :key="department.title" class="landing-brain-item">
                                <div class="landing-brain-item__icon">{{ department.icon }}</div>
                                <div>
                                    <h3>{{ department.title }}</h3>
                                    <p>{{ department.text }}</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </section>

                <section id="contact" class="landing-section landing-section--alt">
                    <div class="landing-container landing-contact">
                        <div>
                            <h2 class="landing-section__title">Расскажите мне о проекте</h2>
                            <p class="landing-section__subtitle" style="margin-bottom: 0;">
                                Оставьте заявку — я свяжусь в удобное время и обсудим задачи, сроки и бюджет.
                            </p>
                        </div>

                        <ContactForm />
                    </div>
                </section>
            </template>
        </AsyncState>
    </main>
</template>

<style scoped>
:deep(.landing-state) {
    padding: 120px 0 80px;
}
</style>
