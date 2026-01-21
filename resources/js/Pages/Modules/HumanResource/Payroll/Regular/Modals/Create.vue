<template>
     <!-- style="--vz-modal-width: 750px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Create Payroll" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-3">
                    <InputLabel for="name" value="Type" :message="form.errors.payroll_id"/>
                    <Multiselect :options="payrolls" :searchable="true" label="name" v-model="form.payroll_id" placeholder="Select Payroll type" @input="handleInput('payroll_id')"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="Month" :message="form.errors.month"/>
                    <Multiselect :options="months" :searchable="true" label="name" v-model="form.month" placeholder="Select Semester" @input="handleInput('month')"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-0">
                    <InputLabel for="name" value="Year"  :message="form.errors.year"/>
                    <TextInput id="name" v-model="form.year" type="text" class="form-control" placeholder="Please enter year" @input="handleInput('year')" :light="true"/>
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
                is_regular: null,
                payroll_id: null,
                option: 'regular'
            }),
            months: ['January','February','March','April','May','June','July','August','September','October','November','December']
            .map((name, index) => ({
                value: index + 1,
                name: name
            })),
            showModal: false
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
        }
    }
}
</script>