<template>
    <v-row justify="center" align="center">
        <v-card class="px-5 py-2">
            <v-card-title>Помилка</v-card-title>
            <v-card-subtitle>Щось пішло не так :(</v-card-subtitle>
            <v-card-actions v-if="hasRetryButton">
                <v-btn @click="$emit('retry')">Спробувати знову</v-btn>
            </v-card-actions>
        </v-card>
    </v-row>
</template>

<script>
    export default {
        computed: {
            hasRetryButton() {
                return this.$listeners && this.$listeners.retry;
            },
        },
    }
</script>
