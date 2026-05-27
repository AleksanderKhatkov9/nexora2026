<script setup>
import { RouterLink } from 'vue-router';
import AsyncState from '../ui/AsyncState.vue';
import { useBlogFeed } from '../../composables/useBlogFeed.js';

const { loading, error, pageMeta, posts } = useBlogFeed('news');
</script>

<template>
    <main class="landing-main">
        <AsyncState :loading="loading" :error="error" loading-text="Загрузка новостей…">
            <section class="landing-page-head">
                <div class="landing-container">
                    <nav class="landing-breadcrumb" aria-label="Хлебные крошки">
                        <RouterLink :to="{ name: 'home' }">Создание сайтов</RouterLink>
                        <span class="landing-breadcrumb__sep">/</span>
                        <span class="landing-breadcrumb__current">Новости</span>
                    </nav>

                    <h1>{{ pageMeta?.title || 'Новости' }}</h1>
                    <p class="landing-page-intro">
                        {{ pageMeta?.description }}
                    </p>
                </div>
            </section>

            <section class="landing-blog-section">
                <div class="landing-container">
                    <p v-if="posts.length === 0" class="landing-blog-empty">
                        Новостей пока нет.
                    </p>

                    <div v-else class="landing-blog-grid">
                        <article v-for="post in posts" :key="post.id" class="landing-blog-card">
                            <div class="landing-blog-card__thumb" aria-hidden="true">
                                <img
                                    v-if="post.cover_image"
                                    :src="post.cover_image"
                                    :alt="post.title"
                                    class="landing-blog-card__image"
                                >
                                <span v-else class="landing-blog-card__placeholder">N</span>
                            </div>
                            <div class="landing-blog-card__body">
                                <div class="landing-blog-card__meta">
                                    <time v-if="post.published_at_formatted" :datetime="post.published_at">
                                        {{ post.published_at_formatted }}
                                    </time>
                                    <span v-if="post.author">{{ post.author }}</span>
                                </div>
                                <h2 class="landing-blog-card__title">{{ post.title }}</h2>
                                <p v-if="post.excerpt" class="landing-blog-card__excerpt">
                                    {{ post.excerpt }}
                                </p>
                                <RouterLink
                                    :to="{ name: 'news.view', params: { slug: post.slug } }"
                                    class="landing-blog-card__link"
                                >
                                    Читать новость →
                                </RouterLink>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        </AsyncState>
    </main>
</template>

<style scoped>
.landing-blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
}

.landing-blog-card {
    display: flex;
    flex-direction: column;
    border: 1px solid var(--nx-border);
    border-radius: var(--nx-radius);
    overflow: hidden;
    background: var(--nx-bg-elevated);
}

.landing-blog-card__thumb {
    aspect-ratio: 16 / 9;
    background: linear-gradient(135deg, var(--nx-accent-soft) 0%, var(--nx-bg) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.landing-blog-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.landing-blog-card__placeholder {
    font-family: var(--nx-font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--nx-accent);
}

.landing-blog-card__body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.landing-blog-card__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 10px;
    font-size: 0.85rem;
    color: var(--nx-text-muted);
}

.landing-blog-card__title {
    margin: 0 0 12px;
    font-size: 1.15rem;
    line-height: 1.35;
}

.landing-blog-card__excerpt {
    margin: 0 0 16px;
    flex: 1;
    font-size: 0.92rem;
    color: var(--nx-text-muted);
    line-height: 1.55;
}

.landing-blog-card__link {
    margin-top: auto;
    font-weight: 600;
    color: var(--nx-accent);
    text-decoration: none;
}

.landing-blog-empty {
    margin: 0;
    padding: 24px;
    text-align: center;
    color: var(--nx-text-muted);
    background: var(--nx-bg-elevated);
    border: 1px dashed var(--nx-border);
    border-radius: var(--nx-radius);
}
</style>
