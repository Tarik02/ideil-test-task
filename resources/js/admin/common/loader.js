import Vue from 'vue';

const getToken = (() => {
    let counter = 0;
    return () => ++counter;
})();

export default {
    data: () => ({
        loaderSlots: {
            __mounting: -1,
        },
    }),

    computed: {
        $loadLoading() {
            return Object.values(this.loaderSlots).some(it => typeof it === 'number');
        },

        $loadLoaded() {
            return !this.$loadLoading && !this.$loadError;
        },

        $loadError() {
            return Object.values(this.loaderSlots).some(it => it === 'error');
        },
    },

    mounted() {
        delete this.loaderSlots.__mounting;
    },

    destroyed() {
        this.loaderSlots = {};
    },

    methods: {
        $load(slot, promise, {success, error} = {}, {ignoreError = false} = {}) {
            const token = getToken();
            Vue.set(this.loaderSlots, slot, token);

            promise
                .then(result => {
                    if (token === this.loaderSlots[slot]) {
                        success && success(result);
                        Vue.set(this.loaderSlots, slot, 'success');
                    }
                })
                .catch(e => {
                    if (token === this.loaderSlots[slot]) {
                        error && error(e);
                        Vue.set(this.loaderSlots, slot, ignoreError ? 'success' : 'error');
                    }
                })
            ;
        },

        $loadCancel(slot) {
            Vue.set(this.loaderSlots, slot, 'success');
        },
    },
};
