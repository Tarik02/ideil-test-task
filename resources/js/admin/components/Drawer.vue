<template>
    <v-navigation-drawer
        :value="value"
        :clipped="$vuetify.breakpoint.lgAndUp"
        @input="value => $emit('input', value)"
        app
    >
        <v-list dense shaped>
            <v-list-item-group :value="currentRouteIndex" color="primary">
                <slot />
            </v-list-item-group>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import _ from 'lodash';

    export default {
        props: ['value'],

        computed: {
            currentRouteIndex() {
                const currentRouteName = this.$router.currentRoute.name;

                return this.$slots.default
                    .filter(it => it.tag !== undefined)
                    .findIndex(
                        it => _.startsWith(it.componentOptions.propsData.route, currentRouteName)
                    );
            },
        },
    }
</script>
