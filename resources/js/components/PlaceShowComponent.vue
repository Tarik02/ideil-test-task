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

                <template v-if="showCommentForm">
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </template>
                <template v-else>
                    <div class="comments-container">
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

                    <span @click="showCommentForm = true" class="btn btn-primary">Залишити коментар</span>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import Axios from 'axios';
    import { mapState } from 'vuex';
    import PlaceCommentCardComponent from './PlaceCommentCardComponent';

    export default {
        components: {
            PlaceCommentCardComponent,
        },

        data: () => ({
            showCommentForm: false,
        }),

        computed: {
            ...mapState({
                place: state => state.place,
                like_state: state => state.like_state,
            }),
        },

        methods: {
            toggleLike(value) {
                Axios.get(`/place/${this.place.slug}/like/${value}`).then(response => {
                    this.$store.commit('HANDLE_LIKE_RESPONSE', response.data);
                });
            },
        },
    };
</script>
