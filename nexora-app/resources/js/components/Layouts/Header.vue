<script setup>
import { computed, ref } from 'vue';
import { RouterLink, useRoute } from 'vue-router';

const props = defineProps({
    homeUrl: { type: String, default: '/' },
    projectsUrl: { type: String, default: '/projects' },
    faviconUrl: { type: String, default: '/favicon.svg' },
    loginUrl: { type: String, default: '' },
    dashboardUrl: { type: String, default: '/nova' },
    isAuthenticated: { type: Boolean, default: false },
});

const route = useRoute();
const mobileMenuOpen = ref(false);

const authLink = computed(() => {
    if (props.isAuthenticated) {
        return { href: props.dashboardUrl, label: 'Панель' };
    }

    if (props.loginUrl) {
        return { href: props.loginUrl, label: 'Вход' };
    }

    return null;
});

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
};

const homeSection = (hash) => {
    const cleanHash = hash.startsWith('#') ? hash : `#${hash}`;
    return { name: 'home', hash: cleanHash };
};
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
                    <RouterLink :to="homeSection('#services')">Услуги</RouterLink>
                    <RouterLink
                        :to="{ name: 'projects' }"
                        :class="{ 'is-active': route.name === 'projects' }"
                    >
                        Портфолио
                    </RouterLink>
                    <RouterLink :to="homeSection('#team')">Команда</RouterLink>
                    <RouterLink :to="homeSection('#contact')">Контакты</RouterLink>
                </nav>

                <div class="landing-header__actions">
                    <a href="tel:+375291234567" class="landing-phone">+375 (29) 123-45-67</a>
                    <a v-if="authLink" :href="authLink.href" class="landing-auth">{{ authLink.label }}</a>
                    <RouterLink :to="homeSection('#contact')" class="landing-btn landing-btn--primary">
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
                <RouterLink :to="homeSection('#services')" @click="closeMobileMenu">Услуги</RouterLink>
                <RouterLink
                    :to="{ name: 'projects' }"
                    :class="{ 'is-active': route.name === 'projects' }"
                    @click="closeMobileMenu"
                >
                    Портфолио
                </RouterLink>
                <RouterLink :to="homeSection('#team')" @click="closeMobileMenu">Команда</RouterLink>
                <RouterLink :to="homeSection('#contact')" @click="closeMobileMenu">Контакты</RouterLink>
                <a href="tel:+375291234567" @click="closeMobileMenu">+375 (29) 123-45-67</a>
            </nav>
        </div>
    </header>
</template>
