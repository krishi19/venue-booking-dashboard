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
                    <v-row>
                        <v-col cols="6">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Owner Name"
                                    rules="required"
                                    vid="name"
                            >
                                <v-text-field
                                        outlined
                                        dense
                                        label="Owner Name
                            "
                                        :error-messages="errors"
                                        v-model="form.name"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="6">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Email"
                                    rules="required|email"
                                    vid="email"
                            >
                                <v-text-field
                                        label="Email
                            "
                                        outlined
                                        dense
                                        :error-messages="errors"
                                        v-model="form.email"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="6">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Mobile"
                                    rules="required"
                                    vid="mobile"
                            >
                                <v-text-field
                                        outlined
                                        dense
                                        label="Mobile
                            "
                                        :error-messages="errors"
                                        v-model="form.mobile"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                        <v-col cols="6">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Phone"
                                    vid="phone"
                            >
                                <v-text-field
                                        label="Phone
                            "
                                        outlined
                                        dense
                                        :error-messages="errors"
                                        v-model="form.phone"
                                ></v-text-field>
                            </ValidationProvider>
                        </v-col>
                    </v-row>
                    <v-row>
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
                        <v-col cols="6">
                            <ValidationProvider
                                    v-slot="{ errors }"
                                    name="Password"
                                    rules="required"
                                    vid="password"
                            >
                                <v-text-field
                                        label="Password
                            "
                                        outlined
                                        dense
                                        :error-messages="errors"
                                        v-model="form.password"
                                ></v-text-field>
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
        props: ["editMode", 'owner'],
        data() {
            return {
                cardTitle: "Add New Owner",
                form: {
                    id: "",
                   name:'',
                    email:'',
                    mobile:'',
                    phone:'',
                    address:'',
                    password:''
                },
                loader: false,
                disable: false,
                tab:''
            };
        },
        methods: {
            submitForm() {
                this.$refs.observer.validate()
                    .then(res => {
                        if (res) {
                            this.loader = true;
                            this.disable = true;
                            if (this.editMode) {
                                this.$store
                                    .dispatch("UPDATE_ADMIN", this.form)
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
                                    .dispatch("CREATE_ADMIN", this.form)
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
            ...mapGetters(['allLocales']),
        },
        mounted() {
            if (this.editMode) {
                this.cardTitle = "Edit Owner Detail";
                this.form.id = this.owner.id;
                this.form.name = this.owner.name;
                this.form.email = this.owner.email;
                this.form.mobile = this.owner.mobile;
                this.form.phone = this.owner.phone;
                this.form.address = this.owner.address;
                this.form.password = this.owner.password;
            }
        }
    }
</script>
