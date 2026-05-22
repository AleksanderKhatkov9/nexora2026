import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';

const appRoot = document.getElementById('app');
if (appRoot) {
    createApp(App, {
        homeUrl: appRoot.dataset.homeUrl,
        projectsUrl: appRoot.dataset.projectsUrl,
        faviconUrl: appRoot.dataset.faviconUrl,
        loginUrl: appRoot.dataset.loginUrl,
        dashboardUrl: appRoot.dataset.dashboardUrl,
        isAuthenticated: appRoot.dataset.isAuthenticated?.trim() === 'true',
        csrfToken: appRoot.dataset.csrfToken,
        currentYear: appRoot.dataset.currentYear,
    }).mount('#app');
}
