import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import { createHttpClient } from './api/httpClient.js';
import { createSiteApi } from './api/createSiteApi.js';
import { createAppConfigFromRoot } from './config/appConfig.js';
import { createSitePlugin } from './plugins/sitePlugin.js';
import { createAppRouter } from './router/index.js';

const appRoot = document.getElementById('app');

async function bootstrap() {
    if (!appRoot) {
        return;
    }

    const appConfig = createAppConfigFromRoot(appRoot);
    const http = createHttpClient(window.axios);
    const siteApi = createSiteApi(http);

    let navigation = [];
    let footer = [];

    try {
        navigation = await siteApi.pages.getNavigation();
    } catch (error) {
        console.error('Failed to load navigation', error);
    }

    try {
        footer = await siteApi.pages.getFooter();
    } catch (error) {
        console.error('Failed to load footer', error);
    }

    createApp(App)
        .use(createSitePlugin({ appConfig, navigation, footer, siteApi }))
        .use(createAppRouter())
        .mount('#app');
}

bootstrap();
