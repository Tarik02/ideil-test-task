<template>
    <v-layout fill-height>
        <error v-if="$loadError" @retry="load" />
        <v-row v-else>
            <v-col>
                <v-pagination
                    :value="page"
                    :length="meta.last_page"
                    :total-visible="6"
                    :disabled="$loadLoading"
                    circle
                    @input="$mergePushRoute({query:{page: $event}})"
                />

                <v-data-table
                    :headers="allHeaders"
                    :items="items"
                    :loading="$loadLoading"
                    hide-default-footer
                    @click:row="openItem($event)"
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>
                                {{ $t(`resources.${name}.plural`) }}
                                ({{ $t('common.page') }} {{ page }}/{{ meta.last_page }})
                            </v-toolbar-title>
                            <v-divider
                                class="mx-4"
                                inset
                                vertical
                            ></v-divider>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                class="mb-2"
                                @click="createNew"
                            >
                                {{ $t('resources.common.create') }}
                            </v-btn>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.action="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click.stop="openItem(item)"
                        >
                            edit
                        </v-icon>
                        <v-icon
                            small
                            @click.stop="deleteItem(item)"
                        >
                            delete
                        </v-icon>
                    </template>
                </v-data-table>

                <v-pagination
                    :value="page"
                    :total-visible="6"
                    :length="meta.last_page"
                    :disabled="$loadLoading"
                    circle
                    @input="$mergePushRoute({query:{page: $event}})"
                />
            </v-col>
        </v-row>

        <delete-dialog
            v-model="deleteDialog"
            :identifier="identifier"
            :identifier-getter="identifierGetter"
            :name="name"
            :model="deleteModel"
            @done="load()"
        />
    </v-layout>
</template>

<script>
    import Axios from 'axios';

    import Error from '../common/Error';
    import DeleteDialog from '../DeleteDialog';
    import loader from '../../common/loader';

    export default {
        mixins: [
            loader,
        ],

        components: {
            Error,
            DeleteDialog,
        },

        props: {
            name: String,
            headers: {
                type: Array,
                default: () => ({}),
            },
            identifier: {
                type: String,
                default: 'id',
            },
            identifierGetter: {
                type: Function,
                default: (item, identifier) => item[identifier],
            },
        },

        data: () => ({
            items: [],
            meta: {},

            deleteDialog: false,
            deleteModel: undefined,
        }),

        computed: {
            allHeaders() {
                return [
                    {text: '#', value: 'id'},
                    ...this.headers,
                    {text: this.$t('resources.common.actions'), value: 'action', sortable: false},
                ];
            },

            page() {
                return this.$route.query.page ? Number(this.$route.query.page) : 1;
            },
        },

        created() {
            this.load();
        },

        methods: {
            load() {
                this.$load(
                    'data',
                    Axios.get(`/${this.name}`, {
                        params: {
                            page: this.page,
                        },
                    }),
                    {
                        success: response => {
                            const {data, meta} = response.data;
                            this.items = data;
                            this.meta = meta;
                        },
                    },
                );
            },

            createNew() {
                this.$router.push({
                    name: `${this.name}.create`,
                });
            },

            openItem(item) {
                this.$router.push({
                    name: `${this.name}.edit`,
                    params: {
                        [this.identifier]: this.identifierGetter(item, this.identifier),
                    },
                });
            },

            deleteItem(item) {
                this.deleteDialog = true;
                this.deleteModel = item;
            },
        },

        watch: {
            '$route': 'load',
        },
    }
</script>
