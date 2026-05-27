import { inject } from 'vue';
import { APP_CONFIG_KEY, NAVIGATION_KEY, SITE_API_KEY } from '../config/injectionKeys.js';

export function useAppConfig() {
    const config = inject(APP_CONFIG_KEY);

    if (!config) {
        throw new Error('App config is not provided.');
    }

    return config;
}

export function useNavigationItems() {
    return inject(NAVIGATION_KEY, []);
}

export function useSiteApi() {
    const api = inject(SITE_API_KEY);

    if (!api) {
        throw new Error('Site API is not provided.');
    }

    return api;
}
