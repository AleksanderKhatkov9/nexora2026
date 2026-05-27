import { createRouter, createWebHistory } from 'vue-router';
import IndexPage from './components/Page/IndexPage.vue';
import ProjectsPage from './components/Page/ProjectsPage.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: IndexPage,
    },
    {
        path: '/projects',
        name: 'projects',
        component: ProjectsPage,
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
