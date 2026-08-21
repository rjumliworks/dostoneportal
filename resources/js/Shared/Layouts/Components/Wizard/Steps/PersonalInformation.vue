<template>
    <div>
        <h5 class="fs-14 text-primary mb-">Personal Information</h5>
        <div class="card-body bg-light-subtle p-4 rounded-3">
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="3">
                    <InputLabel value="Agency Employee No."/>
                    <TextInput v-model="agency_employee_no" type="text" class="form-control" readonly/>
                </BCol>
                <BCol lg="3">
                    <InputLabel value="Sex" :message="form.errors.sex_id"/>
                    <Multiselect :options="dropdowns.sexes" :searchable="true" label="name" v-model="form.sex_id" placeholder="Select Sex" @input="clearError('sex_id')"/>
                </BCol>
                <BCol lg="3">
                    <InputLabel value="Email Address"/>
                    <TextInput :model-value="email" type="text" class="form-control" readonly/>
                </BCol>
                <BCol lg="3">
                    <InputLabel value="Contact No." :message="form.errors.mobile"/>
                    <TextInput v-model="form.mobile" type="text" class="form-control" @input="clearError('mobile')"/>
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Civil Status" :message="form.errors.marital_id"/>
                    <Multiselect :options="dropdowns.maritals" :searchable="true" label="name" v-model="form.marital_id" placeholder="Select Civil Status" @input="clearError('marital_id')"/>
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Date of Birth" :message="form.errors.birthdate"/>
                    <TextInput v-model="form.birthdate" type="date" class="form-control" @input="clearError('birthdate')"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel value="Place of Birth" :message="form.errors.place_of_birth"/>
                    <TextInput v-model="form.place_of_birth" type="text" class="form-control" placeholder="e.g. Zamboanga City" @input="clearError('place_of_birth')"/>
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Religion" :message="form.errors.religion_id"/>
                    <Multiselect :options="dropdowns.religions" :searchable="true" label="name" v-model="form.religion_id" placeholder="Select Religion" @input="clearError('religion_id')"/>
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Blood Type" :message="form.errors.blood_id"/>
                    <Multiselect :options="dropdowns.bloods" :searchable="true" label="name" v-model="form.blood_id" placeholder="Select Blood Type" @input="clearError('blood_id')"/>
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Height (m)" :message="form.errors.height"/>
                    <TextInput v-model="form.height" type="number" class="form-control" placeholder="e.g. 1.65" @input="clearError('height')"/>
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Weight (kg)" :message="form.errors.weight"/>
                    <TextInput v-model="form.weight" type="number" class="form-control" @input="clearError('weight')" />
                </BCol>
                <BCol lg="12" class="mt-0"><hr class="text-muted mb-2"/></BCol>
                <BCol lg="12" class="mt-2">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="flex-shrink-0 fs-12 fw-semibold me-3">Citizenship:</div>
                        <div class="custom-control custom-radio d-inline-block me-3">
                            <input type="radio" id="cit_fil" class="custom-control-input me-1" value="filipino" v-model="form.citizenship">
                            <label class="custom-control-label fs-12" for="cit_fil">Filipino</label>
                        </div>
                        <div class="custom-control custom-radio d-inline-block">
                            <input type="radio" id="cit_dual" class="custom-control-input me-1" value="dual" v-model="form.citizenship">
                            <label class="custom-control-label fs-12" for="cit_dual">Dual Citizenship</label>
                        </div>
                    </div>
                </BCol>
                <template v-if="form.citizenship === 'dual'">
                    <BCol lg="6" class="mt-2">
                        <InputLabel value="Acquired By" :message="form.errors.citizenship_type"/>
                        <div>
                            <div class="custom-control custom-radio d-inline-block me-3">
                                <input type="radio" id="cit_birth" class="custom-control-input me-1" value="by_birth" v-model="form.citizenship_type">
                                <label class="custom-control-label fs-12" for="cit_birth">By Birth</label>
                            </div>
                            <div class="custom-control custom-radio d-inline-block">
                                <input type="radio" id="cit_nat" class="custom-control-input me-1" value="by_naturalization" v-model="form.citizenship_type">
                                <label class="custom-control-label fs-12" for="cit_nat">By Naturalization</label>
                            </div>
                        </div>
                    </BCol>
                    <BCol lg="6" class="mt-2">
                        <InputLabel value="Country" :message="form.errors.citizenship_country"/>
                        <TextInput v-model="form.citizenship_country" type="text" class="form-control" @input="clearError('citizenship_country')" :light="true"/>
                    </BCol>
                </template>
            </BRow>
        </form>
        </div>
    </div>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { Multiselect, TextInput, InputLabel },
    props: ['data', 'dropdowns'],
    data(){
        const profile = this.data.profile || {};
        return {
            form: useForm({
                sex_id: profile.sex_id ?? null,
                marital_id: profile.marital_id ?? null,
                religion_id: profile.religion_id ?? null,
                blood_id: profile.blood_id ?? null,
                mobile: profile.mobile ?? null,
                birthdate: profile.birthdate ?? null,
                height: profile.height ?? null,
                weight: profile.weight ?? null,
                citizenship: profile.citizenship ?? 'filipino',
                citizenship_type: profile.citizenship_type ?? null,
                citizenship_country: profile.citizenship_country ?? null,
                place_of_birth: profile.place_of_birth ?? null,
                option: 'personal'
            }),
        }
    },
    computed: {
        agency_employee_no(){ return this.$page.props.user.data.username; },
        email(){ return this.$page.props.user.data.email; },
    },
    methods: {
        proceed(){
            this.form.put('/profile/updated', {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => this.$emit('saved'),
                onError: () => this.$emit('failed'),
            });
        },
        clearError(field){ this.form.errors[field] = false; }
    }
}
</script>
<style scoped>
:deep(.multiselect),
:deep(.multiselect-wrapper) {
    background-color: #fff !important;
}
</style>
