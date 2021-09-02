<template>
    <div>
        <SpecForm
                v-if="dialog"
                :editMode="editMode"
                :owner="owner"
                @closeDialog="$emit('closeDialog')"
        ></SpecForm>
    </div>

</template>
<script>
    import SpecForm from './Form'

    export default {
        props: ['dialog', 'editMode','owner'],
        components: {
            SpecForm
        }
    }
</script>