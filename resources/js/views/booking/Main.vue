<template>
    <div>
        <v-card>
            <v-card-title class="primary--text headline">
                Bookings List
                <v-spacer></v-spacer>
                <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                        clearable
                ></v-text-field>
            </v-card-title>
        <Venues :search="search" @clearSearch="search=''" @editBtn="selectedVenue=$event,editMode=dialog=true"></Venues>
        </v-card>
<!--        <v-btn-->
<!--                v-show="scroll"-->
<!--                bottom-->
<!--                color="primary"-->
<!--                dark-->
<!--                fab-->
<!--                fixed-->
<!--                right-->
<!--                @click="dialog = true, editMode=false"-->
<!--        >-->
<!--            <v-icon>mdi-plus</v-icon>-->
<!--        </v-btn>-->

        <v-dialog v-if="dialog" v-model="dialog" persistent transition="dialog-top-transition" max-width="700">
            <modal
                    @closeDialog="dialog=editMode=false,selectedVenue=''"
                    :editMode="editMode"
                    :dialog="dialog"
                    :venue="selectedVenue"
            ></modal>
        </v-dialog>
    </div>
</template>

<script>
    import Venues from './Bookings'
    import Modal from './Modal'

    export default {
        components: {Venues, Modal},
        name: "VenueMain",
        data() {
            return {
                scroll: true,
                dialog: false,
                editMode: false,
                selectedVenue:'',
                search:''
            }
        },
        // methods: {
        //     handleScroll() {
        //         let pageHeight = document.documentElement.offsetHeight;
        //         let windowHeight = window.innerHeight
        //         let scrollPosition = window.scrollY || window.pageYOffset || document.body.scrollTop + (document.documentElement && document.documentElement.scrollTop || 0);
        //
        //         if (pageHeight <= windowHeight + scrollPosition + 20) {
        //             this.scroll = false;
        //         } else {
        //             this.scroll = true;
        //         }
        //     }
        // },
        // created() {
        //     window.addEventListener('scroll', this.handleScroll);
        // },
        // destroyed() {
        //     window.removeEventListener('scroll', this.handleScroll);
        // },

    }
</script>
