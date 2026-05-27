<script setup>
import { computed, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import { fetchPricingPage } from '../../api/site';

const loading = ref(true);
const error = ref('');
const page = ref(null);

const content = computed(() => page.value?.content ?? null);
const plans = computed(() => content.value?.plans ?? []);
const extras = computed(() => content.value?.extras ?? []);

const loadPage = async () => {
    loading.value = true;
    error.value = '';

    try {
        page.value = await fetchPricingPage();

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
        error.value = 'Не удалось загрузить страницу с ценами.';
        console.error(e);
    } finally {
        loading.value = false;
    }
};

onMounted(loadPage);
</script>

<template>
    <main class="landing-main">
        <div v-if="loading" class="landing-container landing-state">
            <p>Загрузка…</p>
        </div>

        <div v-else-if="error" class="landing-container landing-state landing-state--error">
            <p>{{ error }}</p>
        </div>

        <template v-else-if="page">
            <section class="landing-page-head">
                <div class="landing-container">
                    <nav class="landing-breadcrumb" aria-label="Хлебные крошки">
                        <RouterLink :to="{ name: 'home' }">Создание сайтов</RouterLink>
                        <span class="landing-breadcrumb__sep">/</span>
                        <span class="landing-breadcrumb__current">Цены</span>
                    </nav>

                    <p v-if="content?.intro?.eyebrow" class="landing-eyebrow">
                        {{ content.intro.eyebrow }}
                    </p>

                    <h1>{{ page.title?.replace(' — Nexora', '') || 'Цены' }}</h1>

                    <p class="landing-page-intro">
                        {{ content?.intro?.lead || page.description }}
                    </p>
                </div>
            </section>

            <section class="landing-pricing-section">
                <div class="landing-container">
                    <div v-if="plans.length" class="landing-pricing-grid">
                        <article
                            v-for="(plan, index) in plans"
                            :key="index"
                            class="landing-pricing-card"
                            :class="{ 'landing-pricing-card--featured': plan.featured }"
                        >
                            <span v-if="plan.badge" class="landing-pricing-card__badge">
                                {{ plan.badge }}
                            </span>

                            <h2 class="landing-pricing-card__name">{{ plan.name }}</h2>
                            <p class="landing-pricing-card__desc">{{ plan.description }}</p>

                            <div class="landing-pricing-card__price">
                                <span v-if="plan.period" class="landing-pricing-card__period">
                                    {{ plan.period }}
                                </span>
                                <span class="landing-pricing-card__amount">{{ plan.price }}</span>
                                <span class="landing-pricing-card__currency">{{ plan.currency }}</span>
                            </div>

                            <ul v-if="plan.features?.length" class="landing-pricing-card__features">
                                <li v-for="(feature, featureIndex) in plan.features" :key="featureIndex">
                                    {{ feature }}
                                </li>
                            </ul>

                            <RouterLink
                                :to="{ name: 'home', hash: '#contact' }"
                                class="landing-btn"
                                :class="plan.featured ? 'landing-btn--primary' : 'landing-btn--outline'"
                            >
                                {{ plan.cta || 'Оставить заявку' }}
                            </RouterLink>
                        </article>
                    </div>

                    <div v-if="extras.length" class="landing-pricing-extras">
                        <h2 class="landing-section__title">Дополнительные услуги</h2>
                        <div class="landing-pricing-extras__grid">
                            <div
                                v-for="(extra, index) in extras"
                                :key="index"
                                class="landing-pricing-extra"
                            >
                                <h3>{{ extra.title }}</h3>
                                <p>{{ extra.text }}</p>
                            </div>
                        </div>
                    </div>

                    <p v-if="content?.note" class="landing-pricing-note">
                        {{ content.note }}
                    </p>
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

.landing-eyebrow {
    margin: 0 0 12px;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--nx-accent);
}
</style>
