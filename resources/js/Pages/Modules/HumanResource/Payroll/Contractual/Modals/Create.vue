<template>
     <!-- style="--vz-modal-width: 750px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Create Payroll Cycle" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12" class="mt-1">
                    <TextInput id="name" value="Contractual Salary" type="text" class="form-control" placeholder="Please enter year" @input="handleInput('year')" :light="true" readonly/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="Month" :message="form.errors.month"/>
                    <Multiselect :options="months" :searchable="true" label="name" v-model="form.month" placeholder="Select Semester" @input="handleInput('month')"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="Year"  :message="form.errors.year"/>
                    <TextInput id="name" v-model="form.year" type="text" class="form-control" placeholder="Please enter year" @input="handleInput('year')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="Start"  :message="form.errors.start"/>
                    <TextInput id="name" v-model="form.start" type="date" class="form-control" placeholder="Please enter start date" @input="handleInput('start')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="End"  :message="form.errors.end"/>
                    <TextInput id="name" v-model="form.end" type="date" class="form-control" placeholder="Please enter end date" @input="handleInput('end')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Type" :message="form.errors.type"/>
                    <Multiselect :options="options" :searchable="true" label="name" v-model="form.type" placeholder="Select Type" @input="handleInput('type')"/>
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
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput },
    props: ['payrolls'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                month: null,
                year: new Date().getFullYear(),
                is_regular: 0,
                payroll_id: 37,
                start: null,
                end: null,
                type: null,
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