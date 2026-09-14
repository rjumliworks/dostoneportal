<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Add Leave Credits" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel for="employee" value="Employee" :message="form.errors.user_id"/>
                    <Multiselect
                        v-model="employee"
                        :options="employees"
                        @search-change="checkSearchStr"
                        :searchable="true"
                        :loading="isLoading"
                        label="name"
                        object
                        :filter-results="false"
                        placeholder="Search employee by last name"
                    />
                </BCol>
                <BCol lg="12">
                    <InputLabel for="leave_id" value="Leave Type" :message="form.errors.leave_id"/>
                    <Multiselect
                        v-model="form.leave_id"
                        :options="leaveTypes"
                        :searchable="true"
                        label="name"
                        placeholder="Select leave type"
                    />
                </BCol>
                <BCol lg="12">
                    <InputLabel for="amount" value="Number of Credits (days)" :message="form.errors.amount"/>
                    <TextInput id="amount" v-model="form.amount" type="number" min="0.01" step="0.01" class="form-control" placeholder="e.g. 5" :light="true" @input="handleInput('amount')"/>
                </BCol>
                <BCol lg="12">
                    <InputLabel for="remarks" value="Remarks (optional)"/>
                    <Textarea id="remarks" v-model="form.remarks" placeholder="e.g. Approved maternity leave grant" :model-size="2" :light="true"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing || !form.user_id || !form.leave_id || !form.amount" block>Submit</b-button>
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
    props: {
        leaveTypes: { type: Array, default: () => [] }
    },
    data(){
        return {
            employee: null,
            employees: [],
            isLoading: false,
            form: useForm({
                user_id: null,
                leave_id: null,
                amount: null,
                remarks: null,
                option: 'add-credit'
            }),
            showModal: false
        }
    },
    watch: {
        employee(newVal){
            this.form.user_id = newVal?.value ?? null;
        }
    },
    methods: {
        show(){
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
            this.form.post('/credits',{
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('added',true);
                    this.hide();
                },
            });
        },
        handleInput(field){
            this.form.errors[field] = false;
        },
        hide(){
            this.form.clearErrors();
            this.form.reset();
            this.employee = null;
            this.employees = [];
            this.showModal = false;
        }
    }
}
</script>
