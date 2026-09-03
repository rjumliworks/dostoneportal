<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" :title="(editable) ? 'Update Maintenance Request' : 'Request Maintenance'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="requested_by" value="Requested By" :message="form.errors.requested_by"/>
                    <Multiselect
                        v-model="employee"
                        :options="employees"
                        @search-change="checkSearchStr"
                        :searchable="true"
                        :loading="isLoading"
                        label="name"
                        object
                        :filter-results="false"
                        placeholder="Search Employee"
                    />
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="location" value="Location" :message="form.errors.location"/>
                    <TextInput id="location" v-model="form.location" type="text" class="form-control" placeholder="e.g. FOS CSTC-ZCIC Section" @input="handleInput('location')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="requested_at" value="Date Requested" :message="form.errors.requested_at"/>
                    <TextInput id="requested_at" v-model="form.requested_at" type="date" class="form-control" @input="handleInput('requested_at')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="work_requested" value="Work Requested" :message="form.errors.work_requested"/>
                    <Textarea v-model="form.work_requested" :light="true" @input="handleInput('work_requested')"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="problem_description" value="Brief Description of the Problem" :message="form.errors.problem_description"/>
                    <Textarea v-model="form.problem_description" :light="true" @input="handleInput('problem_description')"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="priority" value="Priority" :message="form.errors.priority_id"/>
                    <Multiselect :options="dropdowns.priorities" :searchable="true" label="name" v-model="form.priority_id" placeholder="Select Priority" @input="handleInput('priority_id')"/>
                </BCol>
                <BCol lg="6" class="mt-0" v-if="editable">
                    <InputLabel for="status" value="Status" :message="form.errors.status_id"/>
                    <Multiselect :options="dropdowns.request_statuses" :searchable="true" label="name" v-model="form.status_id" placeholder="Select Status" @input="handleInput('status_id')"/>
                </BCol>
                <BCol lg="12" class="mt-0" v-if="editable">
                    <InputLabel for="remarks" value="Remarks" :message="form.errors.remarks"/>
                    <Textarea v-model="form.remarks" :light="true" @input="handleInput('remarks')"/>
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
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, Textarea },
    props: ['dropdowns','maintainable','maintainableType'],
    data(){
        return {
            id: null,
            editable: false,
            employee: null,
            employees: [],
            isLoading: false,
            form: useForm({
                maintainable_type: null,
                maintainable_id: null,
                requested_by: null,
                location: null,
                work_requested: null,
                problem_description: null,
                priority_id: null,
                status_id: null,
                remarks: null,
                requested_at: null,
            }),
            showModal: false
        }
    },
    watch: {
        employee(newVal){
            this.form.requested_by = newVal?.value ?? null;
        }
    },
    methods: {
        show(request = null){
            this.form.clearErrors();
            this.form.reset();
            this.form.maintainable_type = this.maintainableType;
            this.form.maintainable_id = this.maintainable.id;

            if(request){
                this.editable = true;
                this.id = request.id;
                this.form.location = request.location;
                this.form.work_requested = request.work_requested;
                this.form.problem_description = request.problem_description;
                this.form.priority_id = request.priority_id;
                this.form.status_id = request.status_id;
                this.form.remarks = request.remarks;
                this.form.requested_at = request.requested_at;
                this.form.requested_by = request.requested_by;
                this.employee = request.requester ? { value: request.requester.id, name: request.requester.profile?.fullname } : null;
            }else{
                this.editable = false;
                this.id = null;
                this.employee = null;
                this.form.location = this.maintainable.station?.name || null;
                this.form.requested_at = new Date().toISOString().slice(0,10);
            }

            this.showModal = true;
        },
        checkSearchStr: _.debounce(function(string) {
            if(!string){ return; }
            this.isLoading = true;
            axios.get('/search',{
                params: { option: 'users', keyword: string }
            })
            .then(response => {
                this.employees = response.data;
            })
            .catch(err => console.log(err))
            .finally(() => { this.isLoading = false; });
        }, 300),
        submit(){
            if(this.editable){
                this.form.put(`/maintenance/requests/${this.id}`,{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update', response.props.flash.data);
                        this.hide();
                    },
                });
            }else{
                this.form.post('/maintenance/requests',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update', response.props.flash.data);
                        this.hide();
                    },
                });
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.clearErrors();
            this.form.reset();
            this.employee = null;
            this.employees = [];
            this.editable = false;
            this.id = null;
            this.showModal = false;
        }
    }
}
</script>
