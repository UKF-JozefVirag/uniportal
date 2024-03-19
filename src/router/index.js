import {createRouter, createWebHistory} from 'vue-router'
import ProjectsView from "@/views/ProjectsView.vue";
import LoginView from "@/views/LoginView.vue";
import RegisterView from "@/views/RegisterView.vue";
import ImportViewYear from "@/views/ImportViewYear";
import ImportView from "@/views/ImportView";
import ProjectManualSynchronizationView from "@/views/ProjectManualSynchronizationView";
import PublicationsView from "@/views/PublicationsView.vue";
import StatisticsView from "@/views/StatisticsView.vue";

const routes = [
    {
        path: '/',
        name: 'projects',
        component: ProjectsView,
        props: true
    },
    {
        path: '/projektySynchronizacia',
        name: 'projektySynchronizacia',
        component: ProjectManualSynchronizationView,
        props: true
    },
    {
        path: '/publications',
        name: 'publications',
        component: PublicationsView,
        props: true
    },
    {
        path: '/statistics',
        name: 'statistics',
        component: StatisticsView,
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