export function createPageApi(http) {
    return {
        getHome: () => http.get('/api/page/home'),
        getPricing: () => http.get('/api/page/pricing'),
        getNavigation: () => http.get('/api/page/navigation'),
        getBySlug: (slug) => http.get(`/api/page/${slug}`),
    };
}
