<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 550px;" header-class="p-3 bg-light" title="Recommend Request" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
         <div class="d-flex w-100 p-2 justify-content-center align-items-center">
                <div class="p-4 w-100 border rounded bg-light-subtle text-center">
                    <h1 class="bx-tada"><i class="ri-checkbox-circle-fill text-secondary"></i></h1>
                    <p class="mb-3 text-secondary fw-semibold">Are you sure you want to recommend this <b>{{ type }}</b> request?</p>
                    <p class="mb-0 text-dark fs-11">
                        Please review all details carefully before proceeding. Once recommended, this request will be 
                        forwarded and made visible to the designated approver for further action.
                    </p>
                    
                </div>
            </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="secondary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    data(){
        return {
            form: useForm({
                id: null,
                status_id: 25,
                option: 'status'
            }),
            type: null,
            showModal: false
        }
    },
    methods: { 
        show(id,type){
            this.form.id = id;
            this.type = type;
            this.showModal = true;
        },  
        submit(){
            this.form.put('/approvals/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.form.clearErrors();
                    this.form.reset();
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