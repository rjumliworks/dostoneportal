<template>
    <b-modal v-model="showModal" hide-footer hide-header title="Cancel Request" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="modal-body">
            <div class="mt-2">
                <h5 class="mb-4 text-center">Confirm Payroll</h5>
                <p class="text-muted fs-12 mb-4 text-center">
                    Once confirmed, this payroll will be marked as ongoing and can no longer be modified. Please ensure all details are accurate before proceeding.
                </p>
                <div class="customform">
                    <BRow>
                        <BCol lg="12" class="mt-2">
                            <InputLabel for="due" value="Please type CONFIRM to continue."/>
                            <TextInput v-model="keyword" type="text" class="form-control" :light="true"/>
                        </BCol>
                    </Brow>
                    <div class="hstack gap-2 justify-content-center mt-4">
                        <button @click="hide()" class="btn btn-light btn-md" type="button">
                            <div class="btn-content"> Close</div>
                        </button>
                        <button @click="submit()" :disabled="keyword !== 'CONFIRM'" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </b-modal>
</template>
<script>
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                status_id: 18,
                option: 'payroll'
            }),
            keyword: null,
            showModal: false
        }
    },
    methods: { 
        show(id){
            this.form.id = id;
            this.showModal = true;
        },
        submit(){
            this.form.put('/payrolls/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',response.props.flash.data.data);
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