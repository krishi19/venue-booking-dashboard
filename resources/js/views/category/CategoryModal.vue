<template>
    <div>
        <category-form
                v-if="dialog"
                :editMode="editMode"
                :category="category"
                @closeDialog="$emit('closeDialog')"
        ></category-form>
    </div>

</template>
<script>
    import CategoryForm from './CategoryForm'

    export default {
        props: ['dialog', 'editMode','category'],
        components: {
            CategoryForm
        }
    }
</script>