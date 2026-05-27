export function createAppConfigFromRoot(rootElement) {
    if (!rootElement) {
        return {
            homeUrl: '/',
            projectsUrl: '/projects',
            faviconUrl: '/favicon.svg',
            csrfToken: '',
            currentYear: new Date().getFullYear(),
        };
    }

    return {
        homeUrl: rootElement.dataset.homeUrl || '/',
        projectsUrl: rootElement.dataset.projectsUrl || '/projects',
        faviconUrl: rootElement.dataset.faviconUrl || '/favicon.svg',
        csrfToken: rootElement.dataset.csrfToken || '',
        currentYear: rootElement.dataset.currentYear || new Date().getFullYear(),
    };
}
