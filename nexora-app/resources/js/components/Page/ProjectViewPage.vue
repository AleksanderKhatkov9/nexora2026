<script setup>
import { useRoute } from 'vue-router';
import { RouterLink } from 'vue-router';
import AsyncState from '../ui/AsyncState.vue';
import { useCmsPage } from '../../composables/useCmsPage.js';
import { useSiteApi } from '../../composables/useInjections.js';

const route = useRoute();
const api = useSiteApi();

const { loading, error, page: project } = useCmsPage(
    () => api.projects.getById(route.params.id),
    {
        watchSource: () => route.params.id,
        notFoundMessage: 'Проект не найден.',
        errorMessage: 'Не удалось загрузить проект.',
    },
);
</script>

<template>
    <main class="landing-main">
        <AsyncState :loading="loading" :error="error" loading-text="Загрузка проекта…">
            <template #error-actions>
                <RouterLink :to="{ name: 'projects' }" class="landing-btn landing-btn--outline landing-state__back">
                    ← К портфолио
                </RouterLink>
            </template>

            <template v-if="project">
                <section class="landing-page-head">
                    <div class="landing-container">
                        <nav class="landing-breadcrumb" aria-label="Хлебные крошки">
                            <RouterLink :to="{ name: 'home' }">Создание сайтов</RouterLink>
                            <span class="landing-breadcrumb__sep">/</span>
                            <RouterLink :to="{ name: 'projects' }">Портфолио</RouterLink>
                            <span class="landing-breadcrumb__sep">/</span>
                            <span class="landing-breadcrumb__current">{{ project.title }}</span>
                        </nav>

                        <div class="landing-project-view__meta">
                            <span class="landing-project-card__type">{{ project.type }}</span>
                            <span v-if="project.year" class="landing-project-card__year">{{ project.year }}</span>
                        </div>

                        <h1>{{ project.title }}</h1>

                        <p v-if="project.short_description" class="landing-page-intro">
                            {{ project.short_description }}
                        </p>

                        <div v-if="project.tags?.length" class="landing-project-view__tags">
                            <span v-for="tag in project.tags" :key="tag.id" class="landing-tag is-active">
                                {{ tag.name }}
                            </span>
                        </div>
                    </div>
                </section>

                <section class="landing-project-view">
                    <div class="landing-container">
                        <div v-if="project.cover_image" class="landing-project-view__cover">
                            <img :src="project.cover_image" :alt="project.title">
                        </div>

                        <div v-else-if="project.initial" class="landing-project-view__cover landing-project-view__cover--placeholder">
                            {{ project.initial }}
                        </div>

                        <div v-if="project.full_description" class="landing-project-view__content">
                            <p>{{ project.full_description }}</p>
                        </div>

                        <div v-if="project.images?.length" class="landing-project-view__gallery">
                            <img
                                v-for="image in project.images"
                                :key="image.id"
                                :src="image.path"
                                :alt="image.alt || project.title"
                            >
                        </div>

                        <div class="landing-project-view__actions">
                            <RouterLink :to="{ name: 'projects' }" class="landing-btn landing-btn--outline">
                                ← К портфолио
                            </RouterLink>
                            <a
                                v-if="project.site_url"
                                :href="project.site_url"
                                class="landing-btn landing-btn--primary"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                Открыть сайт
                            </a>
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

.landing-project-view__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
}

.landing-project-view__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 20px;
}

.landing-project-view {
    padding-bottom: 80px;
}

.landing-project-view__cover {
    margin-bottom: 32px;
    border-radius: var(--nx-radius);
    overflow: hidden;
    border: 1px solid var(--nx-border);
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
}

.landing-project-view__cover img {
    display: block;
    width: 100%;
    height: auto;
}

.landing-project-view__cover--placeholder {
    aspect-ratio: 16 / 9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--nx-font-display);
    font-size: clamp(3rem, 8vw, 5rem);
    font-weight: 700;
    color: var(--nx-accent);
    background: linear-gradient(135deg, var(--nx-accent-soft) 0%, var(--nx-bg-elevated) 100%);
}

.landing-project-view__content {
    max-width: 820px;
    margin-bottom: 32px;
    font-size: 1.05rem;
    line-height: 1.7;
    color: var(--nx-text-muted);
}

.landing-project-view__content p {
    margin: 0;
    white-space: pre-line;
}

.landing-project-view__gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.landing-project-view__gallery img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: var(--nx-radius);
    border: 1px solid var(--nx-border);
}

.landing-project-view__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
</style>
