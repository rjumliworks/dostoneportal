<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Update EmployeeShift" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" v-if="selected">
            <BRow class="g-3">
                <BCol lg="12" class="mt-3">
                    <div class="d-flex flex-column h-100 border border-dashed rounded p-2">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <!-- <div class="avatar-xs">
                                    <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-15">
                                        <img class="rounded-circle header-profile-user" :src="selected.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                                    </div>
                                </div> -->
                                <img :src="selected.avatar" class="rounded-circle img-thumbnail" style="width:45px;height:45px;object-fit:cover;" alt="Avatar">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="fs-13 fw-semibold text-uppercase text-primary mb-0">{{ selected.fullname }}</h6>
                                <span :class="'badge '+selected.organization.status.bg+' '+selected.organization.status.type">{{selected.organization.shift.name}}</span>
                            </div>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-2">
                    <InputLabel for="name" value="Status" :message="form.errors.status_id"/>
                     <Multiselect
                    v-model="form.shift_id" 
                    :options="shifts"
                    label="name"
                    ref="multiselect"
                    placeholder="Select Shift"/>
                </BCol>
                 <BCol lg="12" class="mt-3 mb-n3">
                    <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show material-shadow fs-10" role="alert">
                        <i class="ri-alert-line label-icon"></i>
                        <strong>Important:</strong> Please update the employee's status to reflect their current employment status (e.g., Active, Resigned, End of Contract, Retired, or Terminated). Ensure the selected status is accurate before saving, as it may affect employee records and system access.
                    </div>
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
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { Multiselect, InputLabel },
    props: ['shifts'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                shift_id: null,
                option: 'shift'
            }),
            selected: null,
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.form.id = data.id;
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            this.form.put('/employees/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update', response.props.flash.data);
                    this.hide();
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.id = null;
            this.form.shift_id = null;
            this.showModal = false;
        }
    }
}
</script>