<script setup>
import { RouterLink } from 'vue-router';
import AsyncState from '../ui/AsyncState.vue';
import { usePortfolio } from '../../composables/usePortfolio.js';

const {
    loading,
    error,
    pageMeta,
    allTags,
    filteredProjects,
    activeTag,
    selectTag,
} = usePortfolio();
</script>

<template>
    <main class="landing-main">
        <AsyncState :loading="loading" :error="error" loading-text="Загрузка портфолио…">
            <section class="landing-page-head">
                <div class="landing-container">
                    <nav class="landing-breadcrumb" aria-label="Хлебные крошки">
                        <RouterLink :to="{ name: 'home' }">Создание сайтов</RouterLink>
                        <span class="landing-breadcrumb__sep">/</span>
                        <span class="landing-breadcrumb__current">Портфолио</span>
                    </nav>

                    <h1>{{ pageMeta?.title || 'Портфолио' }}</h1>
                    <p class="landing-page-intro">
                        {{ pageMeta?.description }}
                    </p>
                </div>
            </section>

            <section class="landing-projects-section">
                <div class="landing-container">
                    <div class="landing-projects-toolbar" role="tablist" aria-label="Фильтр проектов">
                        <button
                            v-for="tag in allTags"
                            :key="tag.slug"
                            type="button"
                            class="landing-tag"
                            :class="{ 'is-active': activeTag === tag.slug }"
                            role="tab"
                            :aria-selected="activeTag === tag.slug ? 'true' : 'false'"
                            @click="selectTag(tag.slug)"
                        >
                            {{ tag.name }}
                        </button>
                    </div>

                    <p v-if="filteredProjects.length === 0" class="landing-projects-empty is-visible">
                        По выбранной категории проектов пока нет.
                    </p>

                    <div v-else class="landing-projects-grid">
                        <article v-for="project in filteredProjects" :key="project.id" class="landing-project-card">
                            <div class="landing-project-card__thumb" aria-hidden="true">
                                <img
                                    v-if="project.cover_image"
                                    :src="project.cover_image"
                                    :alt="project.title"
                                    class="landing-project-card__image"
                                >
                                <span v-else>{{ project.initial }}</span>
                            </div>
                            <div class="landing-project-card__body">
                                <div class="landing-project-card__meta">
                                    <span class="landing-project-card__type">{{ project.type }}</span>
                                    <span class="landing-project-card__year">{{ project.year }}</span>
                                </div>
                                <h2 class="landing-project-card__title">{{ project.title }}</h2>
                                <p v-if="project.short_description" class="landing-project-card__desc">
                                    {{ project.short_description }}
                                </p>
                                <RouterLink
                                    :to="{ name: 'projects.view', params: { id: project.id } }"
                                    class="landing-project-card__link"
                                >
                                    Смотреть проект →
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
.landing-project-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.landing-project-card__desc {
    margin: 0 0 12px;
    font-size: 0.9rem;
    color: var(--nx-text-muted);
    line-height: 1.5;
}

.landing-projects-empty {
    margin: 0 0 24px;
    padding: 24px;
    text-align: center;
    color: var(--nx-text-muted);
    background: var(--nx-bg-elevated);
    border: 1px dashed var(--nx-border);
    border-radius: var(--nx-radius);
}

.landing-projects-empty.is-visible {
    display: block;
}
</style>
