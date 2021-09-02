<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="getAllCategories"
      item-key="id"
      :loading="loading"
      :hide-default-footer="true"
      class="elevation-1"
      select="true"
      :options.sync="options"
      @update:options="paginate"

    >
      <template v-slot:top>
        <v-row align="center">
          <v-col cols="4">
            <v-col>
              <v-row>
                <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn icon class="mt-2 ml-2">
                      <b
                        ><v-icon dark v-on="on" size="40" @click="refreshTable"
                          >mdi-refresh</v-icon
                        ></b
                      >
                    </v-btn>
                  </template>
                  <span>"Reload Table"</span>
                </v-tooltip>
              </v-row>
            </v-col>
          </v-col>
        </v-row>
      </template>
      <template v-slot:item.sno="row">
        {{(getAllCategories.map(function(x) {return x.id; }).indexOf(row.item.id))+1}}.
      </template>
      <template v-slot:item.en_name="props">
        {{
          props.item.en_name
        }}
      </template>
      <template v-slot:item.np_name="props">
        {{
          props.item.np_name
        }}
      </template>
      <template v-slot:item.parentCategory="props">
        <span v-if="props.item.parent_category">
          <v-chip @click="" small>
            {{
              props.item.parent_category.name
            }}
          </v-chip>
        </span>
        <span v-else>
<!--          <v-chip  label @click="" small>-->
          Root Category
<!--          </v-chip>-->
        </span>
      </template>
      <template v-slot:item.total_venues="props">
          <v-chip
                  small
            color="primary"
          >
            {{ props.item.total_venues }}
          </v-chip>
      </template>
       <template v-slot:item.created_at="props">
            {{ new Date(props.item.created_at).toDateString() }}
      </template>

      <template v-slot:item.status="row">
        <v-tooltip top>
          <template v-slot:activator="{ on }">
            <div dark v-on="on">
              <toggle-button
                      :width="80"
                      :height="25"
                      :font-size="10"
                      :value="row.item.status"
                      :color="{
                  checked: 'rgb(0,166,90)',
                  unchecked: 'rgb(234,84,85)',
                }"
                      :sync="true"
                      :labels="{ checked: 'ACTIVE', unchecked: 'INACTIVE'}"
                      @change="
                     statusChanged(
                        $event,
                        row.item,
                            )
                "
              />
            </div>
          </template>
          <span>Change Status</span>
        </v-tooltip>
      </template>

      <template v-slot:item.action="row">
        <Category :category="row.item" @editBtn="$emit('editBtn',$event)"> </Category>
      </template>
    </v-data-table>
    <v-snackbar v-model="snackbar" color="error" :timeout="timeout"
      >{{ error_msg }}
      <v-btn dark text @click="snackbar = false">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-snackbar>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Category from "./Category";

export default {
  props: ["search"],
  components: { Category },
  data() {
    return {
      loading: true,
      selected: [],
      options: {},

      totalData: 0,
      snackbar: false,
      error_msg: "",
      newName: "",
      filterItems: ["All", "Active", "Inactive"],
      selectedFilter: "All",
      index: 0,
      fixedHeader: true,
      dialog: false,
      status: "",
      showDeleted: false,
      parentCategory: "",
      timeout: 5000,
      showOnlyRoot: false,
      parent_category:''
    };
  },
  computed: {
    ...mapGetters(["getAllCategories"]),
    headers() {
      return [
        {
          text: 'Sno',
          align: "center",
          value: "sno",
          width: "5%",
          sortable: false,
        },
        {
          text: "Name",
          align: "start",
          value: "en_name",
          width: "20%",
        },
        {
          text: "Total Venues",
          value: "total_venues",
          sortable: false,
          width: "10%",
          align: "center",
        },
        {
          text: "Created On",
          value: "created_at",
          sortable: false,
          width: "15%",
          align: "center",
        },
        {
          text: "Status",
          value: "status",
          sortable: false,
          width: "10%",
          align: "center",
        },

        {
          text: "Action",
          value: "action",
          sortable: false,
          width: "15%",
          align: "center",
        },
      ];
    },
  },
  watch: {
    search: _.debounce(function() {
      this.paginate();
    }, 500),
    parent_category() {
      if (!this.search) 
      this.paginate();
    },
  },
  methods: {
    ...mapActions([
      "UPDATE_CATEGORY_STATUS",
      "DELETE_SELECTED_CATEGORIES",
    ]),
    statusChanged(status, item) {
      let category = {
        id: item.id,
        status: status.value,
      };
      this.updateCategory(category);
    },
    updateCategory(category){
      this.$store.commit("TOGGLE_OVERLAY");
      this.$store.dispatch('UPDATE_CATEGORY_STATUS',category)
              .then((response) => {
                Toast.fire({
                  icon: "success",
                  title: "Updated Successfully",
                });
              })
              .finally(()=>{
                this.$store.commit("TOGGLE_OVERLAY");

              })
    },
    paginate() {
      this.loading = true;
      const { sortBy, sortDesc, page, itemsPerPage } = this.options;
      this.$store.dispatch("ALL_CATEGORY",{params:{
        search:this.search,
          view_only_root:this.showOnlyRoot,
          parent_category:this.parent_category
        }}).finally(()=>{
        this.loading=false
      })
    },
    refreshTable() {
      this.$emit("clearSearch");
      this.showOnlyRoot = false;
      if (!this.search&&!this.parent_category)
     this.paginate();
      this.parent_category=''

    },
  },
};
</script>
<style>
thead .text-start,
thead .text-center {
  font-size: 14px !important;
  text-transform: uppercase;
  font-weight: bold;
}
</style>
