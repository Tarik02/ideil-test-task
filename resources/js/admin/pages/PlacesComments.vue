<template>
    <v-layout fill-height column>
        <v-toolbar flat>
            <v-toolbar-title>
                {{ $t('resources.comments.plural') }}
            </v-toolbar-title>

            <v-spacer />

            <v-btn
                text
                @click="$router.go(-1)"
            >{{ $t('common.back') }}</v-btn>
        </v-toolbar>

        <v-layout fill-height column>
            <loading v-if="$loadLoading" />
            <error v-else-if="$loadError" @retry="load" />
            <template v-else-if="comments.length === 0">
                <v-row align="center" justify="center">
                    Коментарів немає.
                </v-row>
            </template>
            <template v-else>
                <v-row
                    v-for="comment in comments"
                    v-if="!comment.deleted"
                    :key="comment.id"
                >
                    <v-card class="ma-2" width="100%" :loading="comment.loading">
                        <v-card-title>
                            {{ comment.author.name }}, {{ formatDate(comment.created_at) }}
                        </v-card-title>
                        <v-card-text>
                            {{ comment.text }}
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer />

                            <v-checkbox
                                v-model="comment.visible"
                                :label="$t('fields.visible')"
                                class="mr-2"
                                @change="setCommentVisibility(comment, $event)"
                            />

                            <v-btn color="red" @click="deleteComment(comment)">
                                {{ $t('common.destroy') }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-row>
            </template>
        </v-layout>
    </v-layout>
</template>

<script>
    import Axios from 'axios';
    import Vue from 'vue';
    import {format, parseISO} from 'date-fns';
    import {uk} from 'date-fns/locale';

    import Error from '../components/common/Error';
    import Loading from '../components/common/Loading';
    import loader  from '../common/loader';

    export default {
        mixins: [
            loader,
        ],

        components: {
            Error,
            Loading,
        },

        data: () => ({
            comments: [],
        }),

        mounted() {
            this.load();
        },

        methods: {
            load() {
                this.$load(
                    'comments',
                    Axios.get(`/places/${this.$route.params.slug}/comments`),
                    {
                        success: response => {
                            this.comments = response.data.data;
                        },
                    },
                );
            },

            formatDate(iso) {
                return format(parseISO(iso), 'PPPP', {locale: uk});
            },

            setCommentVisibility(comment, value) {
                Vue.set(comment, 'loading', true);

                Axios.patch(`/places/${this.$route.params.slug}/comments/${comment.id}`, {
                    visible: value,
                })
                    .then(() => {
                        Vue.set(comment, 'visible', value);
                        Vue.set(comment, 'loading', false);
                    })
                    .catch(() => {
                        Vue.set(comment, 'visible', !value);
                        Vue.set(comment, 'loading', false);
                    })
                ;
            },

            deleteComment(comment) {
                Vue.set(comment, 'loading', true);

                Axios.delete(`/places/${this.$route.params.slug}/comments/${comment.id}`)
                    .then(() => {
                        Vue.set(comment, 'deleted', true);
                    })
                    .catch(() => {
                        Vue.set(comment, 'loading', false);
                    })
                ;
            },
        },

        watch: {
            '$route': 'load',
        },
    };
</script>

<style scoped>

</style>
