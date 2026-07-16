<template>
    <b-modal v-model="showModal" :style="(form.has_breakdown) ? '--vz-modal-width: 850px;' : '--vz-modal-width: 700px;'" header-class="p-3 bg-light" :title="(!editable) ? 'Create Activity' : 'Edit Activity'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
        
                <BCol lg="12" class="mt-3">
                    <InputLabel for="due" value="Activity" :message="form.errors.activity"/>
                    <TextInput v-model="form.activity" type="text" class="form-control" placeholder="Please enter title" @input="handleInput('activity')" :light="true"/>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                <BCol lg="8" style="margin-top: 13px; margin-bottom: -12px;" class="fs-12">Activity has breakdown?</BCol>
                <BCol lg="4" style="margin-top: 13px; margin-bottom: -12px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="customRadio1" class="custom-control-input me-2" :value="true" v-model="form.has_breakdown">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio1">Yes</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" id="customRadio2" class="custom-control-input me-2" :value="false" v-model="form.has_breakdown">
                                <label class="custom-control-label fw-normal fs-12" for="customRadio2">No</label>
                            </div>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-n2 mb-0"/></BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="role" value="Schedule" />
                    <Multiselect :options="scheduleOptions" v-model="form.schedule_id" label="name" :message="form.errors.schedule_id" placeholder="Select Schedule" ref="multiselect2"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel for="due" value="Start Time" :message="form.errors.capacity"/>
                    <TextInput v-model="form.start_time" type="time" class="form-control" placeholder="Please enter capacity" @input="handleInput('capacity')" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel for="due" value="End Time" :message="form.errors.capacity"/>
                    <TextInput v-model="form.end_time" type="time" class="form-control" placeholder="Please enter capacity" @input="handleInput('capacity')" :light="true"/>
                </BCol>
                <BCol lg="12" v-if="!form.has_breakdown" class="mt-0">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="conforme" value="Speaker" :message="form.errors.speaker"/>
                            <Multiselect 
                            :options="speakers" 
                            v-model="form.speaker" 
                            label="name"
                            @input="handleInput('speaker')"
                            object
                            :searchable="true" 
                            @search-change="searchUser"
                            placeholder="Select Speaker"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd()" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-1 mb-n4"><hr class="text-muted"/></BCol>
                <BCol lg="12" v-if="form.has_breakdown" class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Breakdowns</span>
                        <div class="d-flex gap-0">
                        <b-button size="sm" variant="light" class="me-1" @click="openAdd"><i class="ri-add-line"></i> Add Speaker</b-button>
                        <b-button size="sm" variant="light" @click="addBreakdown"><i class="ri-add-line"></i> Add Breakdown</b-button>
                        </div>
                    </div>
                    <BCol lg="12" class="mt-n2 mb-n2"><hr class="text-muted"/></BCol>

                    <div v-for="(item, index) in form.breakdowns" :key="index" class="mb-2">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="d-flex gap-0">
                                    <TextInput v-model="item.start_time" type="time" class="form-control" style="width: 50%; font-size:10px;" :light="true"/>
                                    <TextInput v-model="item.end_time" type="time" class="form-control" style="width: 50%; font-size:10px;" :light="true"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <TextInput v-model="item.activity" type="text" class="form-control" placeholder="Activity" :light="true"/>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-0">
                                    <Multiselect :options="speakers" v-model="item.speaker_id"
                                    :searchable="true" @search-change="searchUser" label="name" placeholder="Speaker"/>
                                    <b-button variant="danger" @click="removeBreakdown(index)" class="ms-2" :disabled="form.breakdowns.length === 1"><i class="ri-delete-bin-fill"></i></b-button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
    <AddSpeaker @selected="set" ref="speaker"/>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import AddSpeaker from './AddSpeaker.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect, AddSpeaker },
    props: ['id','schedules'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                activity: null,
                start_time: null,
                end_time: null,
                speaker: null,
                session_id: this.id,
                schedule_id: null,
                has_breakdown: null,
                breakdowns: [{
                    start_time: null, end_time: null, activity: null, speaker_id: null
                }],
                option: 'activity'
            }),
            speakers: [],
            showModal: false,
            editable: false
        }
    },
    computed: {
        scheduleOptions() {
            return this.schedules.map(sched => {
                const date = new Date(sched.date);
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formatted = new Intl.DateTimeFormat('en-US', options).format(date);
                return {
                    value: sched.id,
                    name: formatted
                };
            });
        }
    },
    watch: {
        'form.breakdowns': {
            handler(newVal) {
                const validStartTimes = newVal
                    .map(item => item.start_time)
                    .filter(Boolean)
                    .sort(); // strings in "HH:MM" format sort correctly

                const validEndTimes = newVal
                    .map(item => item.end_time)
                    .filter(Boolean)
                    .sort();

                this.form.start_time = validStartTimes.length > 0 ? validStartTimes[0] : null;
                this.form.end_time = validEndTimes.length > 0 ? validEndTimes[validEndTimes.length - 1] : null;
            },
            deep: true,
            immediate: true
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.editable = false;
            this.showModal = true;
        },
        edit(data){
            this.editable = true;
            this.form.id = data.id;
            this.form.activity = data.activity;
            this.form.start_time = data.start_time;
            this.form.end_time = data.end_time;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/sessions/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    },
                });
            }else{
                this.form.post('/sessions',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    },
                });
            }
        },
        set(data){
            this.speakers.push(data);
            (!this.form.has_breakdown) ? this.form.speaker = data : '';
        },
        openAdd(){
            this.$refs.speaker.show(this.form.customer);
        },
        searchUser: _.debounce(function(string) {
            (string) ? this.fetchUsers(string) : '';
        }, 300),
        fetchUsers(string){
            axios.get('/search',{
                params: {
                    option: 'speakers',
                    code: string
                }
            })
            .then(response => {
                this.speakers = response.data;
            })
            .catch(err => console.log(err));
        },
        addBreakdown() {
            this.form.breakdowns.push({
                start_time: null,
                end_time: null,
                activity: null,
                speaker_id: null
            });
        },
        removeBreakdown(index) {
            if (this.form.breakdowns.length > 1) {
                this.form.breakdowns.splice(index, 1);
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