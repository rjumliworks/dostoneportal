<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Add Vehicle" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
            
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Name" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="Plate" value="Plate" :message="form.errors.plate"/>
                    <TextInput id="name" v-model="form.plate" type="text" class="form-control" placeholder="Please enter plate" @input="handleInput('plate')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="Type" value="Type" :message="form.errors.type"/>
                    <TextInput id="name" v-model="form.type" type="text" class="form-control" placeholder="Please enter type" @input="handleInput('type')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Station" :message="form.errors.station_id"/>
                    <Multiselect :options="dropdowns.stations" :searchable="true" label="name" v-model="form.station_id" placeholder="Select Station" @input="handleInput('station_id')"/>
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Date Acquired" :message="form.errors.acquired_at"/>
                    <TextInput id="name" v-model="form.acquired_at" type="date" class="form-control" placeholder="Please enter type" @input="handleInput('acquired_at')" :light="true"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import flatPickr from "vue-flatpickr-component";
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput },
    props: ['dropdowns'],
    data(){
        return {
            address: null,
            form: useForm({
                name: null,
                type: null,
                plate: null,
                status_id: 21,
                station_id: null,
                acquired_at: null,
                option: 'vehicle'
            }),
            showModal: false
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        submit(){
            this.form.post('/vehicles',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
                    this.hide();
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.clearErrors();
            this.form.reset();
            this.showModal = false;
        }
    }
}
</script>
<style scoped>
.multiselect__option--disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>