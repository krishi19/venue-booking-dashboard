<template>
<!--  <div>-->
<!--    <div>Hello, Example Component!</div>-->
<!--  </div>-->
      <router-view />
<!--      <v-overlay :value="overlay" style="z-index: 100000">-->
<!--            <v-progress-circular indeterminate size="64"></v-progress-circular>-->
<!--      </v-overlay>-->
</template>
<script>
  // import {mapGetters} from 'vuex'
export default {
  name: "App",
  computed:{
    // ...mapGetters(['overlay'])
  }
};
</script>
