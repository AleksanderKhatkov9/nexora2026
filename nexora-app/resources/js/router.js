// resources/js/router.js
import { createRouter, createWebHistory } from 'vue-router';
import IndexPage from './components/Page/IndexPage.vue';
import ProjectsPage from './components/Page/ProjectsPage.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: IndexPage,
    // опционально: загрузка данных до открытия страницы
    async beforeEnter(to, from, next) {
      // const data = await fetchLandingContent();
      // to.meta.prefetch = data;
      next();
    },
  },
  {
    path: '/projects',
    name: 'projects',
    component: ProjectsPage,
  },
];

export default createRouter({
  history: createWebHistory(), // URL: / и /projects
  routes,
  scrollBehavior(to) {
    if (to.hash) return { el: to.hash, behavior: 'smooth' };
    return { top: 0 };
  },
});