<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="getAllAdmins"
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
                  <span>Reload Table</span>
                </v-tooltip>
              </v-row>
            </v-col>
          </v-col>
        </v-row>
      </template>
      <template v-slot:item.sno="row">
        {{(getAllAdmins.map(function(x) {return x.id; }).indexOf(row.item.id))+1}}.
      </template>
      <template v-slot:item.name="props">
        {{
          props.item.name
        }}
      </template>
      <template v-slot:item.contact="props">
        {{
          props.item.mobile
        }}, {{
          props.item.phone
        }}
        <div style="color:grey;font-size:12px">{{props.item.email}}</div>
      </template>
      <template v-slot:item.adress="props">
        {{
          props.item.address
        }}
      </template>
      <template v-slot:item.created_at="props">
        {{
        new Date(props.item.created_at).toDateString()
        }}
      </template>
      <template v-slot:item.action="row">
        <Owner :owner="row.item" @editBtn="$emit('editBtn',$event)"> </Owner>
      </template>
    </v-data-table>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Owner from "./Owner";

export default {
  props: ["search"],
  components: { Owner },
  data() {
    return {
      loading: true,
      options: {},
      filterItems: ["All", "Active", "Inactive"],
      selectedFilter: "All",
      dialog: false,
      status: "",
    };
  },
  computed: {
    ...mapGetters(["getAllAdmins"]),
    headers() {
      return [
        {
          text: 'Sno.',
          align: "center",
          value: "sno",
          width: "5%",
          sortable: false,
        },
        {
          text: "Full Name",
          align: "start",
          value: "name",
          width: "25%",
        },
        {
          text: "Contact",
          align: "start",
          value: "contact",
          width: "20%",
          sortable: false,
        },
        {
          text: "Address",
          align: "start",
          value: "address",
          width: "25%",
          sortable: false,
        },
        {
          text: "Created On",
          align: "start",
          value: "created_at",
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
      this.$store.dispatch("ALL_ADMINS",{params:{
        search:this.search,
        }}).finally(()=>{
        this.loading=false
      })
    },
    refreshTable() {
     this.paginate();

    },
  },
};
</script>

