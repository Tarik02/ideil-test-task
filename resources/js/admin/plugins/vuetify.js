import Vue from 'vue';
import Vuetify from 'vuetify';
import uk from 'vuetify/es5/locale/uk';

Vue.use(Vuetify);

export const vuetify = new Vuetify({
    icons: {
        iconfont: 'md',
    },
    theme: {
        dark: localStorage.getItem('dark-mode') === 'true',
    },
    lang: {
        current: 'uk',
        locales: {uk},
    },
});
