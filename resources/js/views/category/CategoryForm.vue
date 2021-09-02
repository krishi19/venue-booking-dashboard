<template>
    <validation-observer ref="observer" v-slot="{ validate, reset, invalid }">
        <v-form v-on:submit.prevent="submitForm()">
            <v-card>
                <v-toolbar color="primary" class="headline" dark>
                    {{ cardTitle }}
                    <v-spacer></v-spacer>
                    <v-btn small icon @click="$emit('closeDialog')">
                        <v-icon size="23">mdi-close-circle</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-divider></v-divider>
                <v-container>
                    <v-row>
                        <v-col cols="8">
                            <v-row>
                                <v-col>
                                    <ValidationProvider
                                            v-slot="{ errors }"
                                            name="Name in English"
                                            rules="required"
                                            vid="en_name"
                                    >
                                        <v-text-field
                                                outlined
                                                dense
                                                label="
                              Category Name (English)
                            "
                                                :error-messages="errors"
                                                v-model="categoryForm.en_name"
                                        ></v-text-field>
                                    </ValidationProvider>

                                </v-col>
                            </v-row>

                        </v-col>
                        <v-col>
                            <v-row>
                                <v-col>
                                    <v-card>
                                        <div style="margin-bottom: -12px">
                                            <v-img style="background-size: contain" height="130px" width="185px" :src="image_url"/>
                                        </div>
                                        <v-card-text style="margin-top:8px">
                                            <v-file-input
                                                    accept="image/png, image/jpeg, image/bmp"
                                                    prepend-icon="mdi-camera"
                                                    label="Image"
                                                    v-model="image"
                                                    @change="handleFileChange()"
                                                    :clearable="false"
                                                    truncate-length="9"
                                                    dense
                                            />
                                            <!-- </ValidationProvider> -->
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>
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
    import axios from "axios";

    export default {
        props: ["editMode", 'category'],
        data() {
            return {
                cardTitle: "Add New Category",
                categories: [],
                sub_categories: [],
                sub_cat: false,
                categoryForm: {
                    id: "",
                    en_name: '',
                    np_name: '',
                    category_id: '',
                    specifications:[]
                },
                image:'',
                image_url:'',
                allCategories: [],
                loader: false,
                disable: false,
                tab: '',
                loadingSpecifications:false,
                searchInput:''
            };
        },
        methods: {
            handleFileChange(event) {
                this.image
                    ? (this.image_url = URL.createObjectURL(this.image))
                    : "";
            },

            submitForm() {
                this.$refs.observer.validate()
                    .then(res => {
                        if (res) {
                            this.loader = true;
                            this.disable = true;
                            let formData = new FormData();
                            formData.append("image_file", this.image);
                            Object.entries(this.categoryForm).forEach((entry) => {
                                const [key, value] = entry;
                                formData.append(key,value);
                            });

                            if (this.editMode) {
                                formData.append("_method", "PUT");

                                this.$store
                                    .dispatch("UPDATE_CATEGORY", {form:formData,id:this.categoryForm.id})
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
                                    .dispatch("CREATE_CATEGORY",formData)
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
        },
        computed: {
            ...mapGetters(['getAllCategories', 'allLocales','getAllAdmins']),
        },
        mounted() {
            this.allCategories = [...this.getAllCategories]

            if (this.editMode) {
                this.cardTitle = "Edit Category Detail";
                this.categoryForm.id = this.category.id;
                this.categoryForm.en_name = this.category.en_name;
                this.categoryForm.np_name = this.category.np_name;
                this.categoryForm.category_id = this.category.category_id?this.category.category_id:'';
                this.categoryForm.specifications = this.category.specifications;
                this.image_url=this.category.image_url
                this.image=this.category.image
                let filtered_categories = []
                this.allCategories.map(c => {
                    if (c.id != this.category.id) {
                        let index = c.parent_ids.findIndex(x => x == this.category.id)
                        if (index < 0) filtered_categories.push(c);
                    }
                })
                this.allCategories = filtered_categories
            }
        }
    }
</script>
