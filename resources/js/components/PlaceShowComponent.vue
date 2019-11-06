<template>
    <div class="container" id="show-place">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h5 class="title">
                    {{ place.name }}
                </h5>

                <p class="text">{{ place.description }}</p>

                <template v-if="place.fields.length > 0">
                    <h6>Додаткова інформація:</h6>
                    <table class="table">
                        <tr v-for="field of place.fields" :key="field.key">
                            <td>{{ field.key }}</td>
                            <td>{{ field.value }}</td>
                        </tr>
                    </table>
                </template>

                <h6>Галерея:</h6>
                <img
                    v-for="photo of place.photos"
                    :key="photo.preview"
                    :src="photo.preview"
                    alt=""
                    @click="showPhotoFullscreen(photo.original)"
                >

                <p class="text">
                    Оцінка: {{ place.mark }}/10
                    <br>
                    Кількість лайків: {{ place.likes_count }}
                    <br>
                    Кількість дизлайків: {{ place.dislikes_count }}
                    <br>
                    <span
                        @click="toggleLike(1)"
                        :class="[
                            'btn',
                            like_state === 'like' ? 'btn-primary' : 'btn-outline-primary'
                        ]"
                    >Like</span>
                    <span
                        @click="toggleLike(-1)"
                        :class="[
                            'btn',
                            like_state === 'dislike' ? 'btn-primary' : 'btn-outline-primary'
                        ]"
                    >Dislike</span>
                </p>

                <place-comment-form-component
                    v-if="showCommentForm"
                    :place="place"
                    @success="commentSuccess"
                    @cancel="showCommentForm = false"
                />
                <template v-else>
                    <div class="comments-container">
                        <h3>Коментарі</h3>

                        <template v-if="place.comments.length > 0">
                            <place-comment-card-component
                                v-for="comment of place.comments"
                                :key="comment.id"
                                :comment="comment"
                            />
                        </template>
                        <template v-else>
                            Коментарів немає
                        </template>
                    </div>

                    <span
                        v-if="authenticated"
                        @click="showCommentForm = true"
                        class="btn btn-primary"
                    >Залишити коментар</span>
                    <span v-else>
                        Лише авторизовані користувачі можуть залишати коментарі
                    </span>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import Axios from 'axios';
    import { mapState, mapActions } from 'vuex';
    import PlaceCommentCardComponent from './PlaceCommentCardComponent';
    import PlaceCommentFormComponent from './PlaceCommentFormComponent';

    export default {
        components: {
            PlaceCommentCardComponent,
            PlaceCommentFormComponent,
        },

        data: () => ({
            showCommentForm: false,
        }),

        computed: {
            ...mapState({
                place: state => state.place,
                like_state: state => state.like_state,
            }),
            authenticated() {
                return this.$store.state.auth.id !== null;
            },
        },

        methods: {
            toggleLike(value) {
                Axios.get(`/place/${this.place.slug}/like/${value}`).then(response => {
                    this.$store.commit('HANDLE_LIKE_RESPONSE', response.data);
                });
            },

            commentSuccess() {
                this.reloadComments();
                this.showCommentForm = false;
            },

            showPhotoFullscreen(url) {
                // TODO: show
            },

            ...mapActions(['reloadComments']),
        },
    };
</script>
