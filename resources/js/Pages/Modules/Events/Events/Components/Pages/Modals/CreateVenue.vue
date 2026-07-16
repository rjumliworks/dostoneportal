<template>
    <b-modal v-model="showModal"  header-class="p-3 bg-light" :title="(!editable) ? 'Create Venue' : 'Edit Venue'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
        
                <BCol lg="6" class="mt-3">
                    <InputLabel for="due" value="Name" :message="form.errors.name"/>
                    <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-3">
                    <InputLabel for="due" value="Establishment" :message="form.errors.description"/>
                    <TextInput v-model="form.establishment" type="text" class="form-control" placeholder="Please enter description" @input="handleInput('establishment')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="due" value="Address" :message="form.errors.year"/>
                    <TextInput v-model="form.address" type="text" class="form-control" placeholder="Please enter address" @input="handleInput('address')" :light="true"/>
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
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    props: ['detail','id'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                establishment: this.detail.venue,
                address: this.detail.address+', '+this.detail.municipality.name,
                event_id: this.id,
                option: 'venue'
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
            this.editable = true;
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.establishment = data.establishment;
            this.form.address = data.address;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/events/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    },
                });
            }else{
                this.form.post('/events',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    },
                });
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