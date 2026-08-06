<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Voluntary Work' : 'Add Voluntary Work'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-n1">
                <BCol lg="12">
                    <InputLabel value="Name & Address of Organization" :message="form.errors.organization"/>
                    <TextInput v-model="form.organization" type="text" class="form-control" @input="handleInput('organization')" :light="true" />
                </BCol>
                <BCol lg="6">
                    <InputLabel value="From" :message="form.errors.start_at"/>
                    <TextInput v-model="form.start_at" type="date" class="form-control" @input="handleInput('start_at')" :light="true" />
                </BCol>
                <BCol lg="6">
                    <InputLabel value="To" :message="form.errors.end_at"/>
                    <TextInput v-model="form.end_at" type="date" class="form-control" @input="handleInput('end_at')" :light="true" />
                </BCol>
                <BCol lg="6">
                    <InputLabel value="Number of Hours" :message="form.errors.hours"/>
                    <TextInput v-model="form.hours" type="number" class="form-control" @input="handleInput('hours')" :light="true" />
                </BCol>
                <BCol lg="6">
                    <InputLabel value="Position / Nature of Work" :message="form.errors.position_nature"/>
                    <TextInput v-model="form.position_nature" type="text" class="form-control" @input="handleInput('position_nature')" :light="true" />
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
                organization: null,
                start_at: null,
                end_at: null,
                hours: null,
                position_nature: null,
                option: 'voluntary_work'
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
                organization: data.organization,
                start_at: data.start_at,
                end_at: data.end_at,
                hours: data.hours,
                position_nature: data.position_nature,
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
