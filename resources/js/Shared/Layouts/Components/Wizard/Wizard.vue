<template>
    <b-modal body-class="p-0" header-class="p-0" hide-footer class="v-modal-custom" content-class="border-0 overflow-hidden" size="xl" centered no-close-on-backdrop hide-header-close>
        <BRow class="g-0">
            <BCol lg="12">
                <div class="modal-body p-4">
                    <h2 class="lh-base fw-semibold fs-22 mb-1">
                        Complete Your Personal Data Sheet <span class="text-danger">DOST-IX</span>
                    </h2>
                    <p class="text-muted fs-12 mb-4">
                        Welcome! Please take a few minutes to fill out your Personal Data Sheet (CS Form 212).
                        You can review and update any section later from your Profile page.
                    </p>

                    <div v-if="loading" class="text-center py-5">
                        <span class="spinner-border text-primary"></span>
                    </div>

                    <template v-else>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-12 text-muted">Step {{ currentStep }} of {{ steps.length }}</span>
                            <span class="fs-13 fw-semibold text-primary">{{ steps[currentStep-1].label }}</span>
                        </div>
                        <div class="progress mb-4" style="height: 4px;">
                            <div class="progress-bar bg-primary" :style="{width: progressPercent + '%'}"></div>
                        </div>

                        <div class="pe-1">
                            <component
                                :is="steps[currentStep-1].component"
                                :data="pdsData"
                                :dropdowns="dropdowns"
                                @saved="advance"
                                @failed="onStepFailed"
                                @refresh="fetchOnboardingData"
                                ref="step"
                            />
                        </div>

                        <div class="d-flex mt-4">
                            <b-button v-if="currentStep > 1" @click="back()" variant="light" type="button">Back</b-button>
                            <div class="flex-grow-1"></div>
                            <b-button @click="next()" variant="primary" type="button" :disabled="saving">
                                {{ currentStep === steps.length ? 'Finish' : 'Next' }}
                            </b-button>
                        </div>
                    </template>
                </div>
            </BCol>
        </BRow>
    </b-modal>
</template>
<script>
import PersonalInformation from './Steps/PersonalInformation.vue';
import AddressStep from './Steps/Address.vue';
import GovernmentIds from './Steps/GovernmentIds.vue';
import FamilyBackground from './Steps/FamilyBackground.vue';
import Education from './Steps/Education.vue';
import EligibilityStep from './Steps/Eligibility.vue';
import WorkExperienceStep from './Steps/WorkExperience.vue';
import VoluntaryWorkStep from './Steps/VoluntaryWork.vue';
import TrainingStep from './Steps/Training.vue';
import OtherAndReferences from './Steps/OtherAndReferences.vue';
import DeclarationStep from './Steps/Declaration.vue';

export default {
    components: {
        PersonalInformation, AddressStep, GovernmentIds, FamilyBackground, Education,
        EligibilityStep, WorkExperienceStep, VoluntaryWorkStep, TrainingStep,
        OtherAndReferences, DeclarationStep,
    },
    data(){
        return {
            loading: true,
            saving: false,
            currentStep: 1,
            dropdowns: {},
            pdsData: {},
            steps: [
                { label: 'Personal Information', component: 'PersonalInformation' },
                { label: 'Permanent & Home Address', component: 'AddressStep' },
                { label: 'Government IDs', component: 'GovernmentIds' },
                { label: 'Family Background', component: 'FamilyBackground' },
                { label: 'Educational Background', component: 'Education' },
                { label: 'Civil Service Eligibility', component: 'EligibilityStep' },
                { label: 'Work Experience', component: 'WorkExperienceStep' },
                { label: 'Voluntary Work', component: 'VoluntaryWorkStep' },
                { label: 'Learning & Development', component: 'TrainingStep' },
                { label: 'Other Information & References', component: 'OtherAndReferences' },
                { label: 'Declaration & Legal Info', component: 'DeclarationStep' },
            ],
        }
    },
    computed: {
        progressPercent(){
            return Math.round((this.currentStep / this.steps.length) * 100);
        }
    },
    mounted(){
        this.fetchAll();
    },
    methods: {
        fetchAll(){
            this.loading = true;
            Promise.all([
                axios.get('/dropdowns'),
                axios.get('/profile/onboarding'),
            ]).then(([dropdownsRes, onboardingRes]) => {
                this.dropdowns = dropdownsRes.data.dropdowns;
                this.pdsData = onboardingRes.data;
            }).catch(err => console.log(err))
            .finally(() => { this.loading = false; });
        },
        fetchOnboardingData(){
            return axios.get('/profile/onboarding').then(response => {
                this.pdsData = response.data;
            }).catch(err => console.log(err));
        },
        next(){
            this.saving = true;
            this.$refs.step.proceed();
        },
        back(){
            this.currentStep--;
        },
        advance(){
            this.saving = false;
            this.fetchOnboardingData().finally(() => {
                if (this.currentStep < this.steps.length) {
                    this.currentStep++;
                } else {
                    this.$emit('success', true);
                }
            });
        },
        onStepFailed(){
            this.saving = false;
        }
    }
}
</script>
