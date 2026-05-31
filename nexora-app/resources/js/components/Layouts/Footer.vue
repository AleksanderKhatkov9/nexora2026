<script setup>
import { RouterLink } from 'vue-router';
import FooterNav from './FooterNav.vue';
import { useAppConfig } from '../../composables/useInjections.js';

defineProps({
    currentYear: {
        type: [String, Number],
        default: () => new Date().getFullYear(),
    },
});

const appConfig = useAppConfig();
</script>

<template>
    <footer class="landing-footer">
        <div class="landing-container landing-footer__inner">
            <div class="landing-footer__brand">
                <RouterLink :to="{ name: 'home' }" class="landing-logo landing-footer__logo">
                    Nex<span>ora</span>
                </RouterLink>
                <p class="landing-footer__tagline">
                    Частная разработка сайтов и веб-приложений
                </p>
                <a href="tel:+375291234567" class="landing-footer__phone">
                    +375 (29) 123-45-67
                </a>
                <a href="mailto:hello@nexora.by" class="landing-footer__email">
                    hello@nexora.by
                </a>
            </div>

            <FooterNav />

            <p class="landing-footer__copy">
                &copy; {{ currentYear }} Nexora. Все права защищены.
            </p>
        </div>
    </footer>
</template>
