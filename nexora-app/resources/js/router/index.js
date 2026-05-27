import { createRouter, createWebHistory } from 'vue-router';
import GenericPage from '../components/Page/GenericPage.vue';
import IndexPage from '../components/Page/IndexPage.vue';
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
        path: '/projects/page/:id',
        name: 'projects.view',
        component: ProjectViewPage,
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
