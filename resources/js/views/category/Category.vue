<template>
  <div>
    <v-tooltip  bottom>
      <template v-slot:activator="{ on }">
    <v-btn icon x-small @click.stop="updateCategory(category)" color="primary">
      <v-icon v-on="on">mdi-pencil</v-icon>
    </v-btn>
      </template>
      <span>Edit</span>
    </v-tooltip>
    <v-tooltip bottom>
      <template v-slot:activator="{ on }">
    <v-btn class="ml-2" icon x-small @click.stop="deleteCategory(category)" :v-on="on" color="error" >
      <v-icon v-on="on">mdi-delete</v-icon>
    </v-btn>
      </template>
      <span>Delete</span>
    </v-tooltip>
  </div>
</template>

<script>

export default {
  name: "Category",
  props: ["category"],
  methods: {
    updateCategory(category) {
      this.$emit("editBtn",category);
    },
    deleteCategory(category) {
      let message = "You won't be able to revert this!";
      // if (category.sub_category.length)
      //   message =
      //     "This category contain sub category and You won't be able to revert this!";
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
            .dispatch("DELETE_CATEGORY", category)
            .then(() => {
              Toast.fire({
                icon: "success",
                title: "Category Deleted Successfully."
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
