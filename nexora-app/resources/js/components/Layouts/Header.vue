<script setup>
import { RouterLink } from 'vue-router';
import { useSiteNavigation } from '../../composables/useSiteNavigation.js';

defineProps({
    faviconUrl: { type: String, default: '/favicon.svg' },
});

const {
    navigation,
    mobileMenuOpen,
    closeMobileMenu,
    navLinkTo,
    isNavActive,
} = useSiteNavigation();
</script>

<template>
    <header class="landing-header">
        <div class="landing-container">
            <div class="landing-header__inner">
                <RouterLink :to="{ name: 'home' }" class="landing-logo">
                    <img :src="faviconUrl" alt="" class="landing-logo__icon" width="32" height="32">
                    Nex<span>ora</span>
                </RouterLink>

                <nav class="landing-nav" aria-label="Основное меню">
                    <template v-for="item in navigation" :key="item.slug">
                        <a
                            v-if="item.type === 'external'"
                            :href="item.external_url"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {{ item.label }}
                        </a>
                        <RouterLink
                            v-else
                            :to="navLinkTo(item)"
                            :class="{ 'is-active': isNavActive(item) }"
                        >
                            {{ item.label }}
                        </RouterLink>
                    </template>
                </nav>

                <div class="landing-header__actions">
                    <a href="tel:+375291234567" class="landing-phone">+375 (29) 123-45-67</a>
                    <RouterLink :to="{ name: 'home', hash: '#contact' }" class="landing-btn landing-btn--primary">
                        Обсудить проект
                    </RouterLink>

                    <button
                        type="button"
                        class="landing-burger"
                        aria-label="Меню"
                        :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>

            <nav
                class="landing-mobile-nav"
                :class="{ 'is-open': mobileMenuOpen }"
                aria-label="Мобильное меню"
            >
                <template v-for="item in navigation" :key="`mobile-${item.slug}`">
                    <a
                        v-if="item.type === 'external'"
                        :href="item.external_url"
                        target="_blank"
                        rel="noopener noreferrer"
                        @click="closeMobileMenu"
                    >
                        {{ item.label }}
                    </a>
                    <RouterLink
                        v-else
                        :to="navLinkTo(item)"
                        :class="{ 'is-active': isNavActive(item) }"
                        @click="closeMobileMenu"
                    >
                        {{ item.label }}
                    </RouterLink>
                </template>
                <a href="tel:+375291234567" @click="closeMobileMenu">+375 (29) 123-45-67</a>
            </nav>
        </div>
    </header>
</template>
