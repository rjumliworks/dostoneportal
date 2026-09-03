<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" :title="(editable) ? 'Update Maintenance Record' : 'Add Maintenance Record'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-1" v-if="lockedRequest">
                    <div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show material-shadow fs-12" role="alert">
                        <i class="ri-information-line label-icon"></i>
                        Fulfilling request <strong>{{ lockedRequest.code }}</strong> from <strong>{{ lockedRequest.requester?.profile?.fullname || '-' }}</strong>: "{{ lockedRequest.work_requested }}"
                    </div>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="date" value="Date Performed" :message="form.errors.date"/>
                    <TextInput id="date" v-model="form.date" type="date" class="form-control" @input="handleInput('date')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="type" value="Maintenance Type" :message="form.errors.type_id"/>
                    <Multiselect :options="dropdowns.maintenance_types" :searchable="true" label="name" v-model="form.type_id" placeholder="Select Type" @input="handleInput('type_id')"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="operation_performed" value="Operation Performed" :message="form.errors.operation_performed"/>
                    <Textarea v-model="form.operation_performed" :light="true" @input="handleInput('operation_performed')"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="performed_by" value="Performed By" :message="form.errors.performed_by"/>
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
                    <InputLabel for="status" value="Status" :message="form.errors.status_id"/>
                    <Multiselect :options="dropdowns.record_statuses" :searchable="true" label="name" v-model="form.status_id" placeholder="Select Status" @input="handleInput('status_id')"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="cost" value="Cost" :message="form.errors.cost"/>
                    <TextInput id="cost" v-model="form.cost" type="number" step="0.01" class="form-control" placeholder="0.00" @input="handleInput('cost')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0" v-if="showsNextDue">
                    <InputLabel for="next_due" value="Next Due Date" :message="form.errors.next_due"/>
                    <TextInput id="next_due" v-model="form.next_due" type="date" class="form-control" @input="handleInput('next_due')" :light="true"/>
                    <p class="fs-11 text-muted mb-0 mt-1">Updates the Maintenance Due date.</p>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="attachment" value="Attachment" :message="form.errors.attachment"/>
                    <input type="file" class="form-control" @change="handleFile" accept=".pdf,.jpg,.jpeg,.png">
                    <p v-if="currentAttachment" class="fs-11 mb-0 mt-1">
                        Current file: <a :href="`/storage/${currentAttachment}`" target="_blank">view attachment</a>
                    </p>
                </BCol>
                <BCol lg="12" class="mt-2">
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
            lockedRequest: null,
            currentAttachment: null,
            form: useForm({
                maintainable_type: null,
                maintainable_id: null,
                request_id: null,
                type_id: null,
                status_id: null,
                date: null,
                operation_performed: null,
                remarks: null,
                performed_by: null,
                cost: null,
                attachment: null,
                next_due: null,
            }),
            showModal: false
        }
    },
    computed: {
        showsNextDue(){
            return this.maintainableType === 'equipment';
        }
    },
    watch: {
        employee(newVal){
            this.form.performed_by = newVal?.value ?? null;
        }
    },
    methods: {
        show(record = null, request = null){
            this.form.clearErrors();
            this.form.reset();
            this.form.maintainable_type = this.maintainableType;
            this.form.maintainable_id = this.maintainable.id;

            if(record){
                this.editable = true;
                this.id = record.id;
                this.form.request_id = record.request_id;
                this.form.type_id = record.type_id;
                this.form.status_id = record.status_id;
                this.form.date = record.date;
                this.form.operation_performed = record.operation_performed;
                this.form.remarks = record.remarks;
                this.form.performed_by = record.performed_by;
                this.employee = record.performer ? { value: record.performer.id, name: record.performer.profile?.fullname } : null;
                this.currentAttachment = record.attachment;
                this.lockedRequest = record.request || null;
            }else{
                this.editable = false;
                this.id = null;
                this.currentAttachment = null;
                this.employee = null;
                if(request){
                    this.form.request_id = request.id;
                    this.form.operation_performed = request.work_requested;
                    this.lockedRequest = request;
                }else{
                    this.lockedRequest = null;
                }
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
        handleFile(event){
            this.form.attachment = event.target.files[0] ?? null;
        },
        submit(){
            if(this.editable){
                this.form.put(`/maintenance/records/${this.id}`,{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update', response.props.flash.data);
                        this.hide();
                    },
                });
            }else{
                this.form.post('/maintenance/records',{
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
            this.lockedRequest = null;
            this.currentAttachment = null;
            this.editable = false;
            this.id = null;
            this.showModal = false;
        }
    }
}
</script>
