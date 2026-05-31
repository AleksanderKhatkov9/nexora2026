<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import AsyncState from '../ui/AsyncState.vue';
import { useCmsPage } from '../../composables/useCmsPage.js';
import { useSiteApi } from '../../composables/useInjections.js';

const api = useSiteApi();

const { loading, error, page } = useCmsPage(
    () => api.pages.getReviews(),
    { errorMessage: 'Не удалось загрузить страницу с отзывами.' },
);

const content = computed(() => page.value?.content ?? null);
const reviews = computed(() => content.value?.reviews ?? []);
</script>

<template>
    <main class="landing-main">
        <AsyncState :loading="loading" :error="error">
            <template v-if="page">
                <section class="landing-page-head">
                    <div class="landing-container">
                        <nav class="landing-breadcrumb" aria-label="Хлебные крошки">
                            <RouterLink :to="{ name: 'home' }">Создание сайтов</RouterLink>
                            <span class="landing-breadcrumb__sep">/</span>
                            <span class="landing-breadcrumb__current">Отзывы</span>
                        </nav>

                        <p v-if="content?.intro?.eyebrow" class="landing-eyebrow">
                            {{ content.intro.eyebrow }}
                        </p>

                        <h1>{{ page.title?.replace(' — Nexora', '') || 'Отзывы клиентов' }}</h1>

                        <p class="landing-page-intro">
                            {{ content?.intro?.lead || page.description }}
                        </p>
                    </div>
                </section>

                <section class="landing-reviews-page">
                    <div class="landing-container">
                        <div v-if="reviews.length" class="landing-reviews-grid">
                            <article
                                v-for="(review, index) in reviews"
                                :key="`${review.company}-${index}`"
                                class="landing-review-card"
                            >
                                <div class="landing-review-card__quote">“</div>
                                <p class="landing-review-card__text">{{ review.text }}</p>

                                <div v-if="review.result" class="landing-review-card__result">
                                    {{ review.result }}
                                </div>

                                <div class="landing-review-card__author">
                                    <span class="landing-review-card__name">{{ review.name }}</span>
                                    <span>{{ review.role }}</span>
                                    <strong>{{ review.company }}</strong>
                                </div>
                            </article>
                        </div>

                        <div class="landing-reviews-cta">
                            <div>
                                <h2>Хотите такой же результат?</h2>
                                <p>Опишите задачу, и я предложу понятный план разработки, сроки и бюджет.</p>
                            </div>
                            <RouterLink :to="{ name: 'home', hash: '#contact' }" class="landing-btn landing-btn--primary">
                                Обсудить проект
                            </RouterLink>
                        </div>
                    </div>
                </section>
            </template>
        </AsyncState>
    </main>
</template>
