import { inject } from 'vue';
import { computed } from 'vue';
import { FOOTER_KEY } from '../config/injectionKeys.js';
import { buildFooterGroups } from '../services/footerStructure.js';
import { resolveNavigationLink } from '../services/navigation.js';

export function useFooterItems() {
    return inject(FOOTER_KEY, []);
}

export function useSiteFooter() {
    const items = useFooterItems();

    const groups = computed(() => buildFooterGroups(items));
    const linkTo = (item) => resolveNavigationLink(item);

    return {
        groups,
        linkTo,
    };
}
