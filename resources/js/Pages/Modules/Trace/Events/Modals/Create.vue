<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" title="Create Event" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
            
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Title" :message="form.errors.title"/>
                    <TextInput id="name" v-model="form.title" type="text" class="form-control" placeholder="Please enter year" @input="handleInput('title')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Purpose" :message="form.errors.purpose"/>
                    <div class="position-relative">
                        <textarea
                            id="attribute" v-model="form.purpose" rows="1"
                            class="form-control" placeholder="Please enter purpose"
                            @input="handleInput('purpose')"
                            style="background-color: #f5f6f7; padding-bottom: 28px;" 
                        ></textarea>

                        <span class="position-absolute" style="top: -18px; font-size: 11px; right: 8px; background-color: white; cursor: pointer; color: #0d6efd; font-weight: 500;" @click="improveText" :class="{ 'text-muted': loading }">
                            <span v-if="loading">Improving...</span>
                            <span v-else>Improve with AI</span>
                        </span>
                    </div>
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel for="name" value="Type" :message="form.errors.type_id"/>
                    <Multiselect :options="dropdowns.types" :searchable="true" label="name" v-model="form.type_id" placeholder="Select Type" @input="handleInput('type_id')"/>
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel for="name" value="Mode" :message="form.errors.mode_id"/>
                    <Multiselect :options="dropdowns.modes" :searchable="true" label="name" v-model="form.mode_id" placeholder="Select Mode" @input="handleInput('mode_id')"/>
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel for="name" value="Audience" :message="form.errors.audience_id"/>
                    <Multiselect :options="dropdowns.audiences" :searchable="true" label="name" v-model="form.audience_id" placeholder="Select Audience" @input="handleInput('audience_id')"/>
                </BCol>
                <BCol lg="12" class="mt-0 mb-n1"><hr class="text-muted"/></BCol>
                <BCol lg="12" class="mt-0">
                     <InputLabel for="name" value="Date Type" :message="form.errors.date_type"/>
                    <Multiselect :options="['Single Day','Range','Multiple Dates (non-continuous)']" v-model="dateType" @input="handleInput('date_type')" placeholder="Select Date type"/>
                </BCol>
                <template v-if="dateType == 'Single Day'">
                    <BCol lg="6" class="mt-1">
                        <InputLabel for="name" value="Inclusive Dates"  :message="form.errors.dates"/>
                        <flat-pickr v-model="date" :config="single" placeholder="Select date" class="form-control flatpickr-input" style="min-height: 38.4px !important; border-color: #e9ebec; background-color: #f5f6f7;"  @input="handleInput('dates')"></flat-pickr>
                    </BCol>
                    <BCol lg="6" class="mt-1">
                        <InputLabel for="name" value="Time of Day"  :message="form.errors.date"/>
                        <Multiselect :options="['Whole Day','AM','PM']" label="name" v-model="form.timeOfDay" placeholder="Select Date type"/>
                    </BCol>
                </template>
                <template v-if="dateType == 'Range'">
                    <BCol lg="12" class="mt-1">
                        <div class="d-flex">
                            <div style="width: 100%;">
                                <InputLabel for="name" value="Inclusive Dates"  :message="form.errors.dates"/>
                                <flat-pickr v-model="date" :config="range" placeholder="Select date" class="form-control flatpickr-input" style="min-height: 38.4px !important; border-color: #e9ebec; background-color: #f5f6f7;" @input="handleInput('dates')"></flat-pickr>
                            </div>
                            <div class="flex-shrink-0">
                                <b-button @click="openDates()" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-calendar-fill"></i></b-button>
                            </div>
                        </div>
                    </BCol>
                </template>
                <template v-if="dateType == 'Multiple Dates (non-continuous)'">
                    <BCol lg="12" class="mt-1">
                        <div class="d-flex">
                            <div style="width: 100%;">
                                <InputLabel for="name" value="Inclusive Dates"  :message="form.errors.dates"/>
                                <flat-pickr v-model="date" :config="multiple" placeholder="Select dates" class="form-control flatpickr-input" style="min-height: 38.4px !important; border-color: #e9ebec; background-color: #f5f6f7;"></flat-pickr>
                            </div>
                            <div class="flex-shrink-0">
                                <b-button @click="openDates()" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-calendar-fill"></i></b-button>
                            </div>
                        </div>
                    </BCol>
                </template>
                <BCol lg="12" class="mt-0">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel value="Venue" :message="form.errors.address"/>
                            <TextInput readonly v-model="address" type="text" class="form-control" placeholder="Please enter address" @input="handleInput('address')" :light="true" />
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="addLocation(index)" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12">
                    <BRow class="g-3 mt-n3">
                        <BCol lg="12"><hr class="text-muted mb-n3 mt-n1"/></BCol>
                        <BCol lg="8" style="margin-top: 13px; margin-bottom: -12px;" class="fs-12" :class="(form.errors.is_host) ? 'text-danger' : ''">Is this event internally hosted?</BCol>
                        <BCol lg="4" style="margin-top: 13px; margin-bottom: -12px;">
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="customRadio1" class="custom-control-input me-2" @input="handleInput('is_referral')" :value="true" v-model="form.is_host">
                                        <label class="custom-control-label fw-normal fs-12" for="customRadio1">Yes</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="customRadio2" class="custom-control-input me-2" @input="handleInput('is_referral')" :value="false" v-model="form.is_host">
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
    <Location :regions="dropdowns.regions" @submit="handleLocation" ref="location"/>
    <ViewDate @update="updateDates" ref="dates"/>
    <ViewType :options="dropdowns.options" @update="updateTypes" ref="types"/>
</template>
<script>
import Location from './Location.vue';
import ViewDate from './Date.vue';
import ViewType from './Type.vue';
import { useForm } from '@inertiajs/vue3';
import flatPickr from "vue-flatpickr-component";
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import { data } from 'autoprefixer';
export default {
    components: { Multiselect, InputLabel, TextInput, flatPickr, ViewDate, ViewType, Location },
    props: ['dropdowns'],
    data(){
        return {
            address: null,
            form: useForm({
                title: null,
                purpose: null,
                audience_id: null,
                mode_id: null,
                type_id: null,
                timeOfDay: 'Whole Day',
                date: null,
                dates: [],
                date_type: null,
                is_host: null,
                address: null,
                region_code: null,
                province_code: null,
                municipality_code: null,
                barangay_code: null,
                latitude: null,
                longitude: null,
                option: 'event'
            }),
            date: null,
            single:{
                mode: "single",
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'F j, Y',
                minDate: new Date().setDate(new Date().getDate() + 1),
                disable: [
                    function(date) {
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ]
            },
            range: {
                mode: "range",
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'F j, Y',
                minDate:'',
            },
            multiple: {
                mode: "multiple",
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'F j, Y',
                minDate: new Date().setDate(new Date().getDate() + 1),
                disable: [
                    function(date) {
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ]
            },
            notice: null,
            dateType: null,
            loading: false,
            showModal: false
        }
    },
    computed: {
        formattedDate() {
            if (!this.date) return '';
            
            // If it's a range or multiple dates, handle array
            if (Array.isArray(this.date)) {
                return this.date
                    .map(d => new Date(d).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }))
                    .join(' to '); // or ', ' for multiple
            }

            // Single date
            return new Date(this.date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    },
    watch: {
        dateType(newVal){
            this.date = null;
            this.form.dates = [];
        },
        check(newVal){
            (newVal) ? this.openDates() : '';
        },
        date(newVal) {
            if (!newVal) return;
            if (this.dateType === 'Single Day') {
                this.form.dates = [{
                    date: this.formatDate(newVal),
                    timeOfDay: this.form.timeOfDay || 'Whole Day'
                }];
            }else if (this.dateType === 'Range') {
                const parts = newVal.split(' to ');
                if (parts.length === 2) {
                    const start = new Date(parts[0]);
                    const end = new Date(parts[1]);
                    const datesInRange = [];
                    for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                        if (d.getDay() !== 0 && d.getDay() !== 6) {
                            datesInRange.push({
                                date: this.formatDate(new Date(d)),
                                timeOfDay: 'Whole Day'
                            });
                        }
                    }
                    this.form.dates = datesInRange;
                }
            }else if (this.dateType === 'Multiple Dates (non-continuous)') {
                const dateStrings = newVal.split(',').map(str => str.trim());
                this.form.dates = dateStrings.map(d => ({
                    date: this.formatDate(new Date(d)),
                    timeOfDay: 'Whole Day'
                }));
            }
        },
        'form.timeOfDay'(val) {
            if (this.dateType === 'Single Day' && this.form.dates?.length === 1) {
                this.form.dates[0].timeOfDay = val;
            }
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            this.form.date = this.date;
            this.form.date_type = this.dateType;
            this.form.post('/events',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
                    this.form.clearErrors();
                    this.form.reset();
                    this.hide();
                },
            });
        },
        formatDate(date) {
            const d = new Date(date);
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        formatDateWithWeekday(dateString) {
            const date = new Date(dateString);
            const day = date.getDate();
            const month = date.toLocaleString('en-US', { month: 'short' });
            const year = date.getFullYear();
            const weekday = date.toLocaleString('en-US', { weekday: 'long' });
            return `${month} ${day}, ${year} (${weekday})`;
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
         async improveText() {
            this.loading = true;
            axios.post('/improve', {
                purpose: this.form.purpose, 
            })
            .then(res => {
                this.form.purpose = res.data.improved.trim();
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                this.loading = false;
            });
        },
        openDates(){
            this.$refs.dates.show(this.form.dates);
        },
        addLocation(index){
            this.$refs.location.openEdit(this.region);
        },
        updateDates(data){
            this.form.dates = data;
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
<style scoped>
.multiselect__option--disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>