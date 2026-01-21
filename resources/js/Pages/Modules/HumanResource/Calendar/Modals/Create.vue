<template>
    <b-modal v-if="selected" v-model="showModal" style="--vz-modal-width: 550px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Event' : 'Edit Event'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-1">
                <BCol lg="12" class="mt-n2">
                     <div id="external-events">
                        <div :class="'external-event fc-event '+selected.color+' '+selected.others" style="cursor: pointer;" >
                            <i class="mdi mdi-checkbox-blank-circle me-2"></i>{{selected.name}}
                        </div>
                    </div>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-n2 mb-n4"/></BCol>
                <BCol lg="12" class="mt-0 mb-n1" v-if="selected.name != 'Leave'"> 
                    <InputLabel for="name" :value="(selected.name == 'BOD Schedule') ? 'Company Name' : 'Title'" :message="form.errors.title"/>
                    <TextInput id="name" v-model="form.title" type="text" class="form-control" :placeholder="(selected.name == 'BOD Schedule') ? 'Please enter Company Name' : 'Please enter title'" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-1 mb-n1" v-if="selected.type == 'official'">
                    <InputLabel for="name" :value="(selected.name == 'BOD Schedule') ? 'No. of samples' : 'Venue'" :message="form.errors.venue"/>
                    <TextInput id="name" v-model="form.venue" type="text" class="form-control" :placeholder="(selected.name == 'BOD Schedule') ? 'Please enter no. of samples' : 'Please enter venue'" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-1 mb-1" v-if="selected.type == 'official' || selected.name != 'Holiday'">
                    <InputLabel for="attribute" :value="(selected.name != 'Leave')? (selected.name == 'BOD Schedule') ? 'Contact person' : 'Description' : 'Reason'" :message="form.errors.description"/>
                    <textarea id="attribute" v-model="form.description" maxlength="250" rows="2" type="text" class="form-control" placeholder="Please enter details" style="background-color: #f5f6f7;"/>
                </BCol>
                <BCol lg="12" v-if="selected.type == 'official'"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                <BCol lg="8" v-if="selected.type == 'official'" style="margin-top: 13px; margin-bottom: -12px;" class="fs-12">Is the event all day?</BCol>
                <BCol lg="4" v-if="selected.type == 'official'" style="margin-top: 13px; margin-bottom: -12px;">
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
                <BCol lg="12" v-if="selected.type == 'official'"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                <BCol lg="12" v-if="form.is_allday" class="mt-1"> 
                    <label>Date <span v-if="form.errors.date" class="text-danger" style="font-size: 9px;">({{ form.errors.date }})</span></label>
                    <div class="input-group">
                        <flat-pickr ref="datepicker" 
                        placeholder="Select date" 
                        v-model="form.date" 
                        :config="config"
                        class="form-control flatpickr-input" id="calendar">
                        </flat-pickr>
                    </div>
                </BCol>
                <BCol v-if="form.is_allday != null && form.is_allday == false"  lg="12" class="mt-1">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Start Date</label>
                            <flat-pickr ref="datepicker" 
                                placeholder="Select date & time" 
                                v-model="form.start" 
                                :config="timeConfig"
                                class="form-control flatpickr-input" id="caledate">
                            </flat-pickr>
                        </div>
                        <div class="col-md-6">
                            <label>End Date</label>
                            <flat-pickr ref="datepicker" 
                                placeholder="Select date & time" 
                                v-model="form.end" 
                                :config="timeConfig"
                                class="form-control flatpickr-input" id="caledate">
                            </flat-pickr>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-1">
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
    components: { InputLabel, TextInput, Multiselect, flatPickr },
    props: ['stations'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                event_id: null,
                title: null,
                date: null,
                start: null,
                end: null,
                description: null,
                venue: null,
                is_allday: null,
                stations: [],
                type: null
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
            editable: false
        }
    },
    methods: { 
        show(data){
            this.form.reset();
            this.form.clearErrors();
            this.selected = data;
            if(this.selected.name == 'Leave'){
                this.form.title = this.selected.name 
                this.form.is_allday = true;
            }else if(this.selected.name == 'Holiday'){
                this.form.is_allday = true;
            }else{
                this.form.title = null;
            }
            this.form.type = this.selected.name;
            this.form.event_id = this.selected.value;
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