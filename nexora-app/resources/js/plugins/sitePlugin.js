import { APP_CONFIG_KEY, NAVIGATION_KEY, SITE_API_KEY } from '../config/injectionKeys.js';

export function createSitePlugin({ appConfig, navigation, siteApi }) {
    return {
        install(app) {
            app.provide(APP_CONFIG_KEY, appConfig);
            app.provide(NAVIGATION_KEY, navigation);
            app.provide(SITE_API_KEY, siteApi);
        },
    };
}
