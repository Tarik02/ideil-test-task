<template>
    <v-app>
        <drawer v-model="drawer">
            <drawer-item
                icon="dashboard"
                :route="{ name: 'dashboard' }"
            >Головна</drawer-item>
            <drawer-item
                icon="view_list"
                :route="{ name: 'places' }"
            >Місця</drawer-item>
        </drawer>

        <v-app-bar
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            app
        >
            <v-toolbar-title
                class="ml-0 pl-3"
            >
                <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
                <span>Архітектурні місця Європи</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon @click="$vuetify.theme.dark = !$vuetify.theme.dark">
                <v-icon>invert_colors</v-icon>
            </v-btn>
        </v-app-bar>
        <v-content>
            <v-layout fluid fill-height>
                <v-row no-gutters justify="center">
                    <v-col cols="12" xs="12" sm="10" xl="6">
                        <v-layout fluid fill-height>
                            <v-card class="px-4 py-2" width="100%">
                                <keep-alive>
                                    <router-view />
                                </keep-alive>
                            </v-card>
                        </v-layout>
                    </v-col>
                </v-row>
            </v-layout>
        </v-content>

    </v-app>
</template>

<script>
    import Drawer from './components/Drawer';
    import DrawerItem from './components/DrawerItem';

    export default {
        components: {
            Drawer,
            DrawerItem,
        },

        data: () => ({
            drawer: false,
            dark: false,
        }),
    };
</script>
