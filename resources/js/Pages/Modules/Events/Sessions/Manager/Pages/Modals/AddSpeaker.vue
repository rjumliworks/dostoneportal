<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Add Speaker" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <InputLabel value="Name" :message="form.errors.name"/>
                    <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" :light="true"/>
                </BCol>   
                <BCol lg="6">
                    <InputLabel value="Title" :message="form.errors.contact_no"/>
                    <TextInput v-model="form.title" type="text" class="form-control" placeholder="Please enter title" :light="true"/>
                </BCol>  
                <BCol lg="6">
                    <InputLabel value="Establishment" :message="form.errors.contact_no"/>
                    <TextInput v-model="form.establishment" type="text" class="form-control" placeholder="Please enter establishment" :light="true"/>
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
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                name: null,
                title: null,
                establishment: null,
                option: 'speaker'
            }),
            showModal: false
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        submit(){
            this.form.post('/events',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('selected',response.props.flash.data);
                    this.hide();
                },
            });
        },
        hide(){
            this.form.name = null;
            this.form.contact_no = null;
            this.showModal = false;
        }
    }
}
</script>