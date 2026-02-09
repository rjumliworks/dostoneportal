<template>
    <b-modal body-class="p-0" header-class="p-0" hide-footer class="v-modal-custom" content-class="border-0 overflow-hidden" size="xl" centered no-close-on-backdrop hide-header-close>
        <BRow class="g-0">
            <BCol lg="12">
                <div class="modal-body p-5">
                    <h2 class="lh-base fw-semibold fs-22">
                        Complete Your Profile <span class="text-danger">DOST-IX</span>
                    </h2>
                    <p class="text-muted fs-12 mb-4">
                        Welcome! Before you proceed, please take a moment to review and update your employee profile.
                        This information is required for official records and future reference.
                        <br />
                        Kindly ensure details such as your address, educational background, and eligibility are accurate and complete.
                    </p>

                    <div class="border rounded no-border mb-3">
                        <BRow class="p-4 customform">
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Username"/>
                                <TextInput id="name" v-model="form.username" type="text" class="form-control" placeholder="Please enter username" readonly :light="true"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Email Address"/>
                                <TextInput id="name" v-model="form.email" type="email" class="form-control" placeholder="Please enter email" readonly :light="true"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Birthdate" :message="form.errors.email"/>
                                <TextInput id="name" v-model="form.birthdate" type="date" class="form-control" placeholder="Please enter birthdate" @input="handleInput('birthdate')" :light="true"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Contact no." :message="form.errors.mobile"/>
                                <TextInput id="name" v-model="form.mobile" type="text" class="form-control" placeholder="Please enter contact no." @input="handleInput('mobile')" :light="true"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Sex" :message="form.errors.sex_id"/>
                                <Multiselect :options="dropdowns.sexes" :searchable="true" label="name" v-model="form.sex_id" placeholder="Select Sex" @input="handleInput('sex_id')"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Marital Status" :message="form.errors.marital_id"/>
                                <Multiselect :options="dropdowns.maritals" :searchable="true" label="name" v-model="form.marital_id" placeholder="Select Marital Status" @input="handleInput('marital_id')"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Religion" :message="form.errors.religion_id"/>
                                <Multiselect :options="dropdowns.religions" :searchable="true" label="name" v-model="form.religion_id" placeholder="Select Religion" @input="handleInput('religion_id')"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Blood Type" :message="form.errors.blood_id"/>
                                <Multiselect :options="dropdowns.bloods" :searchable="true" label="name" v-model="form.blood_id" placeholder="Select Blood Type" @input="handleInput('blood_id')"/>
                            </BCol>
                            <BCol lg="12" class="mt-1 mb-n2"><hr class="text-muted"/></BCol>
                            <span class="fw-semibold text-success fs-12 mt-1">Employee Address</span>
                            <BCol lg="12" class="mt-0 mb-n2"><hr class="text-muted"/></BCol>
                            <BCol lg="12" class="mt-1">
                                <div class="d-flex">
                                    <div style="width: 100%;">
                                        <InputLabel value="Permanent Address" :message="form.errors.address"/>
                                        <TextInput readonly v-model="address" type="text" class="form-control" placeholder="House No., Street, Barangay, City/Municipality, Province" @input="handleInput('address')" :light="true" />
                                    </div>
                                    <div class="flex-shrink-0">
                                        <b-button @click="addLocation(index)" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                                    </div>
                                </div>
                            </BCol>
                            <BCol lg="12" class="mt-1">
                                <div class="d-flex">
                                    <div style="width: 100%;">
                                        <InputLabel value="Home Address" :message="form.errors.address"/>
                                        <TextInput readonly v-model="address" type="text" class="form-control" placeholder="House No., Street, Barangay, City/Municipality, Province" @input="handleInput('address')" :light="true" />
                                    </div>
                                    <div class="flex-shrink-0">
                                        <b-button @click="addLocation(index)" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                                    </div>
                                </div>
                            </BCol>
                            <BCol lg="12" class="mt-1 mb-n2"><hr class="text-muted"/></BCol>
                            <span class="fw-semibold text-success fs-12 mt-1">Employee Academics</span>
                            <BCol lg="12" class="mt-0 mb-n2"><hr class="text-muted"/></BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="School" :message="form.errors.sex_id"/>
                                <Multiselect :options="dropdowns.sexes" :searchable="true" label="name" v-model="form.sex_id" placeholder="Select Sex" @input="handleInput('sex_id')"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="Course" :message="form.errors.sex_id"/>
                                <Multiselect :options="dropdowns.sexes" :searchable="true" label="name" v-model="form.sex_id" placeholder="Select Sex" @input="handleInput('sex_id')"/>
                            </BCol>
                            <BCol lg="3" class="mt-0">
                                <InputLabel for="name" value="School" :message="form.errors.sex_id"/>
                                <Multiselect :options="dropdowns.sexes" :searchable="true" label="name" v-model="form.sex_id" placeholder="Select Sex" @input="handleInput('sex_id')"/>
                            </BCol>
                        </BRow>
                    </div>
                    <div class="d-flex mb-n3 mt-4">
                        <div class="flex-shrink-0 me-3">
                            <span class="text-muted fs-13">You have answered questions in the morale survey.</span>
                        </div>
                        <div class="flex-grow-1"></div>
                        <div class="flex-shrink-0">
                            <BButton @click="submit()" variant="primary" class="mt-n1" type="button" id="button-addon1">Submit</BButton>
                        </div>
                    </div>
                </div>
            </BCol>
        </BRow>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { Multiselect, TextInput, InputLabel },
    data(){
        return {
            form: useForm({
                questions: null,
                option: 'answers'
            }),
            dropdowns: []
        }
    },
    mounted() {
        this.fetch();
    },
    methods: { 
         fetch(){
            axios.get('/dropdowns')
            .then(response => {
                if(response){    
                    this.dropdowns = response.data.dropdowns;
                }
            })
            .catch(err => console.log(err));
        },
        submit(){
            this.form.questions = this.questions;
            this.form.post('/surveys',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.form.reset();
                    this.hide();
                    this.$emit('success',true);
                },
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>