export function createPageApi(http) {
    return {
        getHome: () => http.get('/api/page/home'),
        getPricing: () => http.get('/api/page/pricing'),
        getNavigation: () => http.get('/api/page/navigation'),
        getFooter: () => http.get('/api/page/footer'),
        getBySlug: (slug) => http.get(`/api/page/${slug}`),
    };
}
