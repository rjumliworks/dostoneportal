<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-light" title="Assign Officer-in-Charge (OIC) / Signatory" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="p-3">
                <template v-if="step === 1">
                    <BCol lg="12">
                        <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show material-shadow fs-12" role="alert">
                            <i class="ri-alert-line label-icon"></i>
                            <strong>Notice:</strong>
                            This action will assign an <strong>Officer-in-Charge (OIC)</strong> or <strong>temporary signatory</strong>
                            while the currently designated employee is out of office.
                        </div>
                    </BCol>
                    <BCol lg="12" class="mt-4">
                        <form class="app-search d-none d-md-block mb-n3" style="margin-top: -33px;">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search Employee" autocomplete="off" id="search-options" />
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span @click="clear()" class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" id="search-close-options"></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                                <SimpleBar data-simplebar >
                                    <div class="notification-list">
                                        <b-link @click="chooseUser(list)" v-for="(list, index) of names" :key="index" class="d-flex dropdown-item notify-item py-2">
                                            <img :src="list.avatar" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                                            <div class="flex-1">
                                                <h6 class="m-0">{{ list.name}}</h6>
                                                <span class="fs-11 mb-0 text-muted">{{list.position}}</span>
                                            </div>
                                        </b-link>
                                    </div>
                                </SimpleBar>
                            </div>
                        </form>
                    </BCol>
                    <BCol lg="12" class="mt-n1 mb-n2" v-if="user">
                        <hr class="text-muted"/>
                    </BCol>
                    <BCol md v-if="user">
                        <BRow class="align-items-center g-1">
                            <BCol md="auto">
                                <div style="height: 3.5rem; width: 3.5rem;">
                                    <div class="avatar-title bg-white rounded-circle">
                                        <img :src="user.avatar" alt="" class="avatar-sm rounded-circle">
                                    </div>
                                </div>
                            </BCol>
                            <BCol md>
                                <div class="ms-2">
                                    <h4 class="fs-16 text-uppercase text-primary fw-semibold mb-0 mt-1">{{ user.name }}</h4>
                                    <div class="hstack gap-3 flex-wrap">
                                        <div class="text-muted">{{user.position}}</div>
                                    </div>
                                </div>
                            </BCol>
                        </BRow>
                    </BCol>
                    <BCol lg="12" class="mt-n1 mb-n3" v-if="user">
                        <hr class="text-muted"/>
                    </BCol>
                    <BCol lg="6" class="mt-2">
                        <InputLabel for="name" value="Start Date" :message="form.errors.start_at"/>
                        <TextInput id="name" v-model="form.start_at" type="date" class="form-control" placeholder="Please enter contact no." @input="onDateChange('start_at')" :light="true"/>
                    </BCol>
                    <BCol lg="6" class="mt-2">
                        <InputLabel for="name" value="End Date" :message="form.errors.end_at"/>
                        <TextInput id="name" v-model="form.end_at" type="date" class="form-control" placeholder="Please enter contact no." @input="onDateChange('end_at')" :light="true"/>
                    </BCol>
                    <BCol lg="12" class="mt-n1">
                        <hr class="text-muted mb-2"/>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="createPost" v-model="form.create_post">
                            <label class="form-check-label fs-13" for="createPost">
                                Create a post for this designation
                            </label>
                        </div>
                    </BCol>
                </template>

                <template v-else>
                    <BCol lg="12">
                        <BRow class="align-items-center g-1 mt-n4 mb-n3">
                            <BCol md="auto">
                                <div style="height: 3.5rem; width: 3.5rem;">
                                    <div class="avatar-title bg-white rounded-circle">
                                        <img :src="user.avatar" alt="" class="avatar-sm rounded-circle">
                                    </div>
                                </div>
                            </BCol>
                            <BCol md>
                                <div class="ms-2">
                                    <h4 class="fs-16 text-uppercase text-primary fw-semibold mb-0 mt-1">{{ user.name }}</h4>
                                    <div class="text-muted fs-12">{{ formattedRange() }}</div>
                                </div>
                            </BCol>
                        </BRow>
                    </BCol>
                    <BCol lg="12" class="mt-2 mb-n2">
                        <hr class="text-muted"/>
                    </BCol>
                    <BCol v-if="form.create_post" lg="12" class="mt-3">
                        <div :class="{ 'is-invalid': form.errors.content }">
                            <ckeditor :editor="editor" v-model="form.content" :config="editorConfig" @input="handleInput('content')" @ready="handleInput('content')"></ckeditor>
                        </div>
                        <div v-if="form.errors.content" class="invalid-feedback d-block">{{ form.errors.content }}</div>
                    </BCol>
                    <BCol v-else lg="12" class="mt-3">
                        <p class="text-muted fs-13 mb-0">No post will be created for this designation.</p>
                    </BCol>
                </template>
            </BRow>
        </form>
        <template v-slot:footer>
            <template v-if="step === 1">
                <b-button @click="hide()" variant="light" block>Cancel</b-button>
                <b-button @click="next()" variant="primary" :disabled="!canProceed" block>Next</b-button>
            </template>
            <template v-else>
                <b-button @click="back()" variant="light" block>Back</b-button>
                <b-button @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
            </template>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import dayjs from 'dayjs';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import { Ckeditor } from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';

export default {
    components: {InputLabel, TextInput, Multiselect, Ckeditor },
    props: ['divisions'],
    data(){
        return {
            form: useForm({
                signatory_id: null,
                user_id: null,
                start_at: null,
                end_at: null,
                create_post: true,
                content: null,
                option: 'signatory'
            }),
            keyword: null,
            names: [],
            user: null,
            selected: null,
            showModal: false,
            step: 1,
            editor: ClassicEditor,
            editorConfig: {
                licenseKey: 'GPL',
            },
        }
    },
     mounted() {
        this.isCustomDropdown();
    },
    computed: {
        canProceed(){
            return !!(this.user && this.form.start_at && this.form.end_at);
        }
    },
    methods: {
        show(data){
            this.form.reset();
            this.user = null;
            this.names = [];
            this.selected = data;
            this.form.signatory_id = this.selected.signatory_id;
            this.step = 1;
            this.showModal = true;
        },
        next(){
            if(!this.canProceed) return;
            this.buildTemplate();
            this.step = 2;
        },
        back(){
            this.step = 1;
        },
        checkSearchStr: _.debounce(function (string) {
            this.keyword = string;
            this.search();
        }, 500),
        search(){
            axios.get('/payroll', {
                params: {
                    keyword: this.keyword,
                    is_regular: this.is_regular,
                    cutoff_id: this.id,
                    start: this.start,
                    end: this.end,
                    option: 'search'
                }
            })
            .then(response => {
                if(response){ 
                    this.names = response.data; 
                }
            })
            .catch(err => console.log(err));
        },
        chooseUser(data){
            this.user = data;
            this.form.user_id = data.value;
            this.keyword = null;
            document.getElementById("search-options").value = "";
            document.getElementById("search-options").focus();
            this.buildTemplate();
        },
        onDateChange(field){
            this.handleInput(field);
            this.buildTemplate();
        },
        formattedDate(date){
            return date ? dayjs(date).format('D MMMM YYYY') : '';
        },
        formattedRange(){
            if(!this.form.start_at || !this.form.end_at) return '';
            const start = dayjs(this.form.start_at);
            const end = dayjs(this.form.end_at);
            if(start.isSame(end, 'month') && start.isSame(end, 'year')){
                return `${start.format('D')} – ${end.format('D MMMM YYYY')}`;
            }
            return `${start.format('D MMMM')} – ${end.format('D MMMM YYYY')}`;
        },
        formatOfficialName(person){
            if(!person) return '';
            const title = person.sex === 'Female' ? 'Ms.' : (person.sex === 'Male' ? 'Mr.' : '');
            const name = person.fullname ?? person.name ?? '';
            return `${title ? title + ' ' : ''}${name}`.toUpperCase();
        },
        buildTemplate(){
            if(!this.user || !this.selected || !this.form.start_at || !this.form.end_at) return;

            const TAB = '&nbsp;'.repeat(8);
            const holderPerson = this.selected.user ?? this.selected.oic;
            const holder = holderPerson ? this.formatOfficialName(holderPerson) : 'the designated official';
            const holderDivision = this.selected.user?.division ?? this.selected.oic?.division ?? '';
            const holderPosition = this.selected.user?.position ?? this.selected.oic?.position ?? '';
            const holderTitle = [holderDivision, holderPosition].filter(Boolean).join('- ');
            const designation = this.selected.designation ?? '';
            const division = this.selected.assigned?.name ?? '';
            const office = `Office of the ${designation}${division ? ' for the ' + division : ''}`;
            const newOfficial = this.formatOfficialName(this.user);
            const range = this.formattedRange();
            const subject = `Designation of ${newOfficial}, as Officer-in-Charge, ${office}, DOST-IX on ${range}`;

            this.form.content =
                `<p>Subject: <strong>${subject}</strong></p>`
                + `<p>In the interest of the service and in view of the official travel of DOST-IX `
                + `${holderTitle ? holderTitle + ', ' : ''}<strong>${holder}</strong>, `
                + `to <strong>{ADD LOCATION}</strong> on <strong>{ADD DATE}</strong>, DOST-IX ${this.user.position ? this.user.position + ', ' : ''}`
                + `<strong>${newOfficial}</strong>, is hereby designated as `
                + `Officer-in-Charge, <strong>${office}</strong>, in addition to present duties and responsibilities `
                + `without additional compensation.</p>`
                + `<p>${TAB}The following are the duties and responsibilities of the OIC:</p>`
                + `<ul><li>Please add and update this.</li></ul>`
                + `<p>${TAB}This order shall take effect on ${this.formattedDate(this.form.start_at)} and will remain in force `
                + `until the return of  <strong>${holder}</strong>.</p>`;
        },
        submit(){
            if(this.form.create_post && /\{ADD LOCATION\}|\{ADD DATE\}/.test(this.form.content || '')){
                this.form.errors.content = 'Please replace {ADD LOCATION} and {ADD DATE} with the actual details before submitting.';
                this.step = 2;
                return;
            }
            this.form.post('/signatories',{
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.step = 1;
                    this.hide();
                    this.$emit('success',true);
                },
                onError: (errors) => {
                    if(errors.signatory_id || errors.user_id || errors.start_at || errors.end_at){
                        this.step = 1;
                    }
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        clear(){
            this.user = null;
            this.names = [];
        },  
        hide(){
            this.editable = false;
            this.showModal = false;
        },
        isCustomDropdown() {
            var searchOptions = document.getElementById("search-close-options");
            var dropdown = document.getElementById("search-dropdown");
            var searchInput = document.getElementById("search-options");

            searchInput.addEventListener("focus", () => {
                var inputLength = searchInput.value.length;
                if (inputLength > 0) {
                    dropdown.classList.add("show");
                    searchOptions.classList.remove("d-none");
                } else {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });

            searchInput.addEventListener("keyup", () => {
                var inputLength = searchInput.value.length;
                if (inputLength > 0) {
                    dropdown.classList.add("show");
                    searchOptions.classList.remove("d-none");
                    this.checkSearchStr(searchInput.value);
                } else {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });

            searchOptions.addEventListener("click", () => {
                searchInput.value = "";
                dropdown.classList.remove("show");
                searchOptions.classList.add("d-none");
            });

            document.body.addEventListener("click", (e) => {
                if (e.target.getAttribute("id") !== "search-options") {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });
        }
    }
}
</script>
<style scoped>
    .dropdown-menu-lg {
        width: 95%;
    }
</style>