<template>
    <validation-observer ref="observer" v-slot="{ validate, reset, invalid }">
        <v-form v-on:submit.prevent="submitForm()">
            <v-card>
                <v-toolbar color="primary" class="headline" dark>
                    <v-icon class="mr-3" v-if="!editMode">fa-plus</v-icon>
                    {{ cardTitle }}
                </v-toolbar>
                <v-divider></v-divider>
                <v-container>
                    <v-row dense>
                        <v-col cols="6">
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
                        <v-col cols="6">
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
                        <v-col cols="6">
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
                                        :error-messages="errors"
                                        v-model="form.contact"
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
                        <v-col cols="6">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Cost"
                                    rules="required"
                                    vid="cost"
                            >
                                <v-text-field
                                        outlined
                                        dense
                                        label="Cost
                            "
                                        prefix="Rs"
                                        :error-messages="errors"
                                        v-model="form.cost"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="6">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Capacity"
                                    vid="capacity"
                                    rules="required"
                            >
                                <v-text-field
                                        label="Capacity for People
                            "
                                        placholder="like 150-200"
                                        outlined
                                        dense
                                        :error-messages="errors"
                                        v-model="form.capacity"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col>
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
                        <v-col>
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Images"
                                    rules="required"
                                    vid="images"
                            >
                                <v-file-input
                                        small-chips
                                        :rules="rules"
                                        accept="image/png, image/jpeg, image/bmp"
                                        dense
                                        :error-messages="errors"
                                        v-model="form.images"
                                        multiple
                                        label="Venue Images"
                                ></v-file-input>
                            </ValidationProvider>
                        </v-col>

                    </v-row>

                </v-container>
                <v-divider></v-divider>
                <v-card-actions class="justify-end">
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
                    <v-btn color="error" class="elevation-2" tile @click="$emit('closeDialog')">
                        <v-icon left>mdi-close</v-icon>
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </validation-observer>
</template>
<script>
    import {mapGetters} from "vuex";

    export default {
        props: ["editMode", 'venue'],
        data() {
            return {
                cardTitle: "Add New Venue",
                form: {
                    id: "",
                   name:'',
                    email:'',
                    mobile:'',
                    phone:'',
                    address:'',
                    password:'',
                    category_id:'',
                    images:[],
                    description:''
                },
                loader: false,
                disable: false,
                tab:'',
                loading:false,
                rules: [
                    files => !files || !files.some(file => file.size > 5_097_152) || 'Image size should be less than 5 MB!'
                ],
            };
        },
        methods: {
            submitForm() {
                this.$refs.observer.validate()
                    .then(res => {
                        if (res) {
                            this.loader = true;
                            this.disable = true;
                            let formData = new FormData();
                            // formData.append("image_file", this.image);
                            Object.entries(this.form).forEach((entry) => {
                                const [key, value] = entry;
                                formData.append(key,value);
                            });
                            if (this.editMode) {
                                formData.append("_method", "PUT");

                                this.$store
                                    .dispatch("UPDATE_VENUE", this.form)
                                    .then(r => {
                                        this.$emit('closeDialog')
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
                                    });
                            } else {
                                this.$store
                                    .dispatch("CREATE_VENUE", this.form)
                                    .then(() => {
                                        this.$emit("closeDialog")
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
                                    });
                            }
                        }
                    })
            },
            getCategories(){
                this.loading = true;
                this.$store.dispatch("ALL_CATEGORY",{params:{
                        search:this.search,
                    }}).finally(()=>{
                    this.loading=false
                })
            }
        },
        computed: {
            ...mapGetters(['allLocales','getAllCategories']),
        },
        mounted() {
            this.getCategories()
            if (this.editMode) {
                this.cardTitle = "Edit Venue Detail";
                this.form.id = this.venue.id;
                this.form.name = this.venue.name;
                this.form.email = this.venue.email;
                this.form.mobile = this.venue.mobile;
                this.form.phone = this.venue.phone;
                this.form.address = this.venue.address;
                this.form.password = this.venue.password;
            }
        }
    }
</script>
