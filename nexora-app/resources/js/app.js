import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';
import { fetchNavigation } from './api/site';

const appRoot = document.getElementById('app');

async function bootstrap() {
    if (!appRoot) {
        return;
    }

    const appConfig = {
        homeUrl: appRoot.dataset.homeUrl || '/',
        projectsUrl: appRoot.dataset.projectsUrl || '/projects',
        faviconUrl: appRoot.dataset.faviconUrl || '/favicon.svg',
        csrfToken: appRoot.dataset.csrfToken || '',
        currentYear: appRoot.dataset.currentYear || new Date().getFullYear(),
    };

    let navigation = [];

    try {
        navigation = await fetchNavigation();
    } catch (error) {
        console.error('Failed to load navigation', error);
    }

    createApp(App)
        .provide('appConfig', appConfig)
        .provide('navigation', navigation)
        .use(router)
        .mount('#app');
}

bootstrap();
