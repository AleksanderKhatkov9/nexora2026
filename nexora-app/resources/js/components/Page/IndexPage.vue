<script setup>
import { inject, onMounted, onUnmounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { fetchHomePage, submitOrder } from '../../api/site';

const appConfig = inject('appConfig');

const loading = ref(true);
const error = ref('');
const page = ref(null);
const sections = ref(null);

const form = ref({
    name: '',
    phone: '',
    email: '',
    message: '',
    channel: 'email',
});
const formSubmitting = ref(false);
const formSuccess = ref('');
const formError = ref('');
const formIsSuccess = ref(false);

const SUCCESS_RESET_MS = 4000;
let successResetTimer = null;

const clearSuccessState = () => {
    formSuccess.value = '';
    formIsSuccess.value = false;
    successResetTimer = null;
};

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

const resetForm = () => {
    form.value = {
        name: '',
        phone: '',
        email: '',
        message: '',
        channel: 'email',
    };
};

const handleSubmit = async () => {
    formSubmitting.value = true;
    clearSuccessState();
    formError.value = '';

    if (successResetTimer) {
        clearTimeout(successResetTimer);
        successResetTimer = null;
    }

    try {
        const response = await submitOrder({ ...form.value });
        formSuccess.value = response.message || 'Данные успешно отправлены!';
        formIsSuccess.value = true;
        resetForm();

        successResetTimer = setTimeout(clearSuccessState, SUCCESS_RESET_MS);
    } catch (e) {
        const validationMessage = e.response?.data?.errors
            ? Object.values(e.response.data.errors).flat().join(' ')
            : null;
        formError.value = validationMessage || 'Не удалось отправить заявку. Попробуйте позже.';
        console.error(e);
    } finally {
        formSubmitting.value = false;
    }
};

onMounted(loadPage);

onUnmounted(() => {
    if (successResetTimer) {
        clearTimeout(successResetTimer);
    }
});
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

                    <form
                        class="landing-form"
                        :class="{ 'landing-form--success': formIsSuccess }"
                        @submit.prevent="handleSubmit"
                    >
                        <div class="landing-form__row landing-form__row--2">
                            <div>
                                <label for="name">Ваше имя</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    placeholder="Иван"
                                    :disabled="formSubmitting"
                                >
                            </div>
                            <div>
                                <label for="phone">Телефон</label>
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    required
                                    placeholder="+375 (29) 000-00-00"
                                    :disabled="formSubmitting"
                                >
                            </div>
                        </div>
                        <div>
                            <label for="email">E-mail</label>
                            <input
                                v-model="form.email"
                                type="email"
                                id="email"
                                name="email"
                                required
                                placeholder="mail@example.com"
                                :disabled="formSubmitting"
                            >
                        </div>
                        <div>
                            <label for="message">Описание проекта</label>
                            <textarea
                                v-model="form.message"
                                id="message"
                                name="message"
                                placeholder="Расскажите о задаче…"
                                :disabled="formSubmitting"
                            ></textarea>
                        </div>
                        <!-- <div>
                            <span style="display:block;margin-bottom:10px;font-size:0.85rem;font-weight:500;color:var(--nx-text-muted);">
                                Как удобнее связаться?
                            </span>
                            <div class="landing-form__channels">
                                <label><input v-model="form.channel" type="radio" name="channel" value="email" :disabled="formSubmitting"> E-mail</label>
                                <label><input v-model="form.channel" type="radio" name="channel" value="phone" :disabled="formSubmitting"> Телефон</label>
                                <label><input v-model="form.channel" type="radio" name="channel" value="telegram" :disabled="formSubmitting"> Telegram</label>
                                <label><input v-model="form.channel" type="radio" name="channel" value="viber" :disabled="formSubmitting"> Viber</label>
                            </div>
                        </div> -->
                        <button
                            type="submit"
                            class="landing-btn landing-btn--primary"
                            :class="{ 'landing-btn--success': formIsSuccess }"
                            :disabled="formSubmitting || formIsSuccess"
                        >
                            {{
                                formSubmitting
                                    ? 'Отправка…'
                                    : formIsSuccess
                                        ? 'Данные отправлены!'
                                        : 'Отправить заявку'
                            }}
                        </button>
                        <p v-if="formSuccess" class="landing-form__feedback landing-form__feedback--success" role="status">
                            {{ formSuccess }}
                        </p>
                        <p v-if="formError" class="landing-form__feedback landing-form__feedback--error">
                            {{ formError }}
                        </p>
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

.landing-form__feedback {
    margin: 12px 0 0;
    font-size: 0.9rem;
}

.landing-form__feedback--success {
    color: #067647;
}

.landing-form__feedback--error {
    color: #b42318;
}

.landing-form {
    transition: background-color 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
}

.landing-form--success {
    border-color: #067647;
    background-color: rgba(6, 118, 71, 0.08);
    box-shadow: 0 0 0 1px rgba(6, 118, 71, 0.15);
}

.landing-btn--success {
    background-color: #067647 !important;
    border-color: #067647 !important;
    color: #fff !important;
}
</style>
