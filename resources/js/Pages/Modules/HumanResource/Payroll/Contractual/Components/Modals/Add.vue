<template>
<b-modal v-model="showModal" style="--vz-modal-width: 900px;" header-class="p-3 bg-light" title="Select Employee" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
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
                        <BCol lg="12" class="mt-n1 mb-n4" v-if="selected">
                            <hr class="text-muted"/>
                        </BCol>
                        <BCol md  v-if="selected">
                            <BRow class="align-items-center g-1">
                                <BCol md="auto">
                                    <div style="height: 3.5rem; width: 3.5rem;">
                                        <div class="avatar-title bg-white rounded-circle">
                                            <img :src="selected.avatar" alt="" class="avatar-sm rounded-circle">
                                        </div>
                                    </div>
                                </BCol>
                                <BCol md>
                                    <div class="ms-2">
                                        <h4 class="fs-18 fw-semibold mb-1">{{ selected.name }}</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div><span class="text-muted">Position :</span> {{selected.position}}</div>
                                            <div class="vr" style="width: 1px;"></div>
                                            <div><span class="text-muted">Division :</span> <span class="fw-medium">{{selected.division}}</span></div>
                                        </div>
                                    </div>
                                </BCol>
                            </BRow>
                        </BCol>
                        <BCol lg="12" class="mt-n1 mb-n3" v-if="selected">
                            <hr class="text-muted"/>
                        </BCol>
                        <BCol lg="12" v-if="selected && selected.already_in_payroll">
                            <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow" role="alert">
                                <i class="ri-error-warning-line label-icon"></i>
                                <strong>Alert</strong> – This employee is already in the payroll.
                            </div>
                        </BCol>
                        <BCol lg="12" v-if="selected && !selected.already_in_payroll">
                            <div class="row g-3 mt-n3 mb-3">
                                <div class="col-sm-3">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-check-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Completed DTR :</p>
                                                <h5 class="mb-0 fs-12">{{completedCount}} / {{ totalWorkDays }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-todo-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Holiday :</p>
                                                <h5 class="mb-0 fs-12">{{holidayCount}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-map-pin-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Official Travel :</p>
                                                <h5 class="mb-0 fs-12">{{travelCount}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-road-map-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Official Business :</p>
                                                <h5 class="mb-0 fs-12">{{businessCount}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-sm-3">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-close-circle-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Absent :</p>
                                                <h5 class="mb-0 fs-12">{{absentCount}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div v-if="completedCount != totalWorkDays"
                                class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show material-shadow mt-2"
                                role="alert">
                                <i class="ri-alert-line label-icon"></i>
                                <strong>Warning</strong> - Employee is not eligible to be added to the payroll because their DTR is incomplete.
                            </div>
                            <div class="table-responsive" style="height: calc(100vh - 565px); overflow: auto;">
                                <table class="table table-bordered align-middle mb-1">
                                    <thead class="bg-primary fs-11 thead-fixed">
                                        <tr class="text-white">
                                            <th class="text-center" style="width: 25%;">Date</th>
                                            <th class="text-center" style="width: 15%;">Am In</th>
                                            <th class="text-center" style="width: 15%;">Am Out</th>
                                            <th class="text-center" style="width: 15%;">Pm In</th>
                                            <th class="text-center" style="width: 15%;">Pm Out</th>
                                            <th class="text-center" style="width: 15%;">Is updated</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-white fs-12">
                                        <tr v-for="(list,index) in selected.dtrs" v-bind:key="index" :class="{
                                            'bg-success-subtle': list.is_completed,
                                            'bg-dark-subtle': list.status === 'Holiday' || list.status === 'Non-working Day',
                                            'bg-warning-subtle': list.status === 'Official Travel',
                                            'bg-danger-subtle': list.status === 'Absent'
                                        }">
                                            <td class="text-center">{{ list.date }}</td>
                                            <template v-if="list.status === 'Non-working Day'">
                                                <td class="text-center" colspan="5">{{list.title}}</td>
                                            </template>
                                             <template v-else-if="list.status === 'Holiday'">
                                                <td class="text-center" colspan="5">{{list.title}}</td>
                                            </template>
                                            <template v-else-if="list.status === 'Official Travel'">
                                                <td class="text-center" colspan="5">Official Travel : {{list.title}}</td>
                                            </template>
                                            <template v-else-if="list.status === 'Official Business'">
                                                <td class="text-center" colspan="5">Official Business : {{list.title}}</td>
                                            </template>
                                            <template v-else-if="list.status === 'Absent'">
                                                <td class="text-center" colspan="5">Absent</td>
                                            </template>
                                            <template v-else>
                                                <td class="text-center">{{ list.am_in ? list.am_in.time : '-' }}</td>
                                                <td class="text-center">{{ list.am_out ? list.am_out.time : '-' }}</td>
                                                <td class="text-center">{{ list.pm_in ? list.pm_in.time : '-' }}</td>
                                                <td class="text-center">{{ list.pm_out ? list.pm_out.time : '-' }}</td>
                                                <td class="text-center">
                                                    <span v-if="list.is_updated" class="badge bg-border border-success border border-danger text-danger">Updated</span>
                                                    <span v-else class="badge bg-border border-success border border-primary text-primary">Not Updated</span>
                                                </td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
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
    props: ['is_regular','id','start','end'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: this.id,
                user_id: null,
                option: 'payroll'
            }),
            selected: null,
            names: [],
            keyword: null,
            showModal: false
        }
    },
    mounted() {
        this.isCustomDropdown();
    },
    computed: {
        completedCount() {
            return (this.selected?.dtrs || []).filter(item => item.is_completed == 1).length;
        },
        holidayCount() {
            return (this.selected?.dtrs || []).filter(item => item.status == "Holiday").length;
        },
        travelCount() {
            return (this.selected?.dtrs || []).filter(item => item.status == "Official Travel").length;
        },
        businessCount() {
            return (this.selected?.dtrs || []).filter(item => item.status == "Official Business").length;
        },
        nonCount() {
            return (this.selected?.dtrs || []).filter(item => item.status == "Non-working Day").length;
        },
        absentCount() {
            return (this.selected?.dtrs || []).filter(item => item.status == "Absent").length;
        },
        totalWorkDays() {
            return (this.selected?.dtrs?.length || 0) - (this.holidayCount + this.travelCount + this.nonCount + this.absentCount + this.businessCount);
        }
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
            this.selected = data;
            this.form.user_id = data.value;
            this.keyword = null;
            document.getElementById("search-options").value = "";
            document.getElementById("search-options").focus();
        }, 
        submit(){
            this.form.post('/payroll',{
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