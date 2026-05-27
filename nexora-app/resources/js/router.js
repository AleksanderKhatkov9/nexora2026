import { createRouter, createWebHistory } from 'vue-router';
import IndexPage from './components/Page/IndexPage.vue';
import ProjectsPage from './components/Page/ProjectsPage.vue';
import ProjectViewPage from './components/Page/ProjectViewPage.vue';
import PricingPage from './components/Page/PricingPage.vue';
import GenericPage from './components/Page/GenericPage.vue';

const routes = [
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

export default createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to) {
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' };
        }

        return { top: 0 };
    },
});
