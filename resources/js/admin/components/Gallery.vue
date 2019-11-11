<template>
    <div>
        <draggable
            handle=".drag-handle"
            :value="photos"
            :animation="200"
            :move="event => event.relatedContext.index !== photos.length + 1"
            @input="$emit('input', $event)"
            @start="drag = true"
            @end="drag = false"
        >
            <transition-group tag="v-row">
                <v-col
                    v-for="photo in photos"
                    v-if="!photo.deleted"
                    :key="photo.id"
                    xs="12"
                    sm="4"
                    md="4"
                    lg="3"
                    xl="2"
                    style="width: 0;"
                >
                    <v-hover v-slot="{ hover }">
                        <v-card height="100%">
                            <v-img
                                :src="photo.preview"
                                contain
                                height="100%"
                            />

                            <v-fade-transition>
                                <v-overlay
                                    v-if="hover && !drag || !photo.visible"
                                    absolute
                                    color="black"
                                >
                                    <v-row justify="center">
                                        <v-btn text icon @click="photo.visible = !photo.visible">
                                            <v-icon>
                                                {{photo.visible ? 'visibility' : 'visibility_off' }}
                                            </v-icon>
                                        </v-btn>

                                        <v-btn text icon @click="zoomPhoto(photo)">
                                            <v-icon>zoom_in</v-icon>
                                        </v-btn>

                                        <v-btn text icon @click="deletePhoto(photo)">
                                            <v-icon>delete</v-icon>
                                        </v-btn>
                                    </v-row>

                                    <v-row justify="center">
                                        <div class="pa-3 drag-handle">
                                            <v-icon>drag_handle</v-icon>
                                        </div>
                                    </v-row>
                                </v-overlay>
                            </v-fade-transition>
                        </v-card>
                    </v-hover>
                </v-col>
                <v-col
                    key="new"
                    xs="12"
                    sm="4"
                    md="4"
                    lg="3"
                    xl="2"
                >
                    <v-hover v-slot="{ hover }">
                        <v-responsive aspect-ratio="1" min-height="100%">
                            <v-card
                                v-drop="loadPhoto"
                                height="100%"
                                class="photo-button drop-area"
                                @click="openPhotoSelectDialog"
                            >
                                <v-container fill-height>
                                    <v-row justify="center">
                                        <v-icon>add_a_photo</v-icon>
                                    </v-row>
                                </v-container>

                                <v-fade-transition>
                                    <v-overlay
                                        v-if="hover"
                                        absolute
                                        color="black"
                                    >
                                        <v-container fill-height>
                                            <v-row
                                                justify="center"
                                                class="body-2 pa-3 text-center no-selection"
                                            >
                                                Натисність для вибору файлів, або перенесіть їх
                                            </v-row>
                                        </v-container>
                                    </v-overlay>
                                </v-fade-transition>
                            </v-card>
                        </v-responsive>
                    </v-hover>
                </v-col>
            </transition-group>
        </draggable>

        <file-input
            v-model="showFileInput"
            multiple
            @load="loadPhoto"
        />

        <v-dialog
            :value="showZoomedPhoto"
            @input="showZoomedPhoto = $event"
            fullscreen
        >
            <v-card v-if="zoomedPhoto" flat tile>
                <v-toolbar color="transparent" absolute flat class="zoomed-photo-toolbar">
                    <v-spacer />
                    <v-btn icon @click="showZoomedPhoto = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-toolbar>

                <v-img
                    :lazy-src="zoomedPhoto.preview"
                    :src="zoomedPhoto.original"
                    max-height="100vh"
                    contain
                />
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import Vue from 'vue';

    import FileInput from './FileInput';

    export default {
        components: {
            FileInput,
        },

        props: {
            photos: Array,
        },

        data: () => ({
            idCounter: 0,
            drag: false,
            showFileInput: false,

            showZoomedPhoto: false,
            zoomedPhoto: undefined,
        }),

        methods: {
            openPhotoSelectDialog() {
                this.showFileInput = true;
            },

            loadPhoto(file) {
                const reader = new FileReader();
                reader.onload = event => {
                    const url = event.target.result;
                    this.photos.push({
                        id: -(++this.idCounter), // for keying in vue template
                        file,
                        preview: url,
                        original: url,
                        visible: true,
                    });
                };
                reader.readAsDataURL(file);
            },

            zoomPhoto(photo) {
                this.showZoomedPhoto = true;
                this.zoomedPhoto = photo;
            },

            deletePhoto(photo) {
                Vue.set(photo, 'deleted', true);
            },
        },
    };
</script>

<style scoped>
    .drag-handle, .photo-button {
        cursor: pointer;
    }

    .no-selection {
        user-select: none;
    }

    .zoomed-photo-toolbar {
        left: 0;
        right: 0;
    }

    .drop-area.highlight {
        background: black;
    }
</style>
