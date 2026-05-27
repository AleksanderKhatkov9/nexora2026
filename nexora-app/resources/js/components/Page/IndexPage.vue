<script setup>
import { computed } from 'vue';
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
</script>

<template>
    <main>
        <AsyncState :loading="loading" :error="error" loading-text="Загрузка…">
            <template v-if="sections">
                <section class="landing-hero">
                    <div class="landing-container landing-hero__grid">
                        <div>
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

                        <div class="landing-stats">
                            <div v-for="stat in sections.stats" :key="stat.label" class="landing-stat">
                                <span class="landing-stat__value">{{ stat.value }}</span>
                                <span class="landing-stat__label">{{ stat.label }}</span>
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
                        <h2 class="landing-section__title">Автоматизация бизнес-процессов — наш профиль</h2>
                        <p class="landing-section__subtitle">
                            Реальные кейсы: порталы, кабинеты клиентов, интеграции и высоконагруженные системы.
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
                        <h2 class="landing-section__title">Почему выбирают Nexora</h2>
                        <p class="landing-section__subtitle">
                            Качественный сайт — лицо компании. Те, кто заботится об имидже, доверяют разработку нам.
                        </p>

                        <div class="landing-benefits">
                            <article v-for="benefit in sections.benefits" :key="benefit.title" class="landing-benefit">
                                <h3>{{ benefit.title }}</h3>
                                <p>{{ benefit.text }}</p>
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
                            <article
                                v-for="member in sections.team"
                                :key="`${member.name}-${member.role}`"
                                class="landing-member"
                            >
                                <div class="landing-member__avatar">{{ member.initial }}</div>
                                <p class="landing-member__name">{{ member.name }}</p>
                                <p class="landing-member__role">{{ member.role }}</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section class="landing-section landing-section--alt">
                    <div class="landing-container">
                        <h2 class="landing-section__title">Нам доверяют</h2>
                        <p class="landing-section__subtitle">Компании из e-commerce, финансов, недвижимости, ритейла и B2B.</p>

                        <div class="landing-clients">
                            <span v-for="client in sections.clients" :key="client" class="landing-client">{{ client }}</span>
                        </div>
                    </div>
                </section>

                <section class="landing-section">
                    <div class="landing-container">
                        <h2 class="landing-section__title">Каждый занимается своим делом</h2>
                        <p class="landing-section__subtitle">Слаженная работа отделов — залог качественного результата.</p>

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
                            <h2 class="landing-section__title">Доверьте нам ваш проект</h2>
                            <p class="landing-section__subtitle" style="margin-bottom: 0;">
                                Оставьте заявку — свяжемся в удобное время и обсудим задачи, сроки и бюджет.
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
