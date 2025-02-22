import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import { createRouter, createWebHistory } from 'vue-router';
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
import Home from '@/Pages/Welcome.vue';
import Jobslist from '@/Pages/Jobs/Jobslist.vue';
const routes = [
    { path: '/', component: Home },
    { path: '/munkak', component: Jobslist },
  ];
const router = createRouter({
    history: createWebHistory(),
    routes,
  });
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue,{
                theme: {
                    preset: Aura
                },
            })
            .use(router)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
