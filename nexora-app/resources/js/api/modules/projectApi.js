export function createProjectApi(http) {
    return {
        getPortfolio: (tag = null) => {
            const params = tag && tag !== 'all' ? { tag } : {};
            return http.get('/api/projects', { params });
        },

        getBySlug: (slug) => http.get(`/api/projects/${slug}`),
    };
}
