<template>
    <input
        ref="input"
        type="file"
        multiple
        accept="image/*"
        style="display: none;"
    />
</template>

<script>
    export default {
        props: {
            value: Boolean,
        },

        mounted() {
            this.$emit('input', false);
            this.$refs.input.addEventListener('change', this.change);
        },

        methods: {
            change() {
                [...this.$refs.input.files].map(file => this.$emit('load', file));
            },
        },

        watch: {
            value(value) {
                if (value) {
                    this.$emit('input', false);
                    this.$refs.input.click();
                }
            },
        },
    };
</script>

<style scoped>

</style>
