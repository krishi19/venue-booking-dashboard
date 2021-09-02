import Vue from "vue";
import router from "./routes";
import store from "./store/store";
import Axios from "axios";

Vue.http = Vue.prototype.$http = Axios;


// window.axios.interceptors.request.use(
//     config => {
//         config.headers['Authorization'] = localStorage.getItem("token")?localStorage.getItem("token"):'';
//         config.headers['X-Requested-With'] = 'XMLHttpRequest';
//         return config;
//     },
//     error => {
//         Promise.reject(error)
//     });
Vue.prototype.$http.interceptors.response.use(
    response => {
        return Promise.resolve(response);
    },
    error => {
        if (error.response.status == 401 && router.currentRoute.name!='login') {
            Toast.fire({
                icon: "error",
                title: 'Something went wrong. Please login again to continue',
            })
            store.dispatch("logOut");
            router.push({ name: "login" });
        }
        if (error.response.status==403){
            Toast.fire({
                icon: "error",
                title: 'User does not have enough permission. Please contact administrator',
            })
        }
        else if (error.response.status==404){
            Toast.fire({
                icon: "error",
                title: 'Resource not found. Please refresh the browser or contact administrator',
            })
        }

        else if (error.response.status==500&&error.response.data.message=='Selected parent category is already assigned on child categories hierarchy'){
            Toast.fire({
                icon: "error",
                title: 'Selected parent category is already assigned on child categories hierarchy',
            })
        }
        else if (error.response.status != "422"&&error.response.status != "403"&&error.response.status != "404"&&error.response.status != "403"&&error.response.config.method== "delete") {
            Toast.fire({
                icon: "error",
                title: 'Could not delete the record because it is associated with another module(s)',
            })
        }
        else{
            Toast.fire({
                icon: "error",
                title: error,
            })
            // this.errors = errors.response.data.errors;
        }
        return Promise.reject(error);
    }
);