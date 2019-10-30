<template>
    <form>
        <h3>Залишити коментар</h3>
        <div class="form-group">
            <textarea
                v-model="text"
                cols="80"
                rows="5"
                class="form-control"
                placeholder="Текст"
                :disabled="submitting"
            />
        </div>
        <button
            type="submit"
            class="btn btn-primary"
            :disabled="submitting || text.length < 10"
            @click.prevent="submit"
        >Опублікувати</button>
        <button
            type="submit"
            class="btn btn-secondary"
            :disabled="submitting"
            @click.prevent="$emit('cancel')"
        >Скасувати</button>
        <div v-if="errored" class="alert-danger">
            Сталася помилка під час останньої спроби відправлення коментаря
        </div>
    </form>
</template>

<script>
    import Axios from 'axios';

    export default {
        props: ['place'],

        data: () => ({
            submitting: false,
            errored: false,
            text: '',
        }),

        methods: {
            submit() {
                this.errored = false;
                this.submitting = true;

                Axios.put(`/place/${this.place.slug}/comments`, {
                    text: this.text,
                })
                    .then(() => {
                        this.submitting = false;
                        this.$emit('success');
                    })
                    .catch(() => {
                        this.submitting = false;
                        this.errored = true;
                    })
                ;
            },
        },
    };
</script>
