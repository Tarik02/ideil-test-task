<template>
    <resource
        name="places"
        identifier="slug"
        :headers="headers"
        :page="page"
    />
</template>

<script>
    import Resource from '../components/page/Resources';

    export default {
        components: {
            Resource,
        },

        props: {
            page: Number,
        },

        computed: {
            headers() {
                return [
                    {text: 'Назва', value: 'name'},
                ];
            },
        },
    }
</script>
