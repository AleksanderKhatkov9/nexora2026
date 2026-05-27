<script setup>
import { RouterLink } from 'vue-router';
import { useSiteFooter } from '../../composables/useSiteFooter.js';

const { groups, linkTo } = useSiteFooter();
</script>

<template>
    <nav v-if="groups.length" class="landing-footer__nav" aria-label="Структура сайта">
        <div
            v-for="group in groups"
            :key="group.key"
            class="landing-footer__column"
        >
            <h3 class="landing-footer__title">{{ group.title }}</h3>
            <ul class="landing-footer__list">
                <li v-for="item in group.links" :key="item.slug">
                    <a
                        v-if="item.type === 'external'"
                        :href="item.external_url"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        {{ item.label }}
                    </a>
                    <RouterLink v-else :to="linkTo(item)">
                        {{ item.label }}
                    </RouterLink>
                </li>
            </ul>
        </div>
    </nav>
</template>
