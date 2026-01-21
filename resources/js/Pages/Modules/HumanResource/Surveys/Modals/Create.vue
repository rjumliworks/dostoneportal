<template>
     <!-- style="--vz-modal-width: 750px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Create Survey" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Year"  :message="form.errors.year"/>
                    <TextInput id="name" v-model="form.year" type="text" class="form-control" placeholder="Please enter year" @input="handleInput('year')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0 mb-2">
                    <InputLabel for="name" value="Semester" :message="form.errors.semester_id"/>
                    <Multiselect :options="dropdowns.semesters" :searchable="true" label="name" v-model="form.semester_id" placeholder="Select Semester" @input="handleInput('semester_id')"/>
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
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                year: null,
                semester_id: null,
                option: 'survey'
            }),
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            this.form.post('/surveys',{
                preserveScroll: true,
                onSuccess: (response) => {
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
        }
    }
}
</script>