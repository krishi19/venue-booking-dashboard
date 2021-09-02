<template>
    <v-app id="inspire">
        <v-content>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="4">
                        <v-card class="elevation-12">
                            <v-toolbar style="background-color: #064770" dark flat>
                                <v-toolbar-title>Login Here</v-toolbar-title>
                                <v-spacer/>
                            </v-toolbar>
                            <v-card-text>
                                <div v-if="error_msg" class="error--text" style="margin-left: 33px;margin-bottom: 5px;font-size: 15px;">
                                    {{error_msg}}
                                </div>
                                <validation-observer ref="observer" v-slot="{ validate, reset, invalid }">
                                    <v-form>
                                        <ValidationProvider
                                                rules="required|email"
                                                name="Email"
                                                v-slot="{ errors }"
                                                vid="email"
                                        >
                                            <v-text-field
                                                    @keyup.enter="login()"
                                                    label="Email*"
                                                    v-model="email"
                                                    name="email"
                                                    prepend-icon="mdi-account"
                                                    type="text"
                                                    :error-messages="errors"
                                            />
                                        </ValidationProvider>
                                        <ValidationProvider
                                                rules="required"
                                                name="Password"
                                                v-slot="{ errors }"
                                                vid="password"
                                        >
                                            <v-text-field
                                                    @keyup.enter="login()"
                                                    id="password"
                                                    v-model="password"
                                                    label="Password*"
                                                    name="password"
                                                    prepend-icon="mdi-lock"
                                                    type="password"
                                                    :error-messages="errors"
                                            />
                                        </ValidationProvider>
                                    </v-form>
                                </validation-observer>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer/>
                                <v-btn
                                        type="submit"
                                        @click="login()"
                                        style="background-color: #064770;color: white;"
                                        :loading="loading"
                                        :disabled="loading"
                                        tile
                                        class="elevation-2"
                                >Login
                                </v-btn
                                >
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        props: {
            source: String
        },
        components: {},
        computed: {},
        watch: {},
        data() {
            return {
                error_msg: '',
                loading: false,
                email: "",
                password: ""
            };
        },
        methods: {
            login() {
                if (this.loading) return;
                this.error_msg = ''
                this.$refs.observer.validate()
                    .then(res => {
                        if (res) {
                            var data = {
                                email: this.email,
                                password: this.password
                            };
                            this.loading = true;
                            this.$store
                                .dispatch("loginAttempt", data)
                                .then(response => {
                                    const responseData = response.data;
                                    if (responseData !== 400){
                                        window.axios = require("axios");
                                        window.axios.defaults.headers.common["X-Requested-With"] =
                                            "XMLHttpRequest";
                                        var token = localStorage.token;
                                        window.axios.defaults.headers.common["Authorization"] =
                                            token;
                                       this.$is('Admin')?this.$router.push({name: "categories"}):this.$router.push({name: "venues"});
                                    }
                                    else {
                                        this.error_msg = 'Invalid email/password'
                                        // Toast.fire({
                                        //     icon: "error",
                                        //     title: 'Invalid email/password',
                                        // })
                                        this.loading = false;
                                    }
                                })
                                .catch(err => {
                                    if (err.response.status == "422") {
                                        this.$refs.observer.setErrors(err.response.data.errors);
                                    } else {
                                        this.error_msg = 'Invalid email/password'
                                        // Toast.fire({
                                        //     icon: "error",
                                        //     title: 'Invalid email/password',
                                        // })
                                    }

                                    this.loading = false;
                                });

                        }
                    })
            }
        },
        mounted() {
        }
    };
</script>
