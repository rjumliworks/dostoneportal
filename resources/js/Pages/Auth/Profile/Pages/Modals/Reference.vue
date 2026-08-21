<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Reference' : 'Add Reference'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-n4">
                <BCol lg="12">
                    <InputLabel value="Name" :message="form.errors.name"/>
                    <TextInput v-model="form.name" type="text" class="form-control" @input="handleInput('name')" :light="true" />
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel value="Office / Residential Address" :message="form.errors.address"/>
                    <TextInput v-model="form.address" type="text" class="form-control" @input="handleInput('address')" :light="true" />
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel value="Contact No. and/or Email" :message="form.errors.contact"/>
                    <TextInput v-model="form.contact" type="text" class="form-control" @input="handleInput('contact')" :light="true" />
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
                id: null,
                name: null,
                address: null,
                contact: null,
                option: 'reference'
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
                name: data.name,
                address: data.address,
                contact: data.contact,
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
