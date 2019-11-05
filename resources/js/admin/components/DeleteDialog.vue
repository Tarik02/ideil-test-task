<template>
    <v-dialog
        :value="value"
        @input="$emit('input', $event)"
        max-width="290"
    >
        <v-card>
            <v-card-title class="headline">Підтвердження видалення</v-card-title>
            <v-card-text>
                Ви впевнені, що хочете видалити "{{ subject }}"?
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="$emit('input', false)">Ні</v-btn>
                <v-btn color="red" text @click="confirm">Так</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import Axios from 'axios';

    export default {
        props: {
            name: String,
            model: [Object, undefined],
            value: Boolean,
            identifier: {
                type: String,
                default: 'id',
            },
            identifierGetter: {
                type: Function,
                default: (item, identifier) => item[identifier],
            },
        },

        computed: {
            subject() {
                if (!this.model) {
                    return '';
                }

                return this.model.name || this.model.title;
            },
        },

        methods: {
            confirm() {
                this.$emit('input', false);

                const id = this.identifierGetter(this.model, this.identifier);
                Axios.delete(`/${this.name}/${id}`)
                    .then(() => {
                        this.$emit('done');
                    })
                ;
            },
        },
    };
</script>

<style scoped>

</style>
