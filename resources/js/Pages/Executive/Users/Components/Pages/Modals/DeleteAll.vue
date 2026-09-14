<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Delete All Files" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="p-2">
            <div class="alert alert-danger alert-dismissible alert-additional fade show mb-xl-0 material-shadow" role="alert">
                <div class="alert-body">
                    <div class="d-flex mt-n1 mb-n2">
                        <div class="flex-shrink-0 me-2">
                            <i class="ri-alert-line fs-14 align-middle"></i>
                        </div>
                        <div class="flex-grow-1 mt-1">
                            <h5 class="fs-13 alert-heading">Are you sure you want to delete all reference images?</h5>
                        </div>
                    </div>
                </div>
                <div class="alert-content">
                    <p class="mb-0 fs-10">This will permanently remove every uploaded reference image linked to this user. This action cannot be undone.</p>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="danger" :disabled="form.processing" block>Delete All</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    data(){
        return {
            form: useForm({
                code: null,
                option: 'delete-all'
            }),
            showModal: false
        }
    },
    methods: {
        show(code){
            this.form.reset();
            this.form.code = code;
            this.showModal = true;
        },
        submit(){
            this.form.post('/users',{
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('message',true);
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
