require('./bootstrap');
require('./interceptor');
window.Vue = require('vue').default;
import router from "./routes";
import store from './store/store'
import Vuex from "vuex";

import Vuetify from "vuetify";
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

import acl from "./mixin/acl";
Vue.mixin(acl)
import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueGoogleMaps
    , {
        load: {
            key: 'AIzaSyCm_usydJPnP-bUn-mr_rY07KmN6YDQo0M',
            libraries: ''
            // callback:'initMap',
            // libraries: 'places', // This is required if you use the Autocomplete plugin
            // OR: libraries: 'places,drawing'
            // OR: libraries: 'places,drawing,visualization'
            // (as you require)

            //// If you want to set the version, you can do so:
            // v: '3.26',
        }
    }
)
// import "material-design-icons-iconfont/dist/material-design-icons.css"; // Ensure you are using css-loader
// import 'mdi/font/css/materialdesignicons.css';

Vue.use(Vuetify);
import ToggleButton from 'vue-js-toggle-button'
Vue.use(ToggleButton)

import Swal from "sweetalert2";
window.Swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    showCloseButton: true,
    timer: 3000,
    timerProgressBar: true,
    onOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});
window.Toast = Toast;

import { extend } from "vee-validate";
import { required,email,min_value} from "vee-validate/dist/rules";

// Add the required rule
extend("required", {
    ...required,
    message: "The {_field_} field is required"
});
extend("email", {
    ...email,
    message: "The {_field_} must be valid"
});
extend("min_value", {
    ...min_value,
    message: "{_field_} must be higher than {min}"
});

import { ValidationProvider } from "vee-validate";
import {ValidationObserver} from 'vee-validate';

// Register it globally
// main.js or any entry file.
Vue.component("ValidationProvider", ValidationProvider);
Vue.component("ValidationObserver", ValidationObserver);

Vue.component('app', require('./App.vue').default);

// Initialize Vue
const app = new Vue({
    el: '#app',
    router,
    store,
    Vuex,
    vuetify: new Vuetify({
    }),
});