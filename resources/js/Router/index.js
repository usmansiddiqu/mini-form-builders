import { createRouter, createWebHistory } from 'vue-router';
import AllForms from '@/Pages/AllForms.vue';


const routes = [
    { path: '/forms', component: AllForms, name: 'allForms' },
    { path: '/:pathMatch(.*)*', redirect: '/forms' }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
