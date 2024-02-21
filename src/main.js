import { createApp } from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import { loadFonts } from './plugins/webfontloader'
import router from "@/router";
import {store} from "core-js/internals/reflect-metadata";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css"

loadFonts()
import "../node_modules/bootstrap/dist/css/bootstrap.css";
import "../node_modules/bootstrap/dist/js/bootstrap.bundle";
import 'bootstrap/dist/js/bootstrap.js';

import "./style.css";

const options = {

}

createApp(App).use(store).use(vuetify).use(router).use(Toast, options).mount('#app')
