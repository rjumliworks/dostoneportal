<template>
    <b-modal style="--vz-modal-width: 400px;" v-model="showModal" header-class="p-3 bg-light" :title="'Add '+form.type" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <InputLabel for="name" :value="type" :message="errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" autofocus placeholder="Please enter name" autocomplete="name" required :class="{ 'is-invalid': errors.name }" :light="true"/>
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
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            form:{
                name: null,
                type: null,
                option: 'add'
            },
            errors: '',
            type: '',
            showModal: false
        }
    },
    methods: { 
        show(type,key){
            this.form.type = type;
            this.form.name = key;
            this.showModal = true;
        },
        submit(){
            axios.post('/visitors', this.form)
            .then((response) => {
                this.$emit('selected',response.data);
                this.hide();
            }).catch(error => {
                // this.errors = error.response.data.errors;
                // console.log(this.errors);
                console.log(error);
            });
        },
        hide(){
            this.form.name = null;
            this.form.type = null;
            this.showModal = false;
        }
    }
}
</script>