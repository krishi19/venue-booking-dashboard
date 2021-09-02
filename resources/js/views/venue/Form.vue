<template>
    <validation-observer ref="observer" v-slot="{ validate, reset, invalid }">
        <v-form v-on:submit.prevent="submitForm()">
            <v-card>
                <v-card-title>
                    {{ cardTitle }}
                </v-card-title>
                <v-container>
                    <v-row dense>
                        <v-col cols="4">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Category"
                                    rules="required"
                                    vid="category"
                            >
                                <v-autocomplete
                                        :error-messages="errors"
                                        v-model="form.category_id"
                                        :items="getAllCategories"
                                        item-value="id"
                                        item-text="en_name"
                                        dense
                                        outlined
                                        clearable
                                        placeholder="Select category"
                                        label="Category"
                                ></v-autocomplete>
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="4">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Venue Name"
                                    rules="required"
                                    vid="name"
                            >
                                <v-text-field
                                        outlined
                                        dense
                                        label="Venue Name
                            "
                                        :error-messages="errors"
                                        v-model="form.name"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="4">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Contact"
                                    rules="required"
                                    vid="contact"
                            >
                                <v-text-field
                                        outlined
                                        dense
                                        label="Contact
                            "
                                        placeholder="Mobile/Phone"
                                        :error-messages="errors"
                                        v-model="form.contact"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
<!--                            <gmap-map :center="form.gps" :zoom="12"-->
<!--                                      ref="map"-->
<!--                                      @center_changed="updateLocation"-->
<!--                                      @idle="sync"-->
<!--                                      class="map-container">-->
<!--                            </gmap-map>-->
                            <GmapMap
                                    ref="mapRef"
                                    :center="position"
                                    :zoom="13"

                                    style="max-width: 900px; height: 350px"

                            >
                                <GmapMarker
                                        :position="position"
                                        :clickable="true"
                                        :draggable="true"
                                        @drag="updateLocation"
                                />
                            </GmapMap>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="4">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Coordinates"
                                    rules="required"
                                    vid="gps"
                            >
                                <v-text-field
                                        readonly
                                        outlined
                                        dense
                                        label="GPS co-ordinates
                            "
                                        :error-messages="errors"
                                        v-model="computedGps"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="6">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Address"
                                    rules="required"
                                    vid="address"
                            >
                                <v-text-field
                                        outlined
                                        dense
                                        label="Address
                            "
                                        :error-messages="errors"
                                        v-model="form.address"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="4">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Cost"
                                    :rules="{required:true,min_value:1}"
                                    vid="cost"
                            >
                                <v-text-field
                                        type="number"
                                        min="1"
                                        outlined
                                        dense
                                        label="Cost
                            "
                            suffix="Per Person"
                                        prefix="Rs"
                                        :error-messages="errors"
                                        v-model="form.cost"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="4">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Capacity"
                                    vid="capacity"
                                    rules="required"
                            >
                                <v-text-field
                                        label="Capacity of People
                            "
                                        placeholder="like 150-200"
                                        outlined
                                        dense
                                        :error-messages="errors"
                                        v-model="form.capacity"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                    </v-row>

                    <v-row dense>
                        <v-col cols="8">
                            <v-textarea
                                    label="Short Description"
                                    rows="4"
                                    auto-grow
                                    outlined
                                    v-model="
                                                form.description
                                            "
                            ></v-textarea>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="8">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Images"
                                    rules="required"
                                    vid="images"
                            >
                                <vue-dropzone
                                        v-model="image_array"
                                        ref="dropZone"
                                        id="drop"
                                        duplicateCheck
                                        :useCustomSlot="true"
                                        style="padding: 0; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2)"
                                        @vdropzone-file-added="fileAdded"
                                        @vdropzone-max-files-reached="(file) => maxFilesReached(file)"
                                        v-on:vdropzone-removed-file="(file) => removeImage(file)"
                                        :options="dropzoneOptions">

                                    <div class="dropzone-custom-content" style="color: cadetblue">
                                        <i class="fas fa-cloud-upload-alt fa-3x"></i>
                                        <h4 class="dropzone-custom-title mb-0 mt-3">
                                            Drag & Drop
                                        </h4>
                                        <div class="subtitle">
                                            or click to add your image
                                        </div>
                                    </div>
                                </vue-dropzone>
                                <span class="error--text ml-3" style="font-size: 14px">{{
                      errors[0]
                    }}</span>
                            </ValidationProvider>
                            <div
                                    style="
                    color: gray;
                    font-size: 13px;
                    text-align: left;
                    padding-bottom: 10px;
                  "
                            >
                                Multiple images can be uploaded at once. Maximum 4 images allowed.
                            </div>
                        </v-col>

                    </v-row>
                    <v-row>
                        <v-col>

                        </v-col>
                    </v-row>

                </v-container>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn v-if="!editMode" type="submit"
                           :type="!disable?'submit':''" :disabled="disable" color="primary"
                           class="elevation-2" tile>
                        <v-progress-circular
                                v-if="loader"
                                width="3"
                                indeterminate
                                size="18"
                                color="primary"
                        >&nbsp;&nbsp;
                        </v-progress-circular>
                        <v-icon left v-if="!loader">mdi-content-save</v-icon>
                        Save
                    </v-btn>
                    <v-btn v-if="editMode" :disabled="disable"
                           :type="!disable?'submit':''" tile color="primary">
                        <v-progress-circular
                                v-if="loader"
                                width="3"
                                indeterminate
                                size="18"
                                color="primary"
                        >&nbsp;&nbsp;
                        </v-progress-circular>
                        <v-icon left v-if="!loader">mdi-content-save</v-icon>
                        Update
                    </v-btn>
                    <v-btn color="error" class="elevation-2" tile :to="{name:'venues'}">
                        <v-icon left>mdi-close</v-icon>
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </validation-observer>
</template>
<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import {mapGetters} from "vuex";

    export default {
        components: {
            vueDropzone: vue2Dropzone
        },
        data() {
            return {
                dropzoneOptions: {
                    url: 'https://httpbin.org/post',
                    maxFilesize: 5,
                    acceptedFiles: ".jpeg,.jpg,.png,.gif",
                    addRemoveLinks: true,
                    thumbnailWidth: 120, // px
                    thumbnailHeight: 120,
                    maxFiles: 4,
                    duplicateCheck: true,
                },
                cardTitle: "Add New Venue",
                form: {
                    id: "",
                    name: '',
                    contact: '',
                    address: '',
                    category_id: '',
                    gps: {lat:27.69305928254161,lng:85.28244430845949},
                    capacity: '',
                    cost: '',
                    description: ''
                },
                image_array: [],

                loader: false,
                disable: false,
                tab: '',
                loading: false,
                editMode: false,
                position: {lat:27.69305928254161,lng:85.28244430845949},

            };
        },
        methods: {
            maxFilesReached(file) {
                this.image_array = file.filter((x) => {
                    return x.status != "error";
                });
                file.map((x) => {
                    if (x.status == "error") this.$refs.dropZone.removeFile(x);
                });
            },

            fileAdded(file) {
                this.image_array.push(file);
            },
            removeImage(file) {
                let index = this.image_array.findIndex((x) => x.name == file.name);
                if (index > -1) this.image_array.splice(index, 1);
            },
            submitForm() {
                this.$refs.observer.validate()
                    .then(res => {
                        if (res) {
                            this.loader = true;
                            this.disable = true;
                            this.$store.commit('TOGGLE_OVERLAY')
                            let formData = new FormData();
                            this.image_array.map((x) => {
                                formData.append("image_array[]", x);
                            });
                            Object.entries(this.form).forEach((entry) => {
                                let [key, value] = entry;
                                if (key == 'gps')
                                    value=JSON.stringify(this.form.gps)
                                formData.append(key, value);
                            });
                            if (this.editMode) {
                                formData.append("_method", "PUT");

                                this.$store
                                    .dispatch("UPDATE_VENUE", {form: formData, id: this.form.id})
                                    .then(r => {
                                        this.$router.push({name: 'venues'})
                                        Toast.fire({
                                            icon: "success",
                                            title: "Updated Successfully."
                                        });
                                    })
                                    .catch((err) => {
                                        if (err.response.status == "422")
                                            this.$refs.observer.setErrors(err.response.data.errors)
                                    })
                                    .finally(() => {
                                        this.disable = false;
                                        this.loader = false
                                        this.$store.commit('TOGGLE_OVERLAY')

                                    });
                            } else {
                                this.$store
                                    .dispatch("CREATE_VENUE", formData)
                                    .then(() => {
                                        this.$router.push({name: 'venues'})
                                        Toast.fire({
                                            icon: "success",
                                            title: "Created Successfully."
                                        });
                                    })
                                    .catch((err) => {
                                        if (err.response.status == "422")
                                            this.$refs.observer.setErrors(err.response.data.errors)
                                    })
                                    .finally(() => {
                                        this.disable = false;
                                        this.loader = false
                                        this.$store.commit('TOGGLE_OVERLAY')

                                    });
                            }
                        }
                    })
            },
            getCategories() {
                this.loading = true;
                this.$store.dispatch("ALL_CATEGORY", {
                    params: {
                        search: this.search,
                    }
                }).finally(() => {
                    this.loading = false
                })
            },
            updateLocation(location) {
                this.form.gps = {
                    lat: location.latLng.lat(),
                    lng: location.latLng.lng(),
                };
                // this.debounceGeo()
            },
            debounceGeo: _.debounce(function() {
                    this.geocodeLatLng()
            }, 500),
            geocodeLatLng() {
                    var geocoder = new google.maps.Geocoder();
                    const latlng = {
                        lat: parseFloat(this.form.gps.lat),
                        lng: parseFloat(this.form.gps.lng),
                    };
                    return new Promise((resolve, reject) =>{
                        geocoder.geocode({'location': latlng }, (results, status)=>{
                            if (status === 'OK') {
                                if (results[0]) {
                                    this.form.address=results[0].formatted_address;
                                } else {
                                    console.log(status);
                                    window.alert('No results found');
                                    return null
                                }
                            }
                        })
                    });
    // .then(data => {
    //                 console.log(data)
    //                 this.formatedAddresses = data
    //             })
            }
        },
        computed: {
            ...mapGetters(['allLocales', 'getAllCategories']),
            computedGps:{
                get(){
                    return JSON.stringify(this.form.gps)
                }
            }
        },
        mounted() {
            if (this.$route.params.id) this.editMode = true
            if (!this.$route.params.id) {
                setTimeout(() => {
                    // this.geocodeLatLng()
                }, 500)
            }
            this.getCategories()
            if (this.editMode) {
                this.cardTitle = "Edit Venue Detail";
                this.$store.commit('TOGGLE_OVERLAY')
                this.$store.dispatch('VENUE_DETAIL', {id: this.$route.params.id})
                    .then(response => {
                        this.$refs.dropZone.removeAllFiles(true);

                        this.form = {...response.data.data}
                        this.position=this.form.gps=JSON.parse(this.form.gps)

                        if (this.form.image_urls.length) {
                            let length = this.form.image_urls.length;
                            let i = 0;
                            for (i = 0; i <= (length - 1); i++) {
                                console.log(this.form.image_urls)
                                let file = {
                                    size: 200000,
                                    name: this.form.images[i],
                                    type: "image/*",
                                };
                                let url = this.form.image_urls[i];

                                this.$refs.dropZone.manuallyAddFile(file, url);
                                this.image_array.push(this.form.images[i]);


                            }
                        }

                    })
                    .catch((err) => {

                    })
                    .finally(() => {
                        this.$store.commit('TOGGLE_OVERLAY')
                    })

            }
        }
    }
</script>
<style>

    .vue-dropzone > .dz-preview .dz-remove {
        padding: 0;
        padding-left: 5px;
        padding-right: 5px;
        font-size: 10px;
    }

    .dropzone .dz-preview {
        border: 0.1rem solid #dfe3e8;
        margin: 14px;
        z-index: 1;
    }

    .dropzone .dz-message {
        margin: 1em;
        text-align: center;
        /*font-size: 28px;*/
    }

    .vue-dropzone > .dz-preview .dz-details {
        background: rgba(28, 29, 31, 0.2) !important;
    }

    .dz-clickable {
        border: 1px dashed;
    }
</style>