import { i18n, vuetify } from './bootstrap';

import Axios from 'axios';
import Vue from 'vue';
import VueRouter from 'vue-router';

import App from './App';
import routes from './routes';

const router = new VueRouter({
    mode: 'history',
    base: '/admin/',
    routes,
});

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    vuetify,
    i18n,
});

window.addEventListener('storage', event => {
    if (event.key === 'dark-mode') {
        vuetify.framework.theme.dark = event.newValue === 'true';
    }
});

app.$watch(
    () => vuetify.framework.theme.dark,
    value => localStorage.setItem('dark-mode', value ? 'true' : 'false'),
);

if (process.env.NODE_ENV === 'development') {
    window.$app = app;
}
