import { createRouter, createWebHistory } from 'vue-router';
import ArticlesPage from '../components/Page/ArticlesPage.vue';
import BlogPostViewPage from '../components/Page/BlogPostViewPage.vue';
import GenericPage from '../components/Page/GenericPage.vue';
import IndexPage from '../components/Page/IndexPage.vue';
import NewsPage from '../components/Page/NewsPage.vue';
import PricingPage from '../components/Page/PricingPage.vue';
import ProjectViewPage from '../components/Page/ProjectViewPage.vue';
import ProjectsPage from '../components/Page/ProjectsPage.vue';

export const routes = [
    {
        path: '/',
        name: 'home',
        component: IndexPage,
    },
    {
        path: '/pricing',
        name: 'pricing',
        component: PricingPage,
    },
    {
        path: '/projects',
        name: 'projects',
        component: ProjectsPage,
    },
    {
        path: '/projects/:slug',
        name: 'projects.view',
        component: ProjectViewPage,
    },
    {
        path: '/news',
        name: 'news',
        component: NewsPage,
    },
    {
        path: '/news/:slug',
        name: 'news.view',
        component: BlogPostViewPage,
        props: { kind: 'news' },
    },
    {
        path: '/articles',
        name: 'articles',
        component: ArticlesPage,
    },
    {
        path: '/articles/:slug',
        name: 'articles.view',
        component: BlogPostViewPage,
        props: { kind: 'article' },
    },
    {
        path: '/:slug',
        name: 'page',
        component: GenericPage,
    },
];

export function createAppRouter() {
    return createRouter({
        history: createWebHistory(),
        routes,
        scrollBehavior(to) {
            if (to.hash) {
                return { el: to.hash, behavior: 'smooth' };
            }

            return { top: 0 };
        },
    });
}

export default createAppRouter();
