export function createBlogApi(http) {
    return {
        getNewsFeed: () => http.get('/api/news'),

        getArticlesFeed: () => http.get('/api/articles'),

        getNewsBySlug: (slug) => http.get(`/api/news/${slug}`),

        getArticleBySlug: (slug) => http.get(`/api/articles/${slug}`),
    };
}
