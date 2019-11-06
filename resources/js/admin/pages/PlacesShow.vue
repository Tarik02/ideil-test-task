<template>
    <resources-show
        v-model="model"
        ref="resource"
        name="places"
        identifier="slug"
        :initial-model="initialModel"
        :initial-meta="initialMeta"
        @buildFormData="buildFormData"
    >
        <template v-slot:buttons>
            <v-btn
                text
                @click="$router.push({name: 'places.comments', slug: $route.params.slug})"
            >{{ $t('resources.comments.plural') }}</v-btn>
        </template>

        <template v-slot="{ data, meta }">
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

                <v-text-field
                    :value="createdAt"
                    :label="$t('fields.created_at')"
                    disabled
                />

                <v-text-field
                    :value="updatedAt"
                    :label="$t('fields.updated_at')"
                    disabled
                />

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

                <gallery
                    :photos="model.photos"
                    @input="$set(model, 'photos', $event)"
                />
            </v-card-text>
        </template>
    </resources-show>
</template>

<script>
    import Vue from 'vue';
    import ResourcesShow from '../components/page/ResourcesShow';
    import Gallery from '../components/Gallery';
    import {format, parseISO} from 'date-fns';
    import {uk} from 'date-fns/locale';

    export default {
        components: {
            ResourcesShow,
            Gallery,
        },

        data: () => ({
            model: {},
        }),

        computed: {
            createdAt() {
                if (this.model.created_at) {
                    return format(parseISO(this.model.created_at), 'PPPP', {locale: uk});
                } else {
                    return '-';
                }
            },

            updatedAt() {
                if (this.model.updated_at) {
                    return format(parseISO(this.model.updated_at), 'PPPP', {locale: uk});
                } else {
                    return '-';
                }
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
            deleteField(index) {
                const {model} = this;
                Vue.set(model, 'fields', [
                    ...model.fields.slice(0, index),
                    ...model.fields.slice(index + 1),
                ]);
            },
            buildFormData(formData) {
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
                        formData.append('file[]', photo.file);
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
            initialModel() {
                return {
                    slug: '',
                    name: '',
                    description: '',
                    mark: 10,
                    fields: [],
                    photos: [],
                };
            },
            initialMeta() {
                return {
                    url_prefix: `${location.protocol}//${location.hostname}/places/`,
                };
            },
        },
    };
</script>
