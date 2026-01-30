<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Update Date Time Record" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <div class="col-md-12">
                    <InputLabel for="name" value="New Time" />
                    <TextInput id="name" v-model="form.time" type="time" class="form-control" placeholder="Please enter time" @input="handleInput('time')" :light="true"/>
                </div>
                <div class="col-md-12 mt-0">
                    <InputLabel for="name" value="Remarks" />
                    <TextInput id="name" v-model="form.remarks" type="text" class="form-control" placeholder="Please enter remarks" @input="handleInput('remarks')" :light="true"/>
                </div>
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
    components: { TextInput, InputLabel },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                remarks: null,
                time: null,
                type: null,
                option: 'add'
            }),
            showModal: false
        }
    },
    methods: { 
        show(id,type){
            this.form.type = type;
            this.form.id = id;
            this.showModal = true;
        },
        submit(){
            this.form.put('/visitors/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',response.props.flash.data)
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