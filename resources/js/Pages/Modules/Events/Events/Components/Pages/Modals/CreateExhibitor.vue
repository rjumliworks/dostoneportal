<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Exhibitor' : 'Edit Exhibitor'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
        
                <BCol lg="12" class="mt-3">
                    <InputLabel for="due" value="Title" :message="form.errors.title"/>
                    <TextInput v-model="form.title" type="text" class="form-control" placeholder="Please enter title" @input="handleInput('title')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="due" value="Institution" :message="form.errors.institution"/>
                    <TextInput v-model="form.institution" type="text" class="form-control" placeholder="Please enter institution" @input="handleInput('institution')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="role" value="Type" />
                    <Multiselect
                        v-model="form.type_id"
                        :options="types"
                        label="name"
                        placeholder="Select Type"
                        ref="multiselect2"
                        />
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="role" value="Area" />
                    <Multiselect
                        v-model="form.area"
                        :options="[
                            'Prevention & Mitigation','Preparedness','Response','Recovery/Rehabilitation'
                        ]"
                        placeholder="Select Area"
                        ref="multiselect2"
                        />
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel for="due" value="Description" :message="form.errors.description"/>
                    <Textarea v-model="form.description" rows="4" type="text" class="form-control" placeholder="Please enter description" @input="handleInput('description')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-1"><hr class="text-muted"/></BCol>
                <BCol lg="12" class="mt-n2">
                    <InputLabel for="due" value="Name" :message="form.errors.name"/>
                    <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="due" value="Email" :message="form.errors.email"/>
                    <TextInput v-model="form.email" type="text" class="form-control" placeholder="Please enter email" @input="handleInput('email')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="due" value="Contact no." :message="form.errors.institution"/>
                    <TextInput v-model="form.contact_no" type="text" class="form-control" placeholder="Please enter contact no" @input="handleInput('contact_no')" :light="true"/>
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
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
export default {
    components: { InputLabel, TextInput, Textarea, Multiselect },
    props: ['id','types'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                title: null,
                institution: null,
                type_id: null,
                area: null,
                description: null,
                email: null,
                contact_no: null,
                name: null,
                is_active: true,
                event_id: this.id,
                option: 'exhibitor'
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
                this.form.put('/exhibitors/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    },
                });
            }else{
                this.form.post('/exhibitors',{
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