import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import router from './router';

const appRoot = document.getElementById('app');

if (appRoot) {
    const appConfig = {
        homeUrl: appRoot.dataset.homeUrl || '/',
        projectsUrl: appRoot.dataset.projectsUrl || '/projects',
        faviconUrl: appRoot.dataset.faviconUrl || '/favicon.svg',
        loginUrl: appRoot.dataset.loginUrl || '',
        dashboardUrl: appRoot.dataset.dashboardUrl || '/nova',
        isAuthenticated: appRoot.dataset.isAuthenticated?.trim() === 'true',
        csrfToken: appRoot.dataset.csrfToken || '',
        currentYear: appRoot.dataset.currentYear || new Date().getFullYear(),
    };

    createApp(App)
        .provide('appConfig', appConfig)
        .use(router)
        .mount('#app');
}
