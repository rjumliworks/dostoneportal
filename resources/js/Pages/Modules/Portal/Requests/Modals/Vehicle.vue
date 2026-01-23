<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 820px;" header-class="p-3 bg-light" title="Vehicle Reservation" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 p-2">
                <BCol lg="6" class="mt-3"> 
                    <label>Travel Date <span v-if="form.errors.date" class="text-danger" style="font-size: 9px;">({{ form.errors.date }})</span></label>
                    <div>
                        <flat-pickr ref="datepicker" 
                        placeholder="Select date" 
                        v-model="form.date" 
                        :config="config"
                        @input="handleInput('date')"
                        class="form-control flatpickr-input" id="calendar">
                        </flat-pickr>
                    </div>
                </BCol>
                <BCol lg="6" class="mt-3">
                    <InputLabel for="name" value="Vehicle" :message="form.errors.vehicle"/>
                    <Multiselect
                        v-model="form.vehicle" 
                        :options="vehicles"
                        label="name"
                        object
                        @input="handleInput('vehicle_id')"
                        placeholder="Select Vehicle"
                    />
                </BCol>
                <BCol lg="12">
                    <hr class="text-muted mt-0 mb-n1"/>
                </BCol>
                <BCol lg="12" class="mt-2">
                    <InputLabel for="name" value="Purpose" :message="form.errors.purpose"/>
                    <TextInput id="name" v-model="form.purpose" type="text" class="form-control" placeholder="Please enter purpose" @input="handleInput('purpose')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel value="Destination" :message="form.errors.address"/>
                            <TextInput readonly v-model="address" type="text" class="form-control" placeholder="Please enter address" @input="handleInput('address')" :light="true" />
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="addLocation(index)" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                
                
                <BCol lg="6" class="mt-0">
                    <InputLabel for="name" value="Departure Time" :message="form.errors.time"/>
                    <TextInput id="name" v-model="form.time" type="time" class="form-control" placeholder="Please enter time" @input="handleInput('time')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Remarks" :message="form.errors.remarks"/>
                    <TextInput id="name" v-model="form.remarks" type="text" class="form-control" placeholder="Please enter remarks" @input="handleInput('remarks')" :light="true"/>
                </BCol>

                <BCol lg="12" class="mt-0 mb-0">
                    <InputLabel for="role" value="Employees" :message="form.errors.tags"/>
                    <Multiselect
                        v-model="form.tags"
                        :options="employees"
                        mode="tags"
                        @search-change="checkSearchStr"
                        :multiple="true"
                        :searchable="true"
                        :loading="isLoading"
                        label="name"
                        object
                        :preserve-search="true"
                        :filter-results="false"
                        placeholder="Select Employee"
                        @input="handleInput('tags')"
                        ref="multiselect2"
                        />
                </BCol>
    
                <BCol lg="12">
                    <hr class="text-muted mt-0 mb-0"/>
                </BCol>

                <BCol lg="12">
                    <file-pond name="pdf" ref="pond" allow-multiple="false" max-files="1" accepted-file-types="application/pdf"
                    label-idle='Drag & Drop your PDF or <span class="filepond--label-action">Browse</span>' 
                    :allow-process="false" @addfile="handleAddFile"/>
                </BCol>
            </BRow>
        </form> 
        <Location :regions="dropdowns.regions" @submit="handleLocation" ref="location"/>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import Location from './Location.vue';
import { useForm } from '@inertiajs/vue3';
import flatPickr from "vue-flatpickr-component";
import Multiselect from "@vueform/multiselect";
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
const FilePond = vueFilePond(FilePondPluginFileValidateType);
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, flatPickr, FilePond, Location },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                document: null,
                purpose: null,
                remarks: null,
                date: null,
                time: null,
                vehicle: null,
                tags: [],
                address: null,
                region_code: null,
                province_code: null,
                municipality_code: null,
                barangay_code: null,
                latitude: null,
                longitude: null,
                option: 'reservation'
            }),
            config: {
                enableTime: false,
                altInput: true,
                dateFormat: "Y-m-d",
                altFormat: "M d, Y",
                mode: "range"
            },
            address: null,
            employees: [],
            vehicles: [],
            isLoading: false,
            showModal: false
        }
    },
    watch: {
        'form.date'(val) {
            if(val) {
                this.fetchVehicles(val);
            }else{
                this.vehicles = [];
            }
        },
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        submit(){
            this.form.post('/requests',{
                preserveScroll: true,
                forceFormData: true, 
                onSuccess: (response) => {
                    this.form.clearErrors();
                    this.form.reset();
                    this.hide();
                },
            });
        },
        fetchVehicles(string){
            axios.get('/search',{
                params: {
                    option: 'vehicles',
                    keyword: string
                }
            })
            .then(response => {
                this.vehicles = response.data;
            })
            .catch(err => console.log(err));
        }, 
        checkSearchStr: _.debounce(function(string) {
            (string) ? this.searchUser(string) : '';
        }, 300),
        searchUser(string){
            axios.get('/search',{
                params: {
                    option: 'users',
                    keyword: string
                }
            })
            .then(response => {
                this.employees = response.data;
            })
            .catch(err => console.log(err));
        }, 
        addLocation(index){
            this.$refs.location.openEdit(this.region);
        },
        handleLocation(data) {
            this.address = data.address;
            const index = data.index;

            if (index !== undefined) {
                this.form.address = data.form.info;
                this.form.region_code = data.form.region;
                this.form.province_code = data.form.province.value;
                this.form.municipality_code = data.form.municipality.value;
                this.form.barangay_code = data.form.barangay.value;
                this.form.latitude = data.form.latitude;
                this.form.longitude = data.form.longitude;
            }
        },     
        handleAddFile(error, fileItem) {
            if (error) return console.error('FilePond error:', error);
            this.form.document = fileItem.file;
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>