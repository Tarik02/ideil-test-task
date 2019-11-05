import Vue from 'vue';

Vue.mixin({
    methods: {
        $mergePushRoute(route) {
            try {
                this.$router.push(_.merge({}, this.$router.currentRoute, route));
            } catch (e) {
                console.error(e);
            }
        },
    },
});
