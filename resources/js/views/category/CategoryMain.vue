<template>
    <div>
        <v-card>
            <v-card-title class="primary--text headline">
                Categories List
                <v-spacer></v-spacer>
                <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search Category"
                        single-line
                        hide-details
                        clearable
                ></v-text-field>
            </v-card-title>
        <category-list :search="search" @clearSearch="search=''" @editBtn="selectedCategory=$event,editMode=dialog=true"></category-list>
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

        <v-dialog v-if="dialog" v-model="dialog" persistent transition="dialog-top-transition" max-width="800">
            <category-modal
                    @closeDialog="dialog=editMode=false,selectedCategory=''"
                    :editMode="editMode"
                    :dialog="dialog"
                    :newCat="newCat"
                    :category="selectedCategory"
            ></category-modal>
        </v-dialog>
    </div>
</template>

<script>
    import CategoryList from './Categories'
    import CategoryModal from './CategoryModal'

    export default {
        components: {CategoryList, CategoryModal},
        name: "CategoryMain",
        data() {
            return {
                scroll: true,
                dialog: false,
                editMode: false,
                newCat: false,
                selectedCategory:'',
                search:'',
                show:false
            }
        },
        methods: {
            handleScroll() {
                let pageHeight = document.documentElement.offsetHeight;
                let windowHeight = window.innerHeight
                let scrollPosition = window.scrollY || window.pageYOffset || document.body.scrollTop + (document.documentElement && document.documentElement.scrollTop || 0);

                if (pageHeight <= windowHeight + scrollPosition + 20) {
                    this.scroll = false;
                } else {
                    this.scroll = true;
                }
            }
        },
        created() {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed() {
            window.removeEventListener('scroll', this.handleScroll);
        },

    }
</script>
