<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="getAllBookings"
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
        <v-row >
          <v-col cols="8">
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
          </v-col>
          <v-col>
                <v-menu
                        ref="menu1"
                        v-model="menu1"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                            class="mx-3"
                            v-model="booking_date"
                            label="Filter by Booking Date"
                            prepend-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                    >
                    </v-text-field>
                  </template>
                  <v-date-picker
                          v-model="booking_date"
                          :style="menu1 ? 'height:320px;' : ''"
                          no-title
                          scrollable
                          @change="$refs.menu1.save(booking_date),paginate()"

                  >
                    <v-spacer></v-spacer>
                    <v-btn
                            text
                            color="primary"
                            @click="(booking_date= ''), (menu1 = false)"
                    >Clear</v-btn
                    >
                    <v-btn
                            text
                            color="primary"
                            @click="$refs.menu1.save(booking_date)"
                    >OK</v-btn
                    >
                  </v-date-picker>
                </v-menu>
          </v-col>
        </v-row>
      </template>
      <template v-slot:item.sno="row">
        {{(getAllBookings.map(function(x) {return x.id; }).indexOf(row.item.id))+1}}.
      </template>
      <template v-slot:item.booker="props">
        {{
          props.item.full_name
        }}
        <div style="color:grey;font-size:12px"> {{
          props.item.contact
          }}</div>
        <div style="color:grey;font-size:12px"> {{
          props.item.email
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
      <template v-slot:item.venue="props">
        {{
          props.item.venue_name
        }}
        <div style="color:grey;font-size:12px"> {{
          props.item.venue_address
          }}</div>
      </template>
      <template v-slot:item.date="props">
        {{ new Date(props.item.date).toDateString() }}

      </template>
      <template v-slot:item.no_of_people="props">
        {{
        props.item.no_of_people
        }}
      </template>
      <template v-slot:item.status="props">
        <v-menu
                bottom
                origin="center center"
                transition="scale-transition"
                offset-y>
          <template v-slot:activator="{ on }">
        <v-btn color="primary" tile small v-on="$is('Venue Owner')?on:''">
          {{
          props.item.status
          }}
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
          </template>
          <v-list dense>
            <v-list-item v-if="props.item.status!='Pending'" @click="updateStatus('Pending',props.item.id)">
              <v-list-item-content class="mr0">
                <v-list-item-title>Pending</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item v-if="props.item.status!='Confirmed'" @click="updateStatus('Confirmed',props.item.id)">
              <v-list-item-content class="mr0">
                <v-list-item-title>Confirmed</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item v-if="props.item.status!='Cancelled'" @click="updateStatus('Cancelled',props.item.id)">
              <v-list-item-content class="mr0">
                <v-list-item-title>Cancelled</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
<!--      <template v-slot:item.capacity="props">-->
<!--        {{-->
<!--        new Date(props.item.created_at).toDateString()-->
<!--        }}-->
<!--      </template>-->
      <template v-slot:item.action="row">
        <Booking :owner="row.item" @editBtn="$emit('editBtn',$event)"> </Booking>
      </template>
    </v-data-table>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Booking from "./Booking";

export default {
  props: ["search"],
  components: { Booking },
  data() {
    return {
      loading: true,
      options: {},
      filterItems: ["All", "Active", "Inactive"],
      selectedFilter: "All",
      dialog: false,
      status: "",
      totalData: 0,
      menu1:false,
      booking_date:''

    };
  },
  computed: {
    ...mapGetters(["getAllBookings"]),
    footerProps() {
      return {
        showFirstLastPage: true,
        itemsPerPageOptions: [10, 20, 30, 50],
        itemsPerPageText: 'Bookings Per Page',
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
          text: "Booker",
          align: "start",
          value: "booker",
          width: "20%",
        },
        {
          text: "Venue",
          align: "start",
          value: "venue",
          width: "20%",
          sortable: false,
        },
        {
          text: "Venue Owner",
          align: "start",
          value: "venue_owner",
          width: "20%",
          sortable: false,
        },

        {
          text: "Booking Date",
          align: "start",
          value: "date",
          width: "15%",
          sortable: false,
        },
        {
          text: "No. of People",
          align: "start",
          value: "no_of_people",
          width: "5%",
          sortable: false,
        },
        {
          text: "Status",
          align: "start",
          value: "status",
          width: "10%",
          sortable: false,
        },
        // {
        //   text: "Action",
        //   value: "action",
        //   sortable: false,
        //   width: "10%",
        //   align: "center",
        // },
      ];
      this.$is('Venue Owner')?headers.splice(2,1):''
      return headers;
    },
  },
  watch: {
    search: _.debounce(function() {
      this.paginate();
    }, 500),
  },
  methods: {
    updateStatus(status,id){
      this.$store.commit('TOGGLE_OVERLAY')
      this.$store.dispatch("UPDATE_BOOKING",{id:id,status:status})
              .then((response) => {
               Toast.fire({
                 icon: "success",
                 title: "Updated and mail sent successfully."
               })
              })
              .catch((err) => {

              })
              .finally(()=>{
                this.$store.commit('TOGGLE_OVERLAY')
              })
    },
    paginate() {
      this.loading = true;
      const { sortBy, sortDesc, page, itemsPerPage } = this.options;
      this.$store.dispatch("ALL_BOOKINGS",{params:{
          search:this.search,
          page: page,
          per_page: itemsPerPage,
          booking_date:this.booking_date
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
      this.booking_date=''
     this.paginate();

    },
  },
};
</script>

