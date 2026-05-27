<script setup>
import { inject, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { fetchHomePage } from '../../api/site';

const appConfig = inject('appConfig');

const loading = ref(true);
const error = ref('');
const page = ref(null);
const sections = ref(null);

const loadPage = async () => {
    loading.value = true;
    error.value = '';

    try {
        page.value = await fetchHomePage();
        sections.value = page.value?.content ?? null;

        if (page.value?.seo_title) {
            document.title = page.value.seo_title;
        }

        if (page.value?.seo_description) {
            const meta = document.querySelector('meta[name="description"]');
            if (meta) {
                meta.setAttribute('content', page.value.seo_description);
            }
        }
    } catch (e) {
        error.value = 'Не удалось загрузить данные главной страницы.';
        console.error(e);
    } finally {
        loading.value = false;
    }
};

onMounted(loadPage);
</script>

<template>
    <main>
        <div v-if="loading" class="landing-container landing-state">
            <p>Загрузка…</p>
        </div>

        <div v-else-if="error" class="landing-container landing-state landing-state--error">
            <p>{{ error }}</p>
        </div>

        <template v-else-if="sections">
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

                    <form class="landing-form" action="#" method="post">
                        <input v-if="appConfig.csrfToken" type="hidden" name="_token" :value="appConfig.csrfToken">

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
                            <span style="display:block;margin-bottom:10px;font-size:0.85rem;font-weight:500;color:var(--nx-text-muted);">
                                Как удобнее связаться?
                            </span>
                            <div class="landing-form__channels">
                                <label><input type="radio" name="channel" value="email" checked> E-mail</label>
                                <label><input type="radio" name="channel" value="phone"> Телефон</label>
                                <label><input type="radio" name="channel" value="telegram"> Telegram</label>
                                <label><input type="radio" name="channel" value="viber"> Viber</label>
                            </div>
                        </div>
                        <button type="submit" class="landing-btn landing-btn--primary">Отправить заявку</button>
                        <p class="landing-form__note">
                            * Мы делаем проекты, которые работают на ваш бизнес: продают, приносят прибыль и укрепляют имидж.
                        </p>
                    </form>
                </div>
            </section>
        </template>
    </main>
</template>

<style scoped>
.landing-state {
    padding: 120px 0 80px;
    text-align: center;
    color: var(--nx-text-muted);
}

.landing-state--error {
    color: #b42318;
}
</style>
