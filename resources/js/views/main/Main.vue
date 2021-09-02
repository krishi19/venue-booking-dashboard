<template>
  <v-app id="inspire">
    <v-navigation-drawer
            :clipped="$vuetify.breakpoint.lgAndUp"
            app
            :mini-variant="false"
            :expand-on-hover="drawer"
            width="210"
    >
      <Sidebar></Sidebar>
    </v-navigation-drawer>

    <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" app color="primary" dark>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
        <span class="hidden-sm-and-down">
         Venue Recommendation Admin
        </span>
      </v-toolbar-title>
      <v-spacer />
<!--      <v-menu offset-y>-->
<!--        <template v-slot:activator="{ on }">-->
<!--          <v-btn color="transparent mr-2" dark v-on="on">-->
<!--            <v-icon class="mr-2">flag</v-icon>-->
<!--            {{ $i18n.locale }}-->
<!--          </v-btn>-->
<!--        </template>-->
<!--        <v-list>-->
<!--          <v-list-item-->
<!--            v-for="(item, index) in langs"-->
<!--            :key="index"-->
<!--            @click="changeLocale(item.value)"-->
<!--          >-->
<!--            <v-list-item-title>{{ item.name }}</v-list-item-title>-->
<!--          </v-list-item>-->
<!--        </v-list>-->
<!--      </v-menu>-->

      <!-- profile -->

      <!-- logout -->

      <v-menu
              bottom
              origin="center center"
              transition="scale-transition"
              offset-y>
        <template v-slot:activator="{ on }">
          <span style="height: 36px;
    min-width: 64px;
    padding: 7px 10px;color: white;text-transform: uppercase;cursor: pointer;" dark v-on="on">{{$store.getters.role}}</span>
          <v-avatar v-on="on" size="40">
            <img style="cursor:pointer" src="/img/user.png" />
          </v-avatar>
        </template>

        <v-list dense class="custom-list">
<!--          <v-list-item :to="{name:'changePassword'}">-->
<!--            <v-list-item-action class="mr0">-->
<!--              <v-icon size="20">fa-user</v-icon>-->
<!--            </v-list-item-action>-->
<!--            <v-list-item-content class="mr0">-->
<!--              <v-list-item-title>&nbsp;My Profile</v-list-item-title>-->
<!--            </v-list-item-content>-->
<!--          </v-list-item>-->
<!--          <v-list-item :to="{name:'changePassword'}">-->
<!--            <v-list-item-action class="mr0">-->
<!--              <v-icon size="20">fa-user-lock</v-icon>-->
<!--            </v-list-item-action>-->
<!--            <v-list-item-content class="mr0">-->
<!--              <v-list-item-title>&nbsp;Change Password</v-list-item-title>-->
<!--            </v-list-item-content>-->
<!--          </v-list-item>-->

          <v-list-item @click="logOut">
            <v-list-item-action class="mr0">
              <v-icon size="20">mdi-logout</v-icon>
            </v-list-item-action>
            <v-list-item-content class="mr0">
              <v-list-item-title>Logout</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
    <v-content>
      <v-container fluid>
        <router-view></router-view>
<!--        <vue-progress-bar></vue-progress-bar>-->
      </v-container>
    </v-content>
    <v-overlay :value="overlay" style="z-index: 100000">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </v-app>
</template>
<script>
import { mapGetters } from "vuex";
import Sidebar from './Sidebar'

export default {
  props: {
    source: String
  },
  components: { Sidebar },
  computed: {
    ...mapGetters(["allNotification", "notificationCount",'overlay'])
  },
  methods: {
    profile() {
      this.profileShow = true;
    },
    getNotification() {
      this.$store.dispatch("GET_ALL_NOTIFICATION").then(res => {});
    },
    logOut() {
      this.$store
        .dispatch("logOut")
        .then(response => {
          this.$router.push({name:"login"});
        })
        .catch(error => {
          console.log(error);
        });
    },
    // changeLocale(e) {
    //   // console.log(e)
    //   this.$i18n.locale = e;
    //   localStorage.removeItem("locale");
    //   localStorage.setItem("locale", this.$i18n.locale);
    // }
  },
  data: () => ({
    // language: "",
    // langs: [
    //   { name: "japanese", value: "jp" },
    //   { name: "english", value: "en" }
    // ],
    profileShow: false,
    // inject: ["theme"],
    dialog: false,
    drawer: null,
    messages: 0,
    show: false,
    notif: []
  }),

  // created() {
  //   this.$Progress.start();
  //   this.$router.beforeEach((to, from, next) => {
  //     if (to.meta.progress !== undefined) {
  //       let meta = to.meta.progress;
  //       this.$Progress.parseMeta(meta);
  //     }
  //     this.$Progress.start();
  //     next();
  //   });
  //   this.$router.afterEach((to, from) => {
  //     this.$Progress.finish();
  //   });
  //   Echo.private("App.User." + localStorage.user).notification(notification => {
  //     // console.log(notification)
  //     this.$store.commit("UPDATE_COUNT");
  //   });
  //   // Echo.private("App.User." + localStorage.user).listen(".my-event", e => {
  //   //   alert(e);
  //   // });
  // },
  mounted() {
    console.log(this.overlay)
    // this.$store.dispatch("GET_COUNT");
    // this.$Progress.finish();
    // axios.get('order/delayed-payment-notification')
    // .then(res=>{
    //   console.log(res)
    // })
    // this.locale = this.$i18n.locale;
    // console.log(this.$i18n.locale)
  }
};
</script>
<style>
.custom-list {
  padding: 0;
}

.notification:hover {
  background-color: transparent;
}

.bell {
  margin-top: -10px;
  margin-right: -5px;
  border-radius: 50%;
  background-color: red;
  padding: 5px 10px;
  color: white;
}

.mr0 {
  margin: 0 !important;
}

.pd0 {
  padding: 0 !important;
}

.v-list-item--active {
  background: white !important;
}
.router-link-active {
  background: transparent;
}

/*.v-list-item--active {*/
/*	background: #000 !important;*/
/*}*/
.v-list-item--active {
  color: blueviolet;
}
.scrollbar {
  max-height: 300px !important;
  scrollbar-width: thin !important;
  scrollbar-color: #000 #fff;
}
.scrollbar::-webkit-scrollbar {
  width: 7px;
}
/* .scrollbar::-webkit-scrollbar-track {
  background: #000;
} */
.scrollbar::-webkit-scrollbar-thumb {
  background: #000;
}
.v-application .primary--text {
  color: #064770 !important;
  caret-color: #064770 !important;
}
.v-application .primary {
  background-color: #064770 !important;
}
 thead .text-start,
 thead .text-center,table th {
   font-size: 14px !important;
   text-transform: uppercase;
   font-weight: bold;
 }
.v-application--is-ltr .v-list-item__action:first-child, .v-application--is-ltr .v-list-item__icon:first-child {
  margin-right: 7px;
}
.v-card__actions{
  padding: 20px 20px;
}
  .sub-info{
    color: grey;
    font-size: 12px;
  }
</style>
