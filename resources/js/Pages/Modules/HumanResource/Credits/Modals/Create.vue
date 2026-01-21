<template>
     <!-- style="--vz-modal-width: 750px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Create Survey" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        generate
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                option: 'credit'
            }),
            showModal: false
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        submit(){
            this.form.post('/credits',{
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