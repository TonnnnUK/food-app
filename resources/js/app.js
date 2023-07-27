import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { Link, createInertiaApp, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { UrlParamService } from './Services/URLParamService';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Food Workout Planner';
window.url = new UrlParamService;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component('Link', Link)
            .component('Head', Head)
            .mount(el);
    },
    title: title => `FWP - ${title}`,
    progress: {
        color: '#4B5563',
        showSpinner: true,
    },
});
