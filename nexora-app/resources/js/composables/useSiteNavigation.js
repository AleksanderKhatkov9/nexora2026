import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';
import { isNavigationItemActive, resolveNavigationLink } from '../services/navigation.js';
import { useNavigationItems } from './useInjections.js';

const HIDDEN_HEADER_SLUGS = new Set(['nav-team', 'nav-contact', 'articles']);

export function useSiteNavigation() {
    const navigationItems = useNavigationItems();
    const navigation = computed(() => navigationItems.filter((item) => !HIDDEN_HEADER_SLUGS.has(item.slug)));
    const route = useRoute();
    const mobileMenuOpen = ref(false);

    const closeMobileMenu = () => {
        mobileMenuOpen.value = false;
    };

    const navLinkTo = (item) => resolveNavigationLink(item);
    const isNavActive = (item) => isNavigationItemActive(route, item);

    return {
        navigation,
        mobileMenuOpen,
        closeMobileMenu,
        navLinkTo,
        isNavActive,
    };
}
