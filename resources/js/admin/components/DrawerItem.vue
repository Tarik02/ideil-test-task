<template>
    <v-list-item
        :key="routeName"
        @click="$router.push(route)"
    >
        <v-list-item-action>
            <v-icon>{{ icon }}</v-icon>
        </v-list-item-action>
        <v-list-item-content>
            <v-list-item-title>
                <slot />
            </v-list-item-title>
        </v-list-item-content>
    </v-list-item>
</template>

<script>
    export default {
        props: ['route', 'icon'],

        computed: {
            routeName() {
                return typeof this.route === 'object'
                    ? this.route.name
                    : this.route
                ;
            },
        },
    }
</script>
