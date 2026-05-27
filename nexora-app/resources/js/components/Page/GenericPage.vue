<script setup>
import { useRoute } from 'vue-router';
import { RouterLink } from 'vue-router';
import AsyncState from '../ui/AsyncState.vue';
import { useCmsPage } from '../../composables/useCmsPage.js';
import { useSiteApi } from '../../composables/useInjections.js';

const route = useRoute();
const api = useSiteApi();

const { loading, error, page } = useCmsPage(
    () => api.pages.getBySlug(route.params.slug),
    {
        watchSource: () => route.params.slug,
        notFoundMessage: 'Страница не найдена.',
        errorMessage: 'Не удалось загрузить страницу.',
    },
);
</script>

<template>
    <main class="landing-main">
        <AsyncState :loading="loading" :error="error">
            <template #error-actions>
                <RouterLink :to="{ name: 'home' }" class="landing-btn landing-btn--outline landing-state__back">
                    ← На главную
                </RouterLink>
            </template>

            <template v-if="page">
                <section class="landing-page-head">
                    <div class="landing-container">
                        <nav class="landing-breadcrumb" aria-label="Хлебные крошки">
                            <RouterLink :to="{ name: 'home' }">Создание сайтов</RouterLink>
                            <span class="landing-breadcrumb__sep">/</span>
                            <span class="landing-breadcrumb__current">{{ page.title }}</span>
                        </nav>

                        <h1>{{ page.title }}</h1>

                        <p v-if="page.description" class="landing-page-intro">
                            {{ page.description }}
                        </p>
                    </div>
                </section>

                <section class="landing-generic-page">
                    <div class="landing-container">
                        <img
                            v-if="page.image"
                            :src="page.image"
                            :alt="page.title"
                            class="landing-generic-page__image"
                        >

                        <div v-if="page.content?.body" class="landing-generic-page__body">
                            <p>{{ page.content.body }}</p>
                        </div>

                        <div v-else-if="page.content && Object.keys(page.content).length" class="landing-generic-page__json">
                            <pre>{{ JSON.stringify(page.content, null, 2) }}</pre>
                        </div>

                        <div class="landing-generic-page__actions">
                            <RouterLink :to="{ name: 'home', hash: '#contact' }" class="landing-btn landing-btn--primary">
                                Оставить заявку
                            </RouterLink>
                        </div>
                    </div>
                </section>
            </template>
        </AsyncState>
    </main>
</template>

<style scoped>
.landing-state__back {
    display: inline-flex;
    margin-top: 20px;
}

.landing-generic-page {
    padding-bottom: 80px;
}

.landing-generic-page__image {
    width: 100%;
    max-height: 420px;
    object-fit: cover;
    border-radius: var(--nx-radius);
    margin-bottom: 32px;
    border: 1px solid var(--nx-border);
}

.landing-generic-page__body {
    max-width: 820px;
    margin-bottom: 32px;
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--nx-text-muted);
}

.landing-generic-page__body p {
    margin: 0;
    white-space: pre-line;
}

.landing-generic-page__json {
    margin-bottom: 32px;
    padding: 16px;
    background: var(--nx-bg-elevated);
    border-radius: var(--nx-radius);
    overflow-x: auto;
}

.landing-generic-page__json pre {
    margin: 0;
    font-size: 0.85rem;
}

.landing-generic-page__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
</style>
