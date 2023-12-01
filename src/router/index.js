import {createRouter, createWebHistory} from 'vue-router'
import ProjektyView from "@/views/ProjektyView.vue";
import LoginView from "@/views/LoginView.vue";
import PublikacieView from "@/views/PublikacieView.vue";
import StatistikyView from "@/views/StatistikyView.vue";

const routes = [
    {
        path: '/',
        name: 'projekty',
        component: ProjektyView,
        props: true
    },
    {
        path: '/publikacie',
        name: 'publikacie',
        component: PublikacieView,
        props: true
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView
    },
    {
        path: '/statistiky',
        name: 'statistiky',
        component: StatistikyView,
        props: true
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router