<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Work Experience' : 'Add Work Experience'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-n4">
                <BCol lg="6">
                    <InputLabel value="From" :message="form.errors.start_at"/>
                    <TextInput v-model="form.start_at" type="date" class="form-control" @input="handleInput('start_at')" :light="true" />
                </BCol>
                <BCol lg="6">
                    <InputLabel value="To" :message="form.errors.end_at"/>
                    <TextInput v-model="form.end_at" type="date" class="form-control" :disabled="form.is_present" @input="handleInput('end_at')" :light="true" />
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="is_present" v-model="form.is_present" @change="form.end_at = null">
                        <label class="form-check-label fs-12" for="is_present">Present</label>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-n1">
                    <InputLabel value="Position Title" :message="form.errors.position_title"/>
                    <TextInput v-model="form.position_title" type="text" class="form-control" @input="handleInput('position_title')" :light="true" />
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel value="Department / Agency / Office / Company" :message="form.errors.department_agency"/>
                    <TextInput v-model="form.department_agency" type="text" class="form-control" @input="handleInput('department_agency')" :light="true" />
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel value="Monthly Salary" :message="form.errors.monthly_salary"/>
                    <TextInput v-model="form.monthly_salary" type="number" class="form-control" @input="handleInput('monthly_salary')" :light="true" />
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel value="Salary / Pay Grade" :message="form.errors.salary_grade"/>
                    <TextInput v-model="form.salary_grade" type="text" class="form-control" placeholder="00-0" @input="handleInput('salary_grade')" :light="true" />
                </BCol>
                <BCol lg="4" class="mt-0">
                    <InputLabel value="Status of Appointment" :message="form.errors.appointment_status"/>
                    <TextInput v-model="form.appointment_status" type="text" class="form-control" placeholder="e.g. Permanent" @input="handleInput('appointment_status')" :light="true" />
                </BCol>
                <BCol lg="12" style="margin-top: 13px;">
                    <div class="d-flex position-relative">
                        <div class="flex-shrink-0 fs-12">Government Service? :</div>
                        <div class="flex-grow-1 ms-2"></div>
                        <div class="flex-shrink-0">
                            <div class="d-inline-block" v-for="(list,index) in types" v-bind:key="index">
                                <div class="custom-control custom-radio mb-3 ms-4">
                                    <input type="radio" :id="'gov'+index" class="custom-control-input me-2" @input="handleInput('is_government')" :value="list.value" v-model="form.is_government">
                                    <label class="custom-control-label fs-12 fw-normal" :for="'gov'+index">{{list.name}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
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
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            form: useForm({
                id: null,
                start_at: null,
                end_at: null,
                is_present: false,
                position_title: null,
                department_agency: null,
                monthly_salary: null,
                salary_grade: null,
                appointment_status: null,
                is_government: 1,
                option: 'work_experience'
            }),
            types: [
                { value: 1, name: 'Yes' },
                { value: 0, name: 'No' }
            ],
            showModal: false,
            editable: false
        }
    },
    methods: {
        show(){
            this.form.reset();
            this.form.is_government = 1;
            this.editable = false;
            this.showModal = true;
        },
        edit(data){
            this.form.reset();
            Object.assign(this.form, {
                id: data.id,
                start_at: data.start_at,
                end_at: data.end_at,
                is_present: !data.end_at,
                position_title: data.position_title,
                department_agency: data.department_agency,
                monthly_salary: data.monthly_salary,
                salary_grade: data.salary_grade,
                appointment_status: data.appointment_status,
                is_government: data.is_government ? 1 : 0,
            });
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            const url = this.editable ? '/profile/pds/'+this.form.id : '/profile/pds';
            const method = this.editable ? 'put' : 'post';
            this.form[method](url,{
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.hide();
                    this.$emit('success', true);
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>
