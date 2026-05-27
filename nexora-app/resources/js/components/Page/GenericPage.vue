<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { fetchPageBySlug } from '../../api/site';

const route = useRoute();
const page = ref(null);
const loading = ref(true);
const error = ref('');

const loadPage = async () => {
    const slug = route.params.slug;

    loading.value = true;
    error.value = '';
    page.value = null;

    try {
        page.value = await fetchPageBySlug(slug);

        const title = page.value?.seo_title || page.value?.title;
        if (title) {
            document.title = title;
        }

        if (page.value?.seo_description) {
            const meta = document.querySelector('meta[name="description"]');
            if (meta) {
                meta.setAttribute('content', page.value.seo_description);
            }
        }
    } catch (e) {
        if (e.response?.status === 404) {
            error.value = 'Страница не найдена.';
        } else {
            error.value = 'Не удалось загрузить страницу.';
        }
        console.error(e);
    } finally {
        loading.value = false;
    }
};

onMounted(loadPage);
watch(() => route.params.slug, loadPage);
</script>

<template>
    <main class="landing-main">
        <div v-if="loading" class="landing-container landing-state">
            <p>Загрузка…</p>
        </div>

        <div v-else-if="error" class="landing-container landing-state landing-state--error">
            <p>{{ error }}</p>
            <RouterLink :to="{ name: 'home' }" class="landing-btn landing-btn--outline landing-state__back">
                ← На главную
            </RouterLink>
        </div>

        <template v-else-if="page">
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
    </main>
</template>

<style scoped>
.landing-state {
    padding: 48px 0;
    text-align: center;
    color: var(--nx-text-muted);
}

.landing-state--error {
    color: #b42318;
}

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
