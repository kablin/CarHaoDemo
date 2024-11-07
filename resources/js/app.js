import './bootstrap';
import { createApp } from "vue";
import { createWebHistory, createRouter } from 'vue-router'
import 'flowbite';

import App from "./App.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
        }
    ],
})

createApp(App).use(router).mount("#app");