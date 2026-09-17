<template>
    <b-modal v-model="showModal" size="lg" header-class="p-3 bg-light" :title="(editable) ? 'Update Education' : 'Add Education'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 mt-n1">
                
                <BCol lg="6" class="mt-1">
                    <InputLabel value="Level" :message="form.errors.level_id"/>
                    <Multiselect :options="levels" :searchable="true" label="name" v-model="form.level_id" placeholder="Select Level" @input="handleInput('level_id')"/>
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Attended From" :message="form.errors.attended_from"/>
                    <TextInput v-model="form.attended_from" v-maska data-maska="####" type="text" class="form-control" placeholder="Year" @input="handleInput('attended_from')" :light="true" />
                </BCol>
                <BCol lg="3" class="mt-1">
                    <InputLabel value="Attended To" :message="form.errors.attended_to"/>
                    <TextInput v-model="form.attended_to" v-maska data-maska="####" type="text" class="form-control" placeholder="Year" @input="handleInput('attended_to')" :light="true" />
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel value="School" :message="form.errors.school_id"/>
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <Multiselect :options="schools" v-model="form.school_id" @search-change="fetchSchool" label="name" @input="handleInput('school_id')" :searchable="true" placeholder="Search School"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button type="button" @click="openAddSchool" variant="light" class="waves-effect waves-light ms-1" v-b-tooltip.hover title="School not in the list?"><i class="ri-add-line"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-2 mb-2" v-if="showCourse">
                    <InputLabel value="Basic Education / Degree / Course" :message="form.errors.course_id"/>
                    <Multiselect :options="courses" v-model="form.course_id" @search-change="fetchCourse" label="name" @input="handleInput('course_id')" :searchable="true" placeholder="Search Course"/>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                <BCol lg="12" style="margin-top: 13px; margin-bottom: -10px;">
                    <div class="d-flex position-relative">
                        <div class="flex-shrink-0 fs-12">Is this education still ongoing? :</div>
                        <div class="flex-grow-1 ms-2"></div>
                        <div class="flex-shrink-0">
                            <div class="d-inline-block" v-for="(list,index) in types" v-bind:key="index">
                                <div class="custom-control custom-radio mb-3 ms-4">
                                    <input type="radio" :id="'ongoing'+index" class="custom-control-input me-2" @input="handleInput('is_ongoing')" :value="list.value" v-model="form.is_ongoing">
                                    <label class="custom-control-label fs-12 fw-normal" :for="'ongoing'+index">{{list.name}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12"><hr class="text-muted mt-n2 mb-n4"/></BCol>
                <BCol lg="6" class="mt-0" v-if="!form.is_ongoing">
                    <InputLabel value="Year Graduated" :message="form.errors.graduated_at"/>
                    <TextInput v-model="form.graduated_at" v-maska data-maska="####" type="text" class="form-control" placeholder="Year" @input="handleInput('graduated_at')" :light="true" />
                </BCol>
                <BCol lg="6" class="mt-0" v-else>
                    <InputLabel value="Highest Level / Units Earned" :message="form.errors.units_earned"/>
                    <TextInput v-model="form.units_earned" type="text" class="form-control" placeholder="e.g. 3rd Year" @input="handleInput('units_earned')" :light="true" />
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel value="Scholarship / Academic Honors Received"/>
                    <TextInput v-model="form.honors" type="text" class="form-control" placeholder="N/A if none" :light="true" />
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>

    <b-modal v-model="showAddSchoolModal" header-class="p-3 bg-light" title="Add School" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <BRow class="g-3 mt-n1">
            <BCol lg="12">
                <InputLabel value="School Name" :message="addSchoolError"/>
                <TextInput v-model="newSchoolName" type="text" class="form-control" placeholder="Enter school name" :light="true" @input="addSchoolError = null" />
            </BCol>
        </BRow>
        <template v-slot:footer>
            <b-button @click="cancelAddSchool" variant="light" block>Cancel</b-button>
            <b-button @click="addSchool" variant="primary" :disabled="!newSchoolName || addingSchool" block>Add</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { vMaska } from "maska/vue"
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect },
    directives: { maska: vMaska },
    props: ['levels'],
    data(){
        return {
            form: useForm({
                id: null,
                school_id: null,
                course_id: null,
                level_id: null,
                is_ongoing: 0,
                attended_from: null,
                attended_to: null,
                graduated_at: null,
                units_earned: null,
                honors: null,
                option: 'academic'
            }),
            types: [
                { value: 1, name: 'Yes' },
                { value: 0, name: 'No' }
            ],
            schools: [],
            courses: [],
            showModal: false,
            editable: false,
            showAddSchoolModal: false,
            newSchoolName: null,
            addingSchool: false,
            addSchoolError: null
        }
    },
    computed: {
        showCourse(){
            const level = this.levels?.find(l => l.value === this.form.level_id);
            const name = level?.name?.trim().toLowerCase();
            return name !== 'elementary' && name !== 'junior high school';
        }
    },
    watch: {
        'form.level_id'(){
            if (!this.showCourse) {
                this.form.course_id = null;
                this.courses = [];
            }
        }
    },
    methods: {
        show(){
            this.form.reset();
            this.form.is_ongoing = 0;
            this.editable = false;
            this.cancelAddSchool();
            this.showModal = true;
        },
        edit(data){
            this.form.reset();
            this.form.id = data.id;
            this.form.school_id = data.school_id;
            this.form.course_id = data.course_id;
            this.form.level_id = data.level_id;
            this.form.is_ongoing = data.is_ongoing ? 1 : 0;
            this.form.attended_from = data.attended_from;
            this.form.attended_to = data.attended_to;
            this.form.graduated_at = data.graduated_at;
            this.form.units_earned = data.units_earned;
            this.form.honors = data.honors;
            this.schools = data.school ? [{ value: data.school_id, name: data.school.name }] : [];
            this.courses = data.course ? [{ value: data.course_id, name: data.course.name }] : [];
            this.editable = true;
            this.cancelAddSchool();
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
        fetchSchool: _.debounce(function (code) {
            axios.get('/search', { params: { option: 'schools', keyword: code } })
            .then(response => { this.schools = response.data; })
            .catch(err => console.log(err));
        }, 300),
        fetchCourse: _.debounce(function (code) {
            axios.get('/search', { params: { option: 'courses', keyword: code } })
            .then(response => { this.courses = response.data; })
            .catch(err => console.log(err));
        }, 300),
        openAddSchool(){
            this.newSchoolName = null;
            this.addSchoolError = null;
            this.showAddSchoolModal = true;
        },
        addSchool(){
            if (!this.newSchoolName) return;
            this.addingSchool = true;
            this.addSchoolError = null;
            axios.post('/profile/pds/schools', { name: this.newSchoolName })
            .then(response => {
                const school = response.data.data;
                this.schools = [{ value: school.value, name: school.name }];
                this.form.school_id = school.value;
                this.handleInput('school_id');
                this.cancelAddSchool();
            })
            .catch(err => {
                this.addSchoolError = err.response?.data?.errors?.name?.[0] || 'Unable to add school.';
            })
            .finally(() => { this.addingSchool = false; });
        },
        cancelAddSchool(){
            this.showAddSchoolModal = false;
            this.newSchoolName = null;
            this.addSchoolError = null;
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.editable = false;
            this.cancelAddSchool();
            this.showModal = false;
        }
    }
}
</script>
