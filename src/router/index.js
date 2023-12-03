import {createRouter, createWebHistory} from 'vue-router'
import ProjektyView from "@/views/ProjektyView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
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
        path: '/statistiky',
        name: 'statistiky',
        component: StatistikyView,
        props: true
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView,
    },
    {
        path: '/register',
        name: 'register',
        component: RegisterView,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router