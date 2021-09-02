<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="getAllVenues"
      item-key="id"
      :loading="loading"
      class="elevation-1"
      select="true"
      :options.sync="options"
      @update:options="paginate"
      :server-items-length="totalData"
      :footer-props="footerProps"
      :items-per-page="10"
      loading-text="Loading Data"
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
                  <span>Reload Table</span>
                </v-tooltip>
              </v-row>
            </v-col>
          </v-col>
        </v-row>
      </template>
      <template v-slot:item.sno="row">
        {{(getAllVenues.map(function(x) {return x.id; }).indexOf(row.item.id))+1}}.
      </template>
      <template v-slot:item.name="props">
        {{
          props.item.name
        }}
        <div style="color:grey;font-size:12px"> {{
          props.item.address
          }}</div>
      </template>
      <template v-slot:item.venue_owner="props">
        {{
          props.item.owner_name
        }}
        <div style="color:grey;font-size:12px"> {{
          props.item.owner_mobile
          }}</div>
      </template>
      <template v-slot:item.cost="props">
        {{
          props.item.cost
        }}
      </template>
      <template v-slot:item.category="props">
        <v-chip small>
          {{
          props.item.category
          }}
        </v-chip>
      </template>
      <template v-slot:item.capacity="props">
        {{
        props.item.capacity
        }}
      </template>
<!--      <template v-slot:item.capacity="props">-->
<!--        {{-->
<!--        new Date(props.item.created_at).toDateString()-->
<!--        }}-->
<!--      </template>-->
      <template v-slot:item.action="row">
        <Venue :venue="row.item" @editBtn="$emit('editBtn',$event)"> </Venue>
      </template>
    </v-data-table>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Venue from "./Venue";

export default {
  props: ["search"],
  components: { Venue },
  data() {
    return {
      loading: true,
      options: {},
      filterItems: ["All", "Active", "Inactive"],
      selectedFilter: "All",
      dialog: false,
      status: "",
      totalData: 0,
    };
  },
  computed: {
    ...mapGetters(["getAllVenues"]),
    footerProps() {
      return {
        showFirstLastPage: true,
        itemsPerPageOptions: [10, 20, 30, 50],
        itemsPerPageText: 'Venues Per Page',
        showCurrentPage: true,
      };
    },
    headers() {
      let headers= [
        {
          text: 'Sno.',
          align: "center",
          value: "sno",
          width: "5%",
          sortable: false,
        },
        {
          text: "Venue Name",
          align: "start",
          value: "name",
          width: "20%",
        },
        {
          text: "Venue Owner",
          align: "start",
          value: "venue_owner",
          width: "20%",
          sortable: false,
        },
        {
          text: "Category",
          align: "start",
          value: "category",
          width: "15%",
          sortable: false,
        },
        {
          text: "Price (Rs)",
          align: "start",
          value: "cost",
          width: "15%",
          sortable: false,
        },
        {
          text: "Capacity of People",
          align: "start",
          value: "capacity",
          width: "15%",
          sortable: false,
        },

        {
          text: "Action",
          value: "action",
          sortable: false,
          width: "10%",
          align: "center",
        },
      ];
      this.$is('Admin')?headers.splice(6,1):''
      return headers
    },
  },
  watch: {
    search: _.debounce(function() {
      this.paginate();
    }, 500),
  },
  methods: {
    paginate() {
      this.loading = true;
      const { sortBy, sortDesc, page, itemsPerPage } = this.options;
      this.$store.dispatch("ALL_VENUES",{params:{
        search:this.search,
          page: page,
          per_page: itemsPerPage,
        }})
              .then((response) => {
                response.data.meta.total == 0
                        ? (this.totalData = 1)
                        : (this.totalData = response.data.meta.total);
              })
              .finally(()=>{
        this.loading=false
      })
    },
    refreshTable() {
     this.paginate();

    },
  },
};
</script>

