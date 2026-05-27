import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { isNavigationItemActive, resolveNavigationLink } from '../services/navigation.js';
import { useNavigationItems } from './useInjections.js';

export function useSiteNavigation() {
    const navigation = useNavigationItems();
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
