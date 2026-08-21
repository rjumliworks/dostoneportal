<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Eligibility' : 'Add Eligibility'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-n1">
                <BCol lg="12">
                    <InputLabel value="Eligibility / Exam" :message="form.errors.exam_name"/>
                    <Multiselect :options="eligibilityOptions" :searchable="true" :create-option="true" label="name" v-model="form.exam_name" placeholder="Select or type an eligibility" @input="handleInput('exam_name')"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel value="Rating" :message="form.errors.rating"/>
                    <TextInput v-model="form.rating" type="text" class="form-control" placeholder="If applicable" @input="handleInput('rating')" :light="true" />
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel value="Date of Examination / Conferment" :message="form.errors.exam_at"/>
                    <TextInput v-model="form.exam_at" type="date" class="form-control" @input="handleInput('exam_at')" :light="true" />
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel value="Place of Examination / Conferment" :message="form.errors.exam_place"/>
                    <TextInput v-model="form.exam_place" type="text" class="form-control" @input="handleInput('exam_place')" :light="true" />
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel value="License Number" :message="form.errors.license_number"/>
                    <TextInput v-model="form.license_number" type="text" class="form-control" placeholder="If applicable" @input="handleInput('license_number')" :light="true" />
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel value="License Valid Until" :message="form.errors.license_valid_until"/>
                    <TextInput v-model="form.license_valid_until" type="date" class="form-control" @input="handleInput('license_valid_until')" :light="true" />
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect },
    props: ['eligibilityTypes'],
    computed: {
        eligibilityOptions(){
            return (this.eligibilityTypes || []).map(item => ({ value: item.name, name: item.name }));
        }
    },
    data(){
        return {
            form: useForm({
                id: null,
                exam_name: null,
                rating: null,
                exam_at: null,
                exam_place: null,
                license_number: null,
                license_valid_until: null,
                option: 'eligibility'
            }),
            showModal: false,
            editable: false
        }
    },
    methods: {
        show(){
            this.form.reset();
            this.editable = false;
            this.showModal = true;
        },
        edit(data){
            this.form.reset();
            Object.assign(this.form, {
                id: data.id,
                exam_name: data.exam_name,
                rating: data.rating,
                exam_at: data.exam_at,
                exam_place: data.exam_place,
                license_number: data.license_number,
                license_valid_until: data.license_valid_until,
            });
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            const url = this.editable ? '/profile/pds/'+this.form.id : '/profile/pds';
            const method = this.editable ? 'put' : 'post';
            this.form[method](url,{
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.hide();
                    this.$emit('success', true);
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>
