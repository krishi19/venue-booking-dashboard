<template>
    <div>
        <v-card>
            <v-card-title class="primary--text headline">
                Venue Owners List
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
        <Owners :search="search" @clearSearch="search=''" @editBtn="selectedOwner=$event,editMode=dialog=true"></Owners>
        </v-card>
        <v-btn
                v-show="scroll"
                bottom
                color="primary"
                dark
                fab
                fixed
                right
                @click="dialog = true, editMode=false"
        >
            <v-icon>mdi-plus</v-icon>
        </v-btn>

        <v-dialog v-if="dialog" v-model="dialog" persistent transition="dialog-top-transition" max-width="600">
            <modal
                    @closeDialog="dialog=editMode=false,selectedOwner=''"
                    :editMode="editMode"
                    :dialog="dialog"
                    :owner="selectedOwner"
            ></modal>
        </v-dialog>
    </div>
</template>

<script>
    import Owners from './Owners'
    import Modal from './Modal'

    export default {
        components: {Owners, Modal},
        name: "OwnerMain",
        data() {
            return {
                scroll: true,
                dialog: false,
                editMode: false,
                selectedOwner:'',
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
