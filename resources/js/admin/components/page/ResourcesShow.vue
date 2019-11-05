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
                                    color="red"
                                    @click="destroy"
                                >{{ $t('common.destroy') }}</v-btn>

                                <v-btn
                                    :disabled="!valid"
                                    @click="save"
                                >{{ $t('common.save') }}</v-btn>
                            </v-card-actions>
                        </v-col>
                    </v-form>
                </v-col>
            </v-row>
        </template>
    </v-layout>
</template>

<script>
    import Axios from 'axios';
    import Error from '../common/Error';
    import Loading from '../common/Loading';

    export default {
        components: {
            Error,
            Loading,
        },

        props: {
            name: String,
            identifier: {
                type: String,
                default: 'id',
            },
        },

        data: () => ({
            loading: true,
            error: false,
            data: undefined,
            meta: undefined,

            valid: true,
        }),

        mounted() {
            this.load();
        },

        methods: {
            load() {
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
                const identifier = this.data[this.identifier];
                const create = !identifier;

                const data = new FormData();
                this.$emit('buildFormData', data);

                this.loading = true;
                Axios
                    .request({
                        url: (
                            create
                                ? `/${this.name}`
                                : `/${this.name}/${this.$route.params[this.identifier]}`
                        ),
                        method: (
                            create ? 'post' : 'patch'
                        ),
                        data,
                    })
                    .then(() => {
                        // this.
                        this.load();
                    })
                    .catch(() => {
                        this.loading = false;
                        // this.error = true;
                    })
                ;
            },

            destroy() {
                //
            },

            cancel() {
                this.$router.go(-1);
            },
        },
    };
</script>
