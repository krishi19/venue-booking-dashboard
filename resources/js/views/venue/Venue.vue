<template>
  <div>
    <v-tooltip  bottom>
      <template v-slot:activator="{ on }">
    <v-btn icon x-small :to="{name:'edit-venue',params:{id:venue.id}}" color="primary">
      <v-icon v-on="on">mdi-pen</v-icon>
    </v-btn>
      </template>
      <span>Edit</span>
    </v-tooltip>
    <v-tooltip bottom>
      <template v-slot:activator="{ on }">
    <v-btn class="ml-2" icon x-small @click="deleteSpec(venue)" :v-on="on" color="error" >
      <v-icon v-on="on">mdi-delete</v-icon>
    </v-btn>
      </template>
      <span>Delete</span>
    </v-tooltip>
  </div>
</template>

<script>

export default {
  name: "Venue",
  props: ["venue"],
  // components: { Category },
  methods: {
    update(venue) {
      // this.$store.dispatch("SELECTED_CATEGORY", venue);
      this.$emit("editBtn",venue);
    },
    deleteSpec(venue) {
      let message = "You won't be able to revert this!";
      Swal.fire({
        title: "Are you sure?",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          this.$store
            .dispatch("DELETE_VENUE", venue)
            .then(() => {
              Toast.fire({
                icon: "success",
                title: "Deleted Successfully."
              });
            })

        }
      });
    }
  }
};
</script>

<style scoped>
</style>
