<template>
  <div>
    <v-tooltip  bottom>
      <template v-slot:activator="{ on }">
    <v-btn icon x-small @click.stop="update(owner)" color="primary">
      <v-icon v-on="on">mdi-pen</v-icon>
    </v-btn>
      </template>
      <span>Edit</span>
    </v-tooltip>
    <v-tooltip bottom>
      <template v-slot:activator="{ on }">
    <v-btn class="ml-2" icon x-small @click="deleteSpec(owner)" :v-on="on" color="error" >
      <v-icon v-on="on">mdi-delete</v-icon>
    </v-btn>
      </template>
      <span>Delete</span>
    </v-tooltip>
  </div>
</template>

<script>

export default {
  name: "Owner",
  props: ["owner"],
  // components: { Category },
  methods: {
    update(owner) {
      // this.$store.dispatch("SELECTED_CATEGORY", owner);
      this.$emit("editBtn",owner);
    },
    deleteSpec(owner) {
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
            .dispatch("DELETE_ADMIN", owner)
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
