<template>
    <BModal v-model="showModal" dialog-class="modal-80" hide-footer body-class="p-0" header-class="p-0"  class="v-modal-custom" content-class="border-0" centered hide-header-close>
        <BRow v-if="selected" class="g-0">
            <BCol lg="12">
                <div class="modal-body p-4">
                    <h2 class="lh-base mb-0 fw-bold fs-20 text-center"><span class="text-primary">Session</span> <span class="text-danger">Status</span></h2>
                    <p class="text-muted fs-12 mb-4  text-center">Please take a few moments to complete this satisfaction form</p>
                    <div class="border rounded no-border mb-3">
                        <h4 class="mt-2 text-center">
                            <b-badge :class="selected.status.type">{{selected.status.name}}</b-badge>
                        </h4>
                    </div>
                    <!-- <button type="button" @click="submit" class="btn btn-primary btn-sm mt-2 mb-3 float-end">Submit</button> -->
                    <div class="hstack gap-2 justify-content-center mt-4">
                         <button @click="save(48)" class="btn btn-soft-success btn-md" type="button">
                            <div class="btn-content">Mark as Waiting</div>
                        </button>
                        <button @click="save(49)" class="btn btn-soft-success btn-md" type="button">
                            <div class="btn-content">Mark as Open</div>
                        </button>
                        <button @click="save(50)" class="btn btn-soft-success btn-md" type="button">
                            <div class="btn-content">Mark as Ongoing</div>
                        </button>
                        <button @click="save(51)" class="btn btn-soft-success btn-md" type="button">
                            <div class="btn-content">Mark as Closed</div>
                        </button>
                    </div>
                </div>
                    
            </BCol>
        </BRow>
    </BModal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    data(){
        return {
            selected: null,
            form: useForm({
                id: null,
                status_id: null,
                option: 'status'
            }),
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.form.id = data.id;
            this.showModal = true;
        }, 
        save(id){
            this.form.status_id = id;
            this.form.put('/sessions/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    // this.selected
                    // this.$emit('success',response.props.flash.data);
                    this.hide();
                },
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>