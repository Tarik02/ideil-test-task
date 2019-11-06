<template>
    <v-layout fill-height>
        <loading v-if="$loadLoading" />
        <error v-else-if="$loadError" @retry="load" />
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
                                    @click="$router.go(-1)"
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
            @done="$router.go(-1)"
        />
    </v-layout>
</template>

<script>
    import Axios from 'axios';

    import Error from '../common/Error';
    import Loading from '../common/Loading';
    import DeleteDialog from '../DeleteDialog';
    import loader from '../../common/loader';

    export default {
        mixins: [
            loader,
        ],

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
                    this.data = this.initialModel();
                    this.meta = this.initialMeta();
                    this.$loadCancel('resource');
                    return;
                }

                this.$load(
                    'resource',
                    Axios.get(`/${this.name}/${this.$route.params[this.identifier]}`),
                    {
                        success: response => {
                            this.data = response.data.data;
                            this.meta = response.data.meta;
                        },
                    },
                );
            },

            save() {
                const data = new FormData();
                this.$emit('buildFormData', data);

                this.$load(
                    'resource',
                    Axios.request({
                        url: (
                            this.isNew
                                ? `/${this.name}`
                                : `/${this.name}/${this.$route.params[this.identifier]}`
                        ),
                        method: (
                            this.isNew ? 'post' : 'patch'
                        ),
                        data,
                    }),
                    {
                        success: () => {
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
                        },
                    },
                    {
                        ignoreError: true,
                    },
                );
            },
        },

        watch: {
            '$route': 'load',
            data() {
                this.$emit('input', this.data);
            },
        },
    };
</script>
