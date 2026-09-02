<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Assign Employee" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" v-if="selected">
                    <div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show material-shadow fs-12" role="alert">
                        <i class="ri-information-line label-icon"></i>
                        Tagging <strong>{{ selected.name }}</strong> ({{ selected.code }}) to a new employee will automatically end the current assignment, if any.
                    </div>
                </BCol>
                <BCol lg="12" class="mt-1">
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
                        placeholder="Search Employee"
                    />
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="start_at" value="Start Date" :message="form.errors.start_at"/>
                    <TextInput id="start_at" v-model="form.start_at" type="date" class="form-control" @input="handleInput('start_at')" :light="true"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing || !form.user_id" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput },
    data(){
        return {
            id: null,
            selected: null,
            employee: null,
            employees: [],
            isLoading: false,
            form: useForm({
                user_id: null,
                start_at: null,
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
        show(data){
            this.selected = data;
            this.id = data.id;
            this.employees = data.current_assignment?.user_name ? [{ value: data.current_assignment.user_id, name: data.current_assignment.user_name }] : [];
            this.employee = null;
            this.form.start_at = new Date().toISOString().slice(0,10);
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
            this.form.post(`/equipments/${this.id}/assign`,{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
                    this.hide();
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.clearErrors();
            this.form.reset();
            this.employee = null;
            this.employees = [];
            this.selected = null;
            this.id = null;
            this.showModal = false;
        }
    }
}
</script>
