<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 1000px;" header-class="p-3 bg-light" title="File Travel Order" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 p-2">
                <BCol lg="12" class="mt-2">
                    <InputLabel v-if="!selectedEvent" value="Event" :message="form.errors.event_id"/>
                    <Multiselect
                        v-if="!selectedEvent"
                        v-model="selectedEvent"
                        :options="eventResults"
                        mode="single"
                        object
                        :searchable="true"
                        :loading="eventLoading"
                        label="name"
                        @search-change="checkEventSearchStr"
                        placeholder="Search by event title"
                    />
                    <div class="border border-dashed bg-light-subtle rounded p-2" v-else>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="fw-semibold fs-13 text-primary mb-0">{{ selectedEvent.name }}</h6>
                            </div>
                            <div class="flex-shrink-0">
                                <b-button @click="selectedEvent = null" variant="light" size="sm" v-b-tooltip.hover title="Change Event">
                                    <i class="ri-close-line"></i>
                                </b-button>
                            </div>
                        </div>
                        <!-- <hr class="my-2"/> -->
                        <p class="mb-0 fs-12 text-muted">
                            <i class="ri-map-pin-fill align-bottom me-1"></i>
                            {{ selectedEvent.location?.name || 'No location set for this event' }}
                        </p>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-2">
                    <InputLabel value="Purpose" :message="form.errors.purpose"/>
                    <TextInput v-model="form.purpose" type="text" class="form-control" placeholder="Please enter the purpose of this travel" @input="handleInput('purpose')" :light="true"/>
                </BCol>
                <BCol lg="8" class="mt-0">
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

                <BCol lg="4" class="mt-0">
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
                         @input="handleInput('tags')"
                        :preserve-search="true"
                        :filter-results="false"
                        placeholder="Select Employee"
                        ref="multiselect2"
                        />
                </BCol>

                <BCol lg="12">
                    <hr class="text-muted mt-n1"/>
                </BCol>

                <BCol :lg="(form.mode_id == 150) ? 3 : 6" class="mt-n2">
                    <InputLabel for="name" value="Travel Expense" :message="form.errors.expense_id"/>
                    <Multiselect
                        v-model="form.expense_id"
                        :options="dropdowns.expenses"
                        label="name"
                         @input="handleInput('expense_id')"
                        placeholder="Select type"
                    />
                </BCol>
                <BCol :lg="(form.mode_id == 150 || form.mode_id == 151) ? 3 : 6" class="mt-n2">
                    <InputLabel for="name" value="Mode of Travel" :message="form.errors.mode_id"/>
                    <Multiselect
                        v-model="form.mode_id"
                        :options="dropdowns.modes"
                        label="name"
                        @input="handleInput('mode_id')"
                        placeholder="Select type"
                    />
                </BCol>
                <BCol v-if="form.mode_id == 151" lg="3" class="mt-n2">
                    <InputLabel for="name" value="Transportation" :message="form.errors.transpo_id"/>
                    <Multiselect
                        v-model="form.transpo_id"
                        :options="dropdowns.transportations"
                        label="name"
                        @input="handleInput('transpo_id')"
                        placeholder="Select"
                    />
                </BCol>
                <BCol v-if="form.mode_id == 150" lg="3" class="mt-n2">
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
                 <BCol v-if="form.mode_id == 150" lg="3" class="mt-n2">
                    <InputLabel for="name" value="Driver" :message="form.errors.driver_id"/>
                    <Multiselect
                        v-model="form.driver_id"
                        :options="drivers"
                        label="name"
                        @input="handleInput('driver_id')"
                        placeholder="Select Driver"
                    />
                </BCol>
                <BCol lg="12">
                    <hr class="text-muted mt-0 mb-2"/>
                    <span class="fs-11 text-muted">Please check the expenses that apply to this travel request : <span class="text-danger">{{ form.errors.expenses }}</span></span>
                    <hr class="text-muted mt-2 mb-3"/>
                </BCol>

                <BCol lg="12" style="margin-top: 0px; margin-bottom: -5px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-1">
                                <input type="checkbox" id="customRadio1" class="form-check-input me-2" :value="1" v-model="form.expenses" :disabled="form.expenses.includes('2')">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio1">Accommodation <span class="text-muted">(Actual)</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-1">
                                <input type="checkbox" id="customRadio2" class="form-check-input me-2" :value="2" v-model="form.expenses" :disabled="form.expenses.includes('1')">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio2">Accommodation <span class="text-muted">(Per Diem)</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-radio">
                                <input type="checkbox" id="customRadio3" class="form-check-input me-2" :value="3" v-model="form.expenses">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio3">Incidental Expenses</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-radio">
                                <input type="checkbox" id="customRadio4" class="form-check-input me-2" :value="4" v-model="form.expenses">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio4">Meals</label>
                            </div>
                        </div>
                    </div>
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
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
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
    components: { Multiselect, InputLabel, TextInput, flatPickr, FilePond },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                document: null,
                event_id: null,
                purpose: null,
                remarks: null,
                date: null,
                time: null,
                mode_id: null,
                expense_id: null,
                transpo_id: null,
                driver_id: null,
                vehicle: null,
                expenses: [],
                tags: [],
                option: 'travel'
            }),
            config: {
                enableTime: false,
                altInput: true,
                dateFormat: "Y-m-d",
                altFormat: "M d, Y",
                mode: "range"
            },
            employees: [],
            vehicles: [],
            drivers: [],
            isLoading: false,
            showModal: false,
            selectedEvent: null,
            eventResults: [],
            eventLoading: false
        }
    },
    watch: {
        'form.expenses'(val) {
            if (val.includes('1') && val.includes('2')) {
                const last = val[val.length - 1];
                this.form.expenses = [last];
            }
        },
        'form.mode_id'(val) {
            this.handleTransportFetch();
        },
        'form.date'(val) {
            this.handleTransportFetch();
        },
        selectedEvent(val){
            this.form.event_id = val?.value ?? null;
        }
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
                    this.$emit('success',true);
                    this.form.clearErrors();
                    this.form.reset();
                    this.resetEvent();
                    this.hide();
                },
            });
        },
        resetEvent(){
            this.selectedEvent = null;
            this.eventResults = [];
        },
        checkEventSearchStr: _.debounce(function(string) {
            this.searchEvents(string);
        }, 300),
        searchEvents(keyword){
            this.eventLoading = true;
            axios.get('/search', {
                params: {
                    option: 'events',
                    keyword: keyword
                }
            })
            .then(response => {
                this.eventResults = response.data;
            })
            .catch(err => console.log(err))
            .finally(() => { this.eventLoading = false; });
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
        fetchDrivers(string){
            axios.get('/search',{
                params: {
                    option: 'drivers',
                    keyword: string
                }
            })
            .then(response => {
                this.drivers = response.data;
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
        handleAddFile(error, fileItem) {
            if (error) return console.error('FilePond error:', error);
            this.form.document = fileItem.file;
        },
        handleTransportFetch() {
            if (this.form.mode_id == 150 && this.form.date) {
                this.fetchVehicles(this.form.date);
                this.fetchDrivers(this.form.date);
            } else {
                this.vehicles = [];
                this.drivers = [];
            }
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
