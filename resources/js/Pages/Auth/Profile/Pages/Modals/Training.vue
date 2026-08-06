<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Training' : 'Add Training'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-n1">
                <BCol lg="12">
                    <InputLabel value="Title of L&D Intervention / Training Program" :message="form.errors.title"/>
                    <TextInput v-model="form.title" type="text" class="form-control" @input="handleInput('title')" :light="true" />
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
                    <InputLabel value="Type of L&D" :message="form.errors.type"/>
                    <TextInput v-model="form.type" type="text" class="form-control" placeholder="Managerial / Supervisory / Technical" @input="handleInput('type')" :light="true" />
                </BCol>
                <BCol lg="12">
                    <InputLabel value="Conducted / Sponsored By" :message="form.errors.sponsored_by"/>
                    <TextInput v-model="form.sponsored_by" type="text" class="form-control" @input="handleInput('sponsored_by')" :light="true" />
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
                title: null,
                start_at: null,
                end_at: null,
                hours: null,
                type: null,
                sponsored_by: null,
                option: 'training'
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
                title: data.title,
                start_at: data.start_at,
                end_at: data.end_at,
                hours: data.hours,
                type: data.type,
                sponsored_by: data.sponsored_by,
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
