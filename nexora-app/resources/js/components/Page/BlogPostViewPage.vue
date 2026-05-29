<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { RouterLink } from 'vue-router';
import AsyncState from '../ui/AsyncState.vue';
import { useCmsPage } from '../../composables/useCmsPage.js';
import { useSiteApi } from '../../composables/useInjections.js';
import { sanitizeHtml } from '../../services/sanitizeHtml.js';

const props = defineProps({
    kind: {
        type: String,
        required: true,
        validator: (value) => ['news', 'article'].includes(value),
    },
});

const route = useRoute();
const api = useSiteApi();

const isNews = computed(() => props.kind === 'news');
const listRouteName = computed(() => (isNews.value ? 'news' : 'articles'));
const listLabel = computed(() => (isNews.value ? 'Новости' : 'Статьи'));
const notFoundMessage = computed(() => (isNews.value ? 'Новость не найдена.' : 'Статья не найдена.'));

const { loading, error, page: post } = useCmsPage(
    () => (isNews.value
        ? api.blog.getNewsBySlug(route.params.slug)
        : api.blog.getArticleBySlug(route.params.slug)),
    {
        watchSource: () => [route.params.slug, props.kind],
        notFoundMessage: () => notFoundMessage.value,
        errorMessage: () => (isNews.value ? 'Не удалось загрузить новость.' : 'Не удалось загрузить статью.'),
    },
);

const safeContent = computed(() => sanitizeHtml(post.value?.content));
</script>

<template>
    <main class="landing-main">
        <AsyncState :loading="loading" :error="error" loading-text="Загрузка…">
            <template #error-actions>
                <RouterLink :to="{ name: listRouteName }" class="landing-btn landing-btn--outline landing-state__back">
                    ← К {{ listLabel.toLowerCase() }}
                </RouterLink>
            </template>

            <template v-if="post">
                <section class="landing-page-head">
                    <div class="landing-container">
                        <nav class="landing-breadcrumb" aria-label="Хлебные крошки">
                            <RouterLink :to="{ name: 'home' }">Создание сайтов</RouterLink>
                            <span class="landing-breadcrumb__sep">/</span>
                            <RouterLink :to="{ name: listRouteName }">{{ listLabel }}</RouterLink>
                            <span class="landing-breadcrumb__sep">/</span>
                            <span class="landing-breadcrumb__current">{{ post.title }}</span>
                        </nav>

                        <div class="landing-blog-view__meta">
                            <time v-if="post.published_at_formatted" :datetime="post.published_at">
                                {{ post.published_at_formatted }}
                            </time>
                            <span v-if="post.author">{{ post.author }}</span>
                        </div>

                        <h1>{{ post.title }}</h1>

                        <p v-if="post.excerpt" class="landing-page-intro">
                            {{ post.excerpt }}
                        </p>
                    </div>
                </section>

                <section class="landing-blog-view">
                    <div class="landing-container">
                        <div v-if="post.cover_image" class="landing-blog-view__cover">
                            <img :src="post.cover_image" :alt="post.title">
                        </div>

                        <div
                            v-if="safeContent"
                            class="landing-blog-view__content landing-rich-text"
                            v-html="safeContent"
                        />

                        <div class="landing-blog-view__actions">
                            <RouterLink :to="{ name: listRouteName }" class="landing-btn landing-btn--outline">
                                ← К {{ listLabel.toLowerCase() }}
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

.landing-blog-view__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
    font-size: 0.9rem;
    color: var(--nx-text-muted);
}

.landing-blog-view {
    padding-bottom: 80px;
}

.landing-blog-view__cover {
    margin-bottom: 32px;
    border-radius: var(--nx-radius);
    overflow: hidden;
    border: 1px solid var(--nx-border);
}

.landing-blog-view__cover img {
    display: block;
    width: 100%;
    height: auto;
}

.landing-blog-view__content {
    max-width: 820px;
    margin-bottom: 32px;
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--nx-text-muted);
}

.landing-blog-view__content p {
    margin: 0;
    white-space: pre-line;
}

.landing-blog-view__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
</style>
