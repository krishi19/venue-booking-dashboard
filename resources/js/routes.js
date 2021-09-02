import Vue from "vue";
import Router from "vue-router";
import store from "./store/store";
// import { AclRule } from 'vue-acl'
import Login from './views/auth/Login'
Vue.use(Router);
const router = new Router({
    mode: "history",
    routes: [
        {
          path:'',
          redirect:'login'
        },
        {
            path: '/',
            component: {
                render(c) {
                    return c('router-view')
                }
            },
            children: [

                {
                    path: "login",
                    name: "login",
                    meta: {title: "Login",isVisitor:true},
                    component: lazyLoad('auth/Login')
                },
                {
                    path: "",
                    meta: {
                        title: "Main",
                        requiresAuth: true
                    },
                    component: lazyLoad("main/Main"),
                    children: [
                        {
                            path: "/venue-owners",
                            name: "venue-owners",
                            meta: { title: "Venue Owners"},
                            component: lazyLoad("venue-owner/Main")
                        },
                        {
                            path: "/venues",
                            name: "venues",
                            meta: { title: "Venues"},
                            component: lazyLoad("venue/Main")
                        },
                        {
                            path: "/venues/create",
                            name: "create-venue",
                            meta: { title: "Venues"},
                            component: lazyLoad("venue/Form")
                        },
                        {
                            path: "/venues/:id/edit",
                            name: "edit-venue",
                            meta: { title: "Venues"},
                            component: lazyLoad("venue/Form")
                        },
                        {
                            path: "/bookings",
                            name: "bookings",
                            meta: { title: "Bookings"},
                            component: lazyLoad("booking/Main")
                        },
                        {
                            path: "/categories",
                            name: "categories",
                            meta: { title: "Categories"},
                            component: lazyLoad("category/CategoryMain")
                        },

                    ]
                },
            ]
        }
    ]
});

function lazyLoad(component) {
    return () => import("./views/" + component);
}

router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    if (to.matched.some(record => record.meta.isVisitor)) {
        if (store.getters.isLoggedIn) {
            if (localStorage.getItem("role") == "Admin") {
                next({ path: "/categories" });
                return;
            } else {
                next({ path: "/venues" });
                return;
            }
        }
        next();
    } else if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.getters.isLoggedIn) {
            next({ path: "/login" });
            return;
        }
        next();
    } else {
        next();
    }
});

export default router;
