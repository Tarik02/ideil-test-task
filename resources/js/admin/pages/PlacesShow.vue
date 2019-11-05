<template>
    <resources-show
        ref="resource"
        name="places"
        identifier="slug"
        @buildFormData="buildFormData"
        v-slot="{ data, meta }"
    >
        <v-card-title>{{ $t('resources.places.singular') }}: {{ data.name }}</v-card-title>

        <v-card-text>
            <validation-provider
                name="name"
                rules="required|min:5|max:128"
                v-slot="{ errors }"
            >
                <v-text-field
                    v-model="data.name"
                    :error-messages="errors"
                    :label="$t('fields.name')"
                    required
                />
            </validation-provider>

            <validation-provider
                name="slug"
                rules="required|slug|max:128"
                v-slot="{ errors }"
            >
                <v-text-field
                    v-model="data.slug"
                    :error-messages="errors"
                    :prefix="meta.url_prefix"
                    :label="$t('fields.slug')"
                    required
                />
            </validation-provider>

            <validation-provider
                name="description"
                rules="required|max:20480"
                v-slot="{ errors }"
            >
                <v-textarea
                    v-model="data.description"
                    :error-messages="errors"
                    :label="$t('fields.description')"
                    required
                />
            </validation-provider>

            <v-rating
                v-model="data.mark"
                length="10"
            />

            <v-text-field
                v-model="data.mark"
                :label="$t('fields.mark10')"
                disabled
            />

            <v-row>
                <v-col>
                    <v-text-field
                        v-model="data.likes_count"
                        :label="$t('fields.likes')"
                        prepend-icon="thumb_up"
                        disabled
                    />
                </v-col>

                <v-col>
                    <v-text-field
                        v-model="data.dislikes_count"
                        :label="$t('fields.dislikes')"
                        prepend-icon="thumb_down"
                        disabled
                    />
                </v-col>
            </v-row>

            <v-toolbar flat>
                <v-toolbar-title>
                    Додаткові поля
                </v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-btn color="primary" class="mb-2" icon @click="addField">
                    <v-icon>add</v-icon>
                </v-btn>
            </v-toolbar>

            <v-simple-table>
                <thead>
                    <th>Ключ</th>
                    <th>Значення</th>
                    <th>Дії</th>
                </thead>
                <draggable
                    v-model="data.fields"
                    handle=".drag-handle"
                    tag="tbody"
                    :animation="200"
                >
                    <tr v-for="(item, index) in data.fields">
                        <td>
                            <validation-provider
                                name="key"
                                rules="required|max:64"
                                v-slot="{ errors }"
                            >
                                <v-text-field
                                    v-model="item.key"
                                    single-line
                                    :error-messages="errors"
                                    required
                                />
                            </validation-provider>
                        </td>
                        <td>
                            <validation-provider
                                name="value"
                                rules="required|max:64"
                                v-slot="{ errors }"
                            >
                                <v-text-field
                                    v-model="item.value"
                                    single-line
                                    :error-messages="errors"
                                    required
                                />
                            </validation-provider>
                        </td>
                        <td>
                            <v-icon class="drag-handle mr-2">drag_handle</v-icon>
                            <v-icon
                                small
                                @click="deleteField(index)"
                            >delete</v-icon>
                        </td>
                    </tr>
                </draggable>
            </v-simple-table>

            <v-toolbar flat>
                <v-toolbar-title>
                    Фотогаларея
                </v-toolbar-title>
            </v-toolbar>

            <gallery :photos="model.photos" />
        </v-card-text>
    </resources-show>
</template>

<script>
    import Vue from 'vue';
    import ResourcesShow from '../components/page/ResourcesShow';
    import Gallery from '../components/Gallery';

    export default {
        components: {
            ResourcesShow,
            Gallery,
        },

        data: () => ({
            //
        }),

        computed: {
            model() {
                return this.$refs.resource.data;
            },
        },

        mounted() {
        },

        methods: {
            addField() {
                const {model} = this;
                model.fields = [
                    ...model.fields,
                    { key: '', value: '' },
                ];
            },
            moveFieldUp(index) {
                if (index === 0) {
                    return;
                }
                const {model} = this;
                Vue.set(model, 'fields', [
                    ...model.fields.slice(0, index - 1),
                    model.fields[index],
                    model.fields[index - 1],
                    ...model.fields.slice(index + 1),
                ]);
            },
            moveFieldDown(index) {
                const {model} = this;
                if (index === model.fields.length - 1) {
                    return;
                }
                Vue.set(model, 'fields', [
                    ...model.fields.slice(0, index),
                    model.fields[index + 1],
                    model.fields[index],
                    ...model.fields.slice(index + 2),
                ]);
            },
            deleteField(index) {
                const {model} = this;
                Vue.set(model, 'fields', [
                    ...model.fields.slice(0, index),
                    ...model.fields.slice(index + 1),
                ]);
            },
            buildFormData(formData) {
                // for (const field of [
                //     'slug',
                //     'name',
                //     'description',
                //     'mark',
                // ]) {
                //     formData.append(field, this.model[field]);
                // }
                //
                // for (const {key, value} of this.model.fields) {
                //     formData.append(`fields[${key}]`, value);
                // }

                const photos = [];
                let photoFileCounter = 0;
                for (const photo of this.model.photos) {
                    if (!photo.file) {
                        photos.push({
                            id: photo.id,
                            visible: photo.visible,
                        });
                    } else {
                        let fileIndex = photoFileCounter++;
                        photos.push({
                            file: fileIndex,
                            visible: photo.visible,
                        });
                        formData.append('photos[]', photo.file);
                    }
                }

                formData.append('json', JSON.stringify({
                    ..._.pick(this.model, [
                        'id',
                        'slug',
                        'name',
                        'description',
                        'mark',
                        'fields',
                    ]),
                    photos,
                }));
            },
        },
    };
</script>
