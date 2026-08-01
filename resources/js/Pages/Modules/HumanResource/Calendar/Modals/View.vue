<template>
    <b-modal v-if="selected" v-model="showModal" style="--vz-modal-width: 600px" header-class="p-3 bg-light" :title="'View '+selected.extendedProps.event.name" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-if="!editable">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="row align-items-center g-3">
                    <div class="col-md">
                        <div>
                            <h6><span class="fw-semibold text-primary fs-15">{{ selected.extendedProps.full_title}}</span></h6>
                            <div class="hstack gap-3  fs-12 flex-wrap">
                               
                                <div>Created By : 
                                    <span v-if="selected.extendedProps" class="fw-medium">{{selected.extendedProps.full_name}}</span>
                                    <span v-else class="text-muted">Not Available</span>
                                </div>
                                <div class="vr" style="width: 1px;"></div>
                                <div>Date Created. : 
                                    <span v-if="selected.extendedProps" class="fw-medium">{{selected.extendedProps.created_at}}</span>
                                    <span v-else class="text-muted">Not Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="text-muted"/>
        <div class="row mt-2 g-2">
            <div class="col-sm-12">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-todo-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12">Date:</p>
                            <h5 class="mb-0 fs-12">{{selected.extendedProps.datee}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-map-pin-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12">Stations:</p>
                            <ul class="mb-0 fs-12">
                                <li  v-for="(list,index) in selected.extendedProps.stations" :key="index">{{ list.station.name }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template v-else>
        <form class="customform">
            <BRow class="g-3 mt-1">
                <BCol lg="12" class="mt-0 mb-n1" v-if="selected.name != 'Leave'"> 
                    <InputLabel for="name" value="Title" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.title" type="text" class="form-control" placeholder="Please enter title" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-1 mb-n1" v-if="type == 'official'">
                    <InputLabel for="name" value="Venue" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.venue" type="text" class="form-control" placeholder="Please enter venue" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-1 mb-1" v-if="type == 'official' || selected.name != 'Holiday'">
                    <InputLabel for="attribute" :value="(selected.name != 'Leave')? 'Description' : 'Reason'" />
                    <textarea id="attribute" v-model="form.description" maxlength="250" rows="2" type="text" class="form-control" placeholder="Please enter description" style="background-color: #f5f6f7;"/>
                </BCol>
                <BCol lg="12" v-if="type == 'official'"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                <BCol lg="8" v-if="type == 'official'" style="margin-top: 13px; margin-bottom: -12px;" class="fs-12">Is the event all day?</BCol>
                <BCol lg="4" v-if="type == 'official'" style="margin-top: 13px; margin-bottom: -12px;">
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
                <BCol lg="12" v-if="type == 'official'"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                <BCol lg="12" v-if="form.is_allday" class="mt-1"> 
                    <label>Date</label>
                    <div class="input-group">
                        <flat-pickr ref="datepicker" 
                        placeholder="Select date" 
                        v-model="form.date" 
                        :config="config"
                        class="form-control flatpickr-input" id="caledate">
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
                <BCol lg="12" class="mt-3">
                    <InputLabel for="name" value="Station" :message="form.errors.stations"/>
                    <Multiselect :options="stations" mode="tags" label="name" v-model="form.stations" placeholder="Select Station" @input="handleInput('stations')"/>
                </BCol>
            </BRow>
        </form>
    </template>
    <template v-slot:footer>
        <b-button @click="hide()" variant="light" block>Close</b-button>
        <template v-if="selected.extendedProps.user_id == $page.props.user.data.id">
            <b-button @click="deleteEvent(selected.id)" variant="danger" v-if="editable == false" block>Delete</b-button>
            <b-button @click="edit()" variant="primary" v-if="editable == false" block>Edit</b-button>
            <b-button @click="submit('ok')" variant="primary" v-else :disabled="form.processing" block>Save</b-button>
        </template>
    </template>
</b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import flatPickr from "vue-flatpickr-component";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, flatPickr, Multiselect },
    props: ['stations'],
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            type: null,
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
                stations: []
            }),
            config: {
                enableTime: false,
                altInput: true,
                dateFormat: "Y-m-d H:i:S",
                altFormat: "M d, Y",
                mode: "range"
            },
            timeConfig: {
                enableTime: true,
                altInput: true,
                dateFormat: "Y-m-d H:i:S",
                altFormat: "M d, Y H:i",
            },
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(data){
            this.editable = false;
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            this.form.put(`/calendar/${this.form.id}`,{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.form.reset();
                    this.$emit('message',true);
                    this.hide();
                },
            });
        },
        edit(){
            this.form.id = this.selected.id;
            this.type = this.selected.extendedProps.event.type;
            this.form.venue = this.selected.extendedProps.venue;
            this.form.title = this.selected.extendedProps.full_title;
            this.form.description = this.selected.extendedProps.description;
            this.form.is_allday = (this.selected.extendedProps.is_allday) ? true : false;
            this.form.stations = (this.selected.extendedProps.stations || []).map(list => list.station_id);
            if(this.form.is_allday){
                this.form.date = this.selected.extendedProps.s_date+' to '+this.selected.extendedProps.e_date;
            }else{
                this.form.start = this.selected.extendedProps.s_date;
                this.form.end = this.selected.extendedProps.e_date;
            }
            this.editable = true;
        },
        deleteEvent(id){
            if (confirm("Are you sure you want to delete this event?")) {
                this.$inertia.delete(`/calendar/${id}`, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$emit('message', true); // Emit a success message or reload event list
                        this.showModal = false;
                    },
                    onError: (errors) => {
                        console.error("Failed to delete event:", errors);
                    },
                });
            } else {
                console.log("Deletion canceled");
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.type = null;
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>