<template>
<div class="card bg-light-subtle shadow-none border">
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-file-shield-2-fill text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Declaration & Legal Questions</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">Answer honestly. Any misrepresentation may cause the filing of an administrative/criminal case.</p>
            </div>
            <div class="flex-shrink-0">
                <b-button variant="primary" size="sm" @click="submit()" :disabled="form.processing"><i class="ri-save-3-fill align-bottom me-1"></i> Save</b-button>
            </div>
        </div>
    </div>
    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 291px); overflow: auto;">
        <form class="customform">
            <div v-for="(q, index) in questions" :key="q.field" class="card border shadow-none p-3 mb-3">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1 fs-13 pe-3">{{ index+1 }}. {{ q.label }}</div>
                    <div class="flex-shrink-0">
                        <div class="d-inline-block me-3">
                            <div class="custom-control custom-radio d-inline-block me-3">
                                <input type="radio" :id="q.field+'_yes'" class="custom-control-input me-1" :value="1" v-model="form[q.field]">
                                <label class="custom-control-label fs-12" :for="q.field+'_yes'">YES</label>
                            </div>
                            <div class="custom-control custom-radio d-inline-block">
                                <input type="radio" :id="q.field+'_no'" class="custom-control-input me-1" :value="0" v-model="form[q.field]">
                                <label class="custom-control-label fs-12" :for="q.field+'_no'">NO</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="form[q.field] == 1 && q.details" class="mt-2">
                    <TextInput v-model="form[q.details]" type="text" class="form-control" :placeholder="q.detailsLabel || 'Please give details'" :light="true" />
                </div>
            </div>

            <hr class="text-muted"/>
            <h5 class="fs-14 text-primary mb-3">Government Issued ID</h5>
            <BRow class="g-3">
                <BCol lg="4">
                    <InputLabel value="Government Issued ID" :message="form.errors.government_id_type"/>
                    <TextInput v-model="form.government_id_type" type="text" class="form-control" placeholder="e.g. Passport, GSIS, SSS, PRC" :light="true" />
                </BCol>
                <BCol lg="4">
                    <InputLabel value="ID / License / Passport No." :message="form.errors.government_id_number"/>
                    <TextInput v-model="form.government_id_number" type="text" class="form-control" :light="true" />
                </BCol>
                <BCol lg="4">
                    <InputLabel value="Date / Place of Issuance" :message="form.errors.government_id_issued_at"/>
                    <TextInput v-model="form.government_id_issued_at" type="text" class="form-control" :light="true" />
                </BCol>
            </BRow>
        </form>
    </div>
</div>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    props: ['declaration'],
    data(){
        return {
            questions: [
                { field: 'related_third_degree', label: 'Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office, or to the person who has immediate supervision over you, within the third degree?', details: 'related_third_degree_details' },
                { field: 'related_fourth_degree', label: 'Within the fourth degree (for Local Government Unit - Career Employees)?', details: 'related_fourth_degree_details' },
                { field: 'admin_offense_found_guilty', label: 'Have you ever been found guilty of any administrative offense?', details: 'admin_offense_details' },
                { field: 'criminally_charged', label: 'Have you been criminally charged before any court?', details: 'criminal_charge_details' },
                { field: 'convicted_crime', label: 'Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?', details: 'convicted_crime_details' },
                { field: 'separated_from_service', label: 'Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?', details: 'separated_from_service_details' },
                { field: 'election_candidate', label: 'Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?', details: 'election_candidate_details' },
                { field: 'resigned_to_campaign', label: 'Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?', details: 'resigned_to_campaign_details' },
                { field: 'immigrant_status', label: 'Have you acquired the status of an immigrant or permanent resident of another country?', details: 'immigrant_status_country', detailsLabel: 'Please give details (country)' },
                { field: 'indigenous_group_member', label: 'Are you a member of any indigenous group?', details: 'indigenous_group_details', detailsLabel: 'Please specify' },
                { field: 'is_pwd', label: 'Are you a person with disability?', details: 'pwd_id_number', detailsLabel: 'Please specify ID No.' },
                { field: 'is_solo_parent', label: 'Are you a solo parent?', details: 'solo_parent_id_number', detailsLabel: 'Please specify ID No.' },
            ],
            form: useForm({
                related_third_degree: 0,
                related_third_degree_details: null,
                related_fourth_degree: 0,
                related_fourth_degree_details: null,
                admin_offense_found_guilty: 0,
                admin_offense_details: null,
                criminally_charged: 0,
                criminal_charge_details: null,
                convicted_crime: 0,
                convicted_crime_details: null,
                separated_from_service: 0,
                separated_from_service_details: null,
                election_candidate: 0,
                election_candidate_details: null,
                resigned_to_campaign: 0,
                resigned_to_campaign_details: null,
                immigrant_status: 0,
                immigrant_status_country: null,
                indigenous_group_member: 0,
                indigenous_group_details: null,
                is_pwd: 0,
                pwd_id_number: null,
                is_solo_parent: 0,
                solo_parent_id_number: null,
                government_id_type: null,
                government_id_number: null,
                government_id_issued_at: null,
                option: 'declaration'
            }),
        }
    },
    created(){
        if (this.declaration) {
            Object.keys(this.form.data()).forEach(key => {
                if (key !== 'option' && this.declaration[key] !== undefined && this.declaration[key] !== null) {
                    this.form[key] = this.declaration[key];
                }
            });
        }
    },
    methods: {
        submit(){
            this.form.post('/profile/pds', {
                preserveScroll: true,
            });
        }
    }
}
</script>
