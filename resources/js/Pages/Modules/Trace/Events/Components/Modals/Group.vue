<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="modalTitle" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" v-if="step === 'select'">
            <BRow class="g-3 p-2 mb-2">
                <div class="col-sm-12">
                    <div class="form-check card-radio">
                        <input id="groupTypeAll" name="groupType" v-model="selectedType" value="all" type="radio" class="form-check-input">
                        <label class="form-check-label" for="groupTypeAll">
                            <span class="fs-16 text-muted me-2"><i class="ri-team-fill align-bottom"></i></span>
                            <span class="fs-14 text-wrap">All Employees</span>
                        </label>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-check card-radio">
                        <input id="groupTypeGroup" name="groupType" v-model="selectedType" value="group" type="radio" class="form-check-input">
                        <label class="form-check-label" for="groupTypeGroup">
                            <span class="fs-16 text-muted me-2"><i class="ri-group-2-fill align-bottom"></i></span>
                            <span class="fs-14 text-wrap">Select Group of Employees</span>
                        </label>
                    </div>
                </div>
            </BRow>
        </form>
        <form class="customform" v-else>
            <BRow class="g-3 p-2">
                <BCol lg="12" v-if="form.type === 'group'">
                    <label class="form-label">Division <span class="text-danger">*</span></label>
                    <select class="form-select" v-model="form.division_id" @change="onDivisionChange">
                        <option :value="null">Select Division</option>
                        <option v-for="division in divisions" :key="division.value" :value="division.value">{{ division.name }}</option>
                    </select>
                    <span class="text-danger fs-12" v-if="form.errors.division_id">{{ form.errors.division_id }}</span>
                </BCol>
                <BCol lg="12" v-if="form.type === 'group' && form.division_id">
                    <label class="form-label">Unit <span class="text-muted">(optional)</span></label>
                    <select class="form-select" v-model="form.unit_id" @change="fetchCount" :disabled="!units.length">
                        <option :value="null">All Units</option>
                        <option v-for="unit in units" :key="unit.value" :value="unit.value">{{ unit.name }}</option>
                    </select>
                </BCol>
                <BCol lg="12" class="mt-2">
                    <div class="alert alert-info mb-0" v-if="loadingCount">
                        Checking matching employees...
                    </div>
                    <div class="alert alert-success mb-0" v-else-if="previewCount > 0">
                        <i class="ri-checkbox-circle-fill align-bottom me-1"></i>
                        {{ previewCount }} employee(s) will be added to this activity.
                    </div>
                    <div class="alert alert-warning mb-0" v-else-if="previewCount === 0">
                        <i class="ri-error-warning-line align-bottom me-1"></i>
                        No new employees match this selection.
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button v-if="step === 'confirm'" @click="back()" variant="light">Back</b-button>
            <b-button v-else @click="hide()" variant="light">Cancel</b-button>
            <b-button v-if="step === 'select'" @click="continueToForm()" :disabled="!selectedType" variant="primary">Continue</b-button>
            <b-button v-else @click="submit()" :disabled="!canSubmit || form.processing" variant="primary">Add Employees</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
export default {
    props: ['id'],
    data(){
        return {
            showModal: false,
            step: 'select',
            selectedType: null,
            divisions: [],
            units: [],
            previewCount: null,
            loadingCount: false,
            form: useForm({
                id: this.id,
                type: null,
                division_id: null,
                unit_id: null,
                option: 'participants'
            })
        }
    },
    computed: {
        modalTitle(){
            return this.step === 'select' ? 'Select Group' : 'Add Employees';
        },
        canSubmit(){
            if(this.form.type === 'group' && !this.form.division_id) return false;
            return this.previewCount > 0;
        }
    },
    methods: {
        show(){
            this.reset();
            this.fetchDivisions();
            this.showModal = true;
        },
        fetchDivisions(){
            axios.get('/dropdowns')
            .then(response => {
                this.divisions = response.data.dropdowns.divisions;
            })
            .catch(err => console.log(err));
        },
        fetchUnits(division_id){
            if(!division_id){
                this.units = [];
                return;
            }
            axios.get('/search', {
                params: { option: 'units', code: division_id }
            })
            .then(response => {
                this.units = response.data;
            })
            .catch(err => console.log(err));
        },
        onDivisionChange(){
            this.form.unit_id = null;
            this.fetchUnits(this.form.division_id);
            this.fetchCount();
        },
        fetchCount: _.debounce(function(){
            if(this.form.type === 'group' && !this.form.division_id){
                this.previewCount = null;
                return;
            }
            this.loadingCount = true;
            axios.get('/activities', {
                params: {
                    id: this.form.id,
                    option: 'group_count',
                    type: this.form.type,
                    division_id: this.form.division_id,
                    unit_id: this.form.unit_id
                }
            })
            .then(response => {
                this.previewCount = response.data.count;
            })
            .catch(err => console.log(err))
            .finally(() => {
                this.loadingCount = false;
            });
        }, 400),
        continueToForm(){
            if(!this.selectedType) return;
            this.form.type = this.selectedType;
            this.form.division_id = null;
            this.form.unit_id = null;
            this.units = [];
            this.previewCount = null;
            this.step = 'confirm';
            if(this.selectedType === 'all'){
                this.fetchCount();
            }
        },
        back(){
            this.step = 'select';
            this.previewCount = null;
        },
        submit(){
            this.form.id = this.id;
            this.form.post('/activities', {
                preserveScroll: true,
                onSuccess: () => {
                    this.hide();
                },
            });
        },
        reset(){
            this.step = 'select';
            this.selectedType = null;
            this.divisions = [];
            this.units = [];
            this.previewCount = null;
            this.loadingCount = false;
            this.form.reset();
            this.form.clearErrors();
            this.form.id = this.id;
        },
        hide(){
            this.reset();
            this.showModal = false;
        }
    }
}
</script>
