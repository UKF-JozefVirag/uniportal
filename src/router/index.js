import {createRouter, createWebHistory} from 'vue-router'
import ProjektyView from "@/views/ProjektyView.vue";
import LoginView from "@/views/LoginView.vue";
import PublikacieView from "@/views/PublikacieView.vue";

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
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router