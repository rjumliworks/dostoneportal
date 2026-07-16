<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 750px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Event' : 'Edit Event'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
        
                <BCol lg="12" class="mt-3">
                    <InputLabel for="due" value="Event Name" :message="form.errors.name"/>
                    <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter event name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="due" value="Description" :message="form.errors.description"/>
                    <TextInput v-model="form.description" type="text" class="form-control" placeholder="Please enter description" @input="handleInput('description')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="due" value="Year" :message="form.errors.year"/>
                    <TextInput v-model="form.year" type="text" class="form-control" placeholder="Please enter year" @input="handleInput('year')" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel for="due" value="Start Date" :message="form.errors.start"/>
                    <TextInput v-model="form.start" type="date" class="form-control" placeholder="Please enter description" @input="handleInput('start')" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel for="due" value="End Date" :message="form.errors.end"/>
                    <TextInput v-model="form.end" type="date" class="form-control" placeholder="Please enter description" @input="handleInput('end')" :light="true"/>
                </BCol>
                
                <BCol lg="12" class="mt-1">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel value="Address" :message="form.errors.address"/>
                            <TextInput readonly v-model="address" type="text" class="form-control" placeholder="Please enter address" @input="handleInput('address')" :light="true" />
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="addLocation(index)" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12">
                    <BRow class="g-3 mt-n2">
                        <BCol lg="12"><hr class="text-muted mb-n3 mt-n1"/></BCol>
                        <BCol lg="8" style="margin-top: 13px; margin-bottom: -12px;" class="fs-12" :class="(form.errors.is_active) ? 'text-danger' : ''">Mark event as active?</BCol>
                        <BCol lg="4" style="margin-top: 13px; margin-bottom: -12px;">
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="customRadio1" class="custom-control-input me-2" @input="handleInput('is_active')" :value="true" v-model="form.is_active">
                                        <label class="custom-control-label fw-normal fs-12" for="customRadio1">Yes</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="customRadio2" class="custom-control-input me-2" @input="handleInput('is_active')" :value="false" v-model="form.is_active">
                                        <label class="custom-control-label fw-normal fs-12" for="customRadio2">No</label>
                                    </div>
                                </div>
                            </div>
                        </BCol>
                        <BCol lg="12"><hr class="text-muted mt-n2"/></BCol>
                    </BRow>
                </BCol>
            </BRow>
        </form>
           
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
    <Location :regions="dropdowns.regions" @submit="handleSubmit" ref="location"/>
</template>
<script>
import _ from 'lodash';
import Location from './Location.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Location },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                description: null,
                year: null,
                start: null,
                end: null,
                venue: null,
                address: null,
                region_code: null,
                province_code: null,
                municipality_code: null,
                barangay_code: null,
                latitude: null,
                longitude: null,
                is_active: null,
                option: 'event'
            }),
            address: null,
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.showModal = true;
        },
        submit(){
            this.form.post('/events',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('success',true);
                    this.hide();
                },
            });
        },
        addLocation(){
            this.$refs.location.openEdit();
        },
        handleSubmit(data) {
            this.address = data.address;
            const index = data.index;

            if (index !== undefined) {
                this.form.venue = data.form.venue;
                this.form.address = data.form.info;
                this.form.region_code = data.form.region;
                this.form.province_code = data.form.province.value;
                this.form.municipality_code = data.form.municipality.value;
                this.form.barangay_code = data.form.barangay.value;
                this.form.latitude = data.form.latitude;
                this.form.longitude = data.form.longitude;
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>