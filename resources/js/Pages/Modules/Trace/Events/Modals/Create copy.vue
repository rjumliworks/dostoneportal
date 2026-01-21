<template>
     <!-- style="--vz-modal-width: 750px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" title="Create Event" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Title" :message="form.errors.title"/>
                    <TextInput id="name" v-model="form.title" type="text" class="form-control" placeholder="Please enter year" @input="handleInput('year')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Purpose" :message="form.errors.purpose"/>
                    <div class="position-relative">
                        <textarea
                            id="attribute" v-model="form.purpose" rows="1"
                            class="form-control" placeholder="Please enter purpose"
                            style="background-color: #f5f6f7; padding-bottom: 28px;" 
                        ></textarea>

                        <span class="position-absolute" style="top: -18px; font-size: 11px; right: 8px; background-color: white; cursor: pointer; color: #0d6efd; font-weight: 500;" @click="improveText" :class="{ 'text-muted': loading }">
                            <span v-if="loading">Improving...</span>
                            <span v-else>Improve with AI</span>
                        </span>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-1 mb-n3">
                    <hr class="text-muted"/>
                </BCol>
                <BCol lg="6" class="mt-1"> 
                    <label>Date <span v-if="form.errors.date" class="text-danger" style="font-size: 9px;">({{ form.errors.date }})</span></label>
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
                 <BCol lg="6" class="mt-1">
                        <InputLabel for="name" value="Time of Day"  :message="form.errors.date"/>
                        <Multiselect :options="['Whole Day','AM','PM']" label="name" v-model="form.timeOfDay" placeholder="Select Date type"/>
                    </BCol>
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
                 <BCol lg="12" class="mt-n1 mb-n3">
                    <hr class="text-muted"/>
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Type" :message="form.errors.type_id"/>
                    <Multiselect :options="dropdowns.types" :searchable="true" label="name" v-model="form.type_id" placeholder="Select Type" @input="handleInput('type_id')"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="Mode" :message="form.errors.type_id"/>
                    <Multiselect :options="dropdowns.modes" :searchable="true" label="name" v-model="form.mode_id" placeholder="Select Mode" @input="handleInput('mode_id')"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="Audience" :message="form.errors.type_id"/>
                    <Multiselect :options="dropdowns.audiences" :searchable="true" label="name" v-model="form.audience_id" placeholder="Select Audience" @input="handleInput('audience_id')"/>
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
import Multiselect from "@vueform/multiselect";
import flatPickr from "vue-flatpickr-component";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, flatPickr, },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                title: null,
                purpose: null,
                remarks: null,
                is_host: null,
                audience_id: null,
                type_id: null,
                mode_id: null,
                option: 'contractual'
            }),
            months: ['January','February','March','April','May','June','July','August','September','October','November','December']
            .map((name, index) => ({
                value: index + 1,
                name: name
            })),
            types: [ {'value': 1,'name': 'Yes'},{'value': 0,'name': 'No'}],
            options: [ {'value': '1st','name': '1st Quincena'},{'value': '2nd','name': '2nd Quincena'}],
            showModal: false
        }
    },
    
    watch: {
        'form.month': {
            handler: 'updateDates',
            immediate: false
        },
        'form.year': {
            handler: 'updateDates',
            immediate: false
        },
        'form.is_regular': {
            handler: 'updateDates',
            immediate: false
        },
        "form.start"(){
            this.form.errors.start = null;
        },
        "form.end"(){
            this.form.errors.end = null;
        }
    },
    methods: {
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            this.form.post('/payroll',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('success',true);
                    this.form.clearErrors();
                    this.form.reset();
                    this.hide();
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.showModal = false;
            this.form.reset();
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
        updateDates() {
            if (this.form.is_regular === 1) {
                this.form.type = null;

                if (this.form.month !== null && this.form.year !== null) {
                    const year = this.form.year;
                    const monthIndex = this.form.month - 1;

                    const startDate = new Date(Date.UTC(year, monthIndex, 1));
                    this.form.start = startDate.toISOString().split('T')[0];

                    const endDate = new Date(Date.UTC(year, monthIndex + 1, 0));
                    this.form.end = endDate.toISOString().split('T')[0];
                } else {
                    this.form.start = null;
                    this.form.end = null;
                }
            } else if (this.form.is_regular === 0) {
                this.form.start = null;
                this.form.end = null;
            } else {
                this.form.start = null;
                this.form.end = null;
                this.form.type = null;
            }
        }
    },


}
</script>