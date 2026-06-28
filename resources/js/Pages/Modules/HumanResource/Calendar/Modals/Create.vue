<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 550px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Schedule' : 'Edit Schedule'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-n2">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="role" value="Event" :message="form.errors.event"/>
                    <Multiselect
                    v-model="form.event" :groups="true"
                    :options="events"
                    label="name"
                    object
                    @input="handleInput('event_id')"
                    ref="multiselect2"
                    placeholder="Select Event"/>
                    <hr v-if="form.event" class="text-muted"/>
                </BCol>
                <BCol :lg="(form.event?.type == 'Official Business') ? 6 : 12" class="mt-n2 mb-n1" v-if="form.event?.fields.title">
                    <InputLabel for="name" value="Title" :message="form.errors.event"/>
                    <TextInput id="name" v-model="form.title" type="text" class="form-control" placeholder="Please enter title" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-0 mb-n3"/></BCol>
                <BCol lg="8" style="margin-top: 10px; margin-bottom: -15px;" class="fs-12">Does the event have a set start and end time?</BCol>
                <BCol lg="4" style="margin-top: 10px; margin-bottom: -20px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="customRadio1" class="custom-control-input me-2" :value="true" v-model="form.is_allday">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio1">Yes</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="customRadio2" class="custom-control-input me-2" :value="false" v-model="form.is_allday">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio2">No</label>
                            </div>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                <BCol lg="12" v-if="!form.is_allday" class="mt-2 mb-n2"> 
                    <div class="input-group">
                        <flat-pickr ref="datepicker" 
                        placeholder="Select date" 
                        v-model="form.date" 
                        :config="config"
                        class="form-control flatpickr-input" id="calendar">
                        </flat-pickr>
                    </div>
                </BCol>
                <BCol v-if="form.is_allday == true"  lg="12" class="mt-2 mb-n2">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <flat-pickr ref="datepicker" 
                                placeholder="Select start date & time" 
                                v-model="form.start" 
                                :config="timeConfig"
                                class="form-control flatpickr-input" id="caledate">
                            </flat-pickr>
                        </div>
                        <div class="col-md-6">
                            <flat-pickr ref="datepicker" 
                                placeholder="Select end date & time" 
                                v-model="form.end" 
                                :config="timeConfig"
                                class="form-control flatpickr-input" id="caledate">
                            </flat-pickr>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-3">
                    <InputLabel for="name" value="Station" :message="form.errors.stations"/>
                    <Multiselect :options="stations" mode="tags"  label="name" v-model="form.stations" placeholder="Select Station" @input="handleInput('stations')"/>
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
import flatPickr from "vue-flatpickr-component";
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    props: ['events','stations'],
    components: { InputLabel, TextInput, Multiselect, flatPickr },
    data(){
        return {
            currentUrl: window.location.origin,
            employees: [],
            customers: [],
            tsrs: [],
            form: useForm({
                id: null,
                event: null,
                title: null,
                date: null,
                start: null,
                end: null,
                stations: [],
                is_allday: false
            }),
            timeConfig: {
                enableTime: true,
                altInput: true,
                dateFormat: "Y-m-d H:i:S",
                altFormat: "M d, Y H:i",
            },
            config: {
                enableTime: false,
                altInput: true,
                dateFormat: "Y-m-d H:i:S",
                altFormat: "M d, Y",
                mode: "range"
            },
            selected: null,
            showModal: false,
            isLoading: false,
            editable: false
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        toggleDateFormat() {
            this.config = {
                ...this.config,
               enableTime: false
            };
        },
        submit(){
            this.form.post('/calendar',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.form.reset();
                    this.$emit('message',true);
                    this.hide();
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.form.clearErrors();
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>