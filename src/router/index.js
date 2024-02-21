import {createRouter, createWebHistory} from 'vue-router'
import ProjektyView from "@/views/ProjektyView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import PublikacieView from "@/views/PublikacieView.vue";
import StatistikyView from "@/views/StatistikyView.vue";
import ImportViewYear from "@/views/ImportViewYear";
import ImportView from "@/views/ImportView";
import ProjectManualSynchronizationView from "@/views/ProjectManualSynchronizationView";

const routes = [
    {
        path: '/',
        name: 'projekty',
        component: ProjektyView,
        props: true
    },
    {
        path: '/projektySynchronizacia',
        name: 'projektySynchronizacia',
        component: ProjectManualSynchronizationView,
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
    {
        path: '/importYear',
        name: 'importYear',
        component: ImportViewYear,
    },
    {
        path: '/import/:year',
        name: 'import',
        component: ImportView,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router