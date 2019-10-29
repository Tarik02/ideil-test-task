import './bootstrap';

import Axios from 'axios';
import Vue from 'vue';

import { createStore } from './store-factory';
import PlaceShowComponent from './components/PlaceShowComponent';

const store = createStore(window.__initialState || {});

Axios.interceptors.response.use(response => response, error => {
    const {response} = error;
    if (response) {
        switch (response.status) {
            case 401:
                if (store.state.auth.id === null) {
                    // if we catch 401 and we are not logged in, then redirect to login page
                    window.location = '/login?redirect_to=' + encodeURIComponent(window.location);
                } else {
                    // if we catch 401 being logged in, then we are doing something unallowed
                    // TODO: show message?
                    console.warn('Received 401 while being logged in');
                }
                break;
        }
    }
    return Promise.reject(error);
});

if (process.env.NODE_ENV === 'development') {
    window.$store = store;
}

const replaceWithVue = (selector, component) => {
    return [...document.querySelectorAll(selector)].map(el => new Vue({
        el,
        store,
        render: h => h(component),
    }));
};

replaceWithVue('#show-place', PlaceShowComponent);
