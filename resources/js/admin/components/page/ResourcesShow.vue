<template>
    <v-layout fill-height>
        <template v-if="error">
            <v-row justify="center" align="center">
                <error @retry="load" />
            </v-row>
        </template>
        <template v-else-if="loading">
            <v-row justify="center" align="center">
                <loading />
            </v-row>
        </template>
        <template v-else>
            <v-row>
                <v-col>
                    <v-form
                        ref="form"
                        v-model="valid"
                        @submit="save"
                    >
                        <v-row>
                            <slot v-bind="{ data, meta }" />
                        </v-row>

                        <v-col fluid>
                            <v-card-actions>
                                <slot name="buttons" />

                                <v-spacer />

                                <v-btn
                                    text
                                    @click="cancel"
                                >{{ $t('common.cancel') }}</v-btn>

                                <v-btn
                                    v-if="!isNew"
                                    color="red"
                                    @click="deleteDialog = true"
                                >{{ $t('common.destroy') }}</v-btn>

                                <v-btn
                                    :disabled="!valid"
                                    @click="save"
                                >{{ $t(isNew ? 'common.create' : 'common.save') }}</v-btn>
                            </v-card-actions>
                        </v-col>
                    </v-form>
                </v-col>
            </v-row>
        </template>

        <delete-dialog
            v-model="deleteDialog"
            :identifier="identifier"
            :name="name"
            :model="data"
            @done="$router.go(-1), $router.reload()"
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
            identifier: {
                type: String,
                default: 'id',
            },
            initialModel: {
                type: Function,
                default: () => ({}),
            },
            initialMeta: {
                type: Function,
                default: () => ({}),
            },
        },

        data: () => ({
            loading: true,
            error: false,
            data: undefined,
            meta: undefined,

            valid: true,
            deleteDialog: false,
        }),

        computed: {
            identifierValue() {
                return this.$route.params[this.identifier];
            },

            isNew() {
                return !this.identifierValue;
            },
        },

        mounted() {
            this.load();
        },

        methods: {
            load() {
                if (this.isNew) {
                    this.loading = false;
                    this.error = false;
                    if (!this.data) {
                        this.data = this.initialModel();
                        this.meta = this.initialMeta();
                    }
                    return;
                }

                this.loading = true;
                this.error = false;

                Axios.get(`/${this.name}/${this.$route.params[this.identifier]}`)
                    .then(response => {
                        this.loading = false;
                        this.error = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                    })
                    .catch(() => {
                        this.loading = false;
                        this.error = true;
                    })
                ;
            },

            save() {
                const data = new FormData();
                this.$emit('buildFormData', data);

                this.loading = true;
                Axios
                    .request({
                        url: (
                            this.isNew
                                ? `/${this.name}`
                                : `/${this.name}/${this.$route.params[this.identifier]}`
                        ),
                        method: (
                            this.isNew ? 'post' : 'patch'
                        ),
                        data,
                    })
                    .then(() => {
                        if (this.isNew) {
                            this.$router.push({
                                name: `${this.name}.edit`,
                                params: {
                                    [this.identifier]: this.data[this.identifier],
                                },
                            });
                        } else {
                            this.load();
                        }
                    })
                    .catch(() => {
                        this.loading = false;
                        // this.error = true;
                        // TOOD: show notification about the error
                    })
                ;
            },

            cancel() {
                this.$router.go(-1);
            },
        },
    };
</script>
