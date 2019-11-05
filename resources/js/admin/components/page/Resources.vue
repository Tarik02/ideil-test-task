<template>
    <v-layout fill-height>
        <template v-if="error">
            <v-row justify="center" align="center">
                <error @retry="reload" />
            </v-row>
        </template>
        <loading v-else-if="firstLoading" />
        <v-row v-else>
            <v-col>
                <v-data-table
                    :headers="allHeaders"
                    :items="items"
                    :loading="loading"
                    :options.sync="options"
                    :server-items-length="meta.total"
                    :footer-props="{
                        'items-per-page-options': [],
                    }"
                    @click:row="openItem($event)"
                    :page="page"
                    @update:page="$mergePushRoute({ query: { page: $event } })"
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>
                                {{ $t(`resources.${name}.plural`) }}
                                ({{ $t('common.page') }} {{ page }}/{{ meta.last_page}})
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
                            @click.stop="editItem(item)"
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
            </v-col>
        </v-row>

        <delete-dialog
            v-model="deleteDialog"
            :identifier="identifier"
            :identifier-getter="identifierGetter"
            :name="name"
            :model="deleteModel"
            @done="$router.reload()"
        />
    </v-layout>
</template>

<script>
    import Axios from 'axios';
    import Error from '../common/Error';
    import Loading from '../common/Loading';
    import DeleteDialog from '../DeleteDialog';

    export default {
        components: {
            Error,
            Loading,
            DeleteDialog,
        },

        props: {
            name: String,
            page: Number,
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
            options: {},
            firstLoading: true,
            loading: true,
            error: false,

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
        },

        created() {
            this.options.page = this.$route.query.page ? Number(this.$route.query.page) : 1;
            this.reload(true);
        },

        methods: {
            reload(force = false) {
                if (this.loading && !force) {
                    return;
                }

                this.loading = true;
                this.error = false;

                return Axios.get(`/${this.name}`, {
                    params: {
                        page: this.page,
                    },
                })
                    .then(response => {
                        const {data, meta} = response.data;
                        this.items = data;
                        this.meta = meta;
                        this.firstLoading = false;
                        this.loading = false;
                        this.error = false;
                    })
                    .catch(() => {
                        this.items = [];
                        this.meta = {};
                        this.loading = false;
                        this.error = true;
                    })
                    ;
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
            options: {
                deep: true,
                handler(newValue, oldValue) {
                    if (newValue.page === oldValue.page) {
                        return;
                    }
                    this.reload();
                },
            },
        },
    }
</script>
