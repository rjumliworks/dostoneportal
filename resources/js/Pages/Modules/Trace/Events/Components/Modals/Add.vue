<template>
<b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-light" title="Select Employee" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <BRow class="g-3">
                        <BCol lg="12"><hr class="text-muted mb-0 mt-0"/></BCol>
                        
                        <BCol lg="12">
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
                        <BCol lg="12" class="mt-n1 mb-n3" v-if="selected">
                            <hr class="text-muted"/>
                        </BCol>
                        <BCol md  v-if="selected">
                            <BRow class="align-items-center g-1">
                                <BCol md="auto">
                                    <div style="height: 3rem; width: 3rem;">
                                        <div class="avatar-title bg-white rounded-circle">
                                            <img :src="selected.avatar" alt="" class="avatar-sm rounded-circle">
                                        </div>
                                    </div>
                                </BCol>
                                <BCol md>
                                    <div class="ms-2">
                                        <h4 class="fs-16 fw-semibold mb-1">{{ selected.name }}</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div><span class="text-muted">Position :</span> {{selected.position}}</div>
                                            <div class="vr" style="width: 1px;"></div>
                                            <div><span class="text-muted">Division :</span> <span class="fw-medium">{{selected.division}}</span></div>
                                        </div>
                                    </div>
                                </BCol>
                            </BRow>
                        </BCol>
                        <BCol lg="12" class="mt-0 mb-n3" v-if="selected">
                            <hr class="text-muted"/>
                        </BCol>
                        <BCol lg="12" v-if="selected">
                            <div v-if="selected.available" class="alert alert-success alert-dismissible alert-label-icon label-arrow" role="alert">
                                <i class="ri-error-warning-line label-icon"></i>
                                <strong>Alert</strong> – This employee available on this date.
                            </div>
                             <div v-else class="alert alert-danger alert-dismissible alert-label-icon label-arrow" role="alert">
                                <i class="ri-error-warning-line label-icon"></i>
                                <strong>Alert</strong> – This employee is not available on this date.
                                  {{ conflictText }}
                            </div>
                        </BCol>
                        
                    </BRow>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button v-if="completedCount == totalWorkDays" @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
export default {
    props: ['id','start','end'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: this.id,
                user_id: null,
                division_id: null,
                option: 'participant'
            }),
            selected: null,
            names: [],
            keyword: null,
            showModal: false
        }
    },
    computed: {
        conflictText() {
            if (!this.selected || !this.selected.conflicts) return "";
            return this.selected.conflicts
                .map(conflict => {
                    const type = conflict.type;
                    const dateRanges = conflict.dates
                        .map(d => {
                            if (d.start === d.end) {
                                return d.start;
                            }
                            return `${d.start} to ${d.end}`;
                        })
                        .join("; ");
                    return `${type} on ${dateRanges}`;
                })
                .join(". ");
        }
    },
    mounted() {
        this.isCustomDropdown();
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        checkSearchStr: _.debounce(function (string) {
            this.keyword = string;
            this.search();
        }, 500),
        search(){
            axios.get('/events', {
                params: {
                    keyword: this.keyword,
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
            this.selected = data;
            this.form.user_id = data.value;
            this.form.division_id = data.division_id;
            this.keyword = null;
            document.getElementById("search-options").value = "";
            document.getElementById("search-options").focus();
        }, 
        submit(){
            this.form.post('/events',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
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