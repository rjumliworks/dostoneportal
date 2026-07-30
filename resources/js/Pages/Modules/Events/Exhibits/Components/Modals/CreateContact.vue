<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Add Contact" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel value="Name" :message="form.errors.name"/>
                    <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6">
                    <InputLabel value="Email" :message="form.errors.email"/>
                    <TextInput v-model="form.email" type="email" class="form-control" placeholder="Please enter email" @input="handleInput('email')" :light="true"/>
                </BCol>
                <BCol lg="6">
                    <InputLabel value="Mobile Phone" :message="form.errors.contact_no"/>
                    <TextInput v-model="form.contact_no" type="text" class="form-control" placeholder="Please enter mobile number" @input="handleInput('contact_no')" :light="true"/>
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
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            form: useForm({
                exhibitor_id: null,
                name: null,
                email: null,
                contact_no: null,
                option: 'contact'
            }),
            showModal: false
        }
    },
    methods: {
        show(exhibitorId){
            this.form.reset();
            this.form.exhibitor_id = exhibitorId;
            this.showModal = true;
        },
        submit(){
            this.form.post('/exhibits',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.showModal = false;
        }
    }
}
</script>
