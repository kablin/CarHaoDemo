import './bootstrap';
import { createApp } from "vue";
import { createWebHistory, createRouter } from 'vue-router'
import 'flowbite';
import PrimeVue from 'primevue/config';




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

    createApp(App).use(router).use(PrimeVue,{
        theme: 'none'
    }).mount("#app");