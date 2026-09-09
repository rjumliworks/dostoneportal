<template>
<b-modal v-model="showModal" style="--vz-modal-width: 1200px;" header-class="p-3 bg-light" title="Add Employee" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop @hidden="hide()">
        <form class="customform">
            <BRow class="g-2 align-items-center mb-2">
                <BCol lg>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-search-line search-icon"></i></span>
                        <input type="text" class="form-control" placeholder="Search Employee by name" autocomplete="off" v-model="keyword" @input="checkSearchStr(keyword)"/>
                    </div>
                </BCol>
                <BCol lg="auto">
                    <b-button variant="success" :disabled="!completeUsers.length || bulkForm.processing" @click="addAllComplete">
                        <i class="ri-user-add-fill align-bottom me-1"></i> Add All Complete DTR ({{ completeUsers.length }})
                    </b-button>
                </BCol>
            </BRow>
            <hr class="text-muted mt-0"/>
            <div v-if="loading" class="text-center text-muted py-5">
                <b-spinner small class="me-1"/> Loading employees...
            </div>
            <BRow class="g-3" v-else>
                <BCol lg="5">
                    <div class="table-responsive" style="height: calc(100vh - 480px); overflow: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light thead-fixed fs-11">
                                <tr>
                                    <th style="width: 12%;"></th>
                                    <th>Name</th>
                                    <th class="text-center" style="width: 25%;">DTR</th>
                                </tr>
                            </thead>
                            <tbody class="fs-12">
                                <tr v-for="(list,index) in names" :key="index" @click="chooseUser(list)" style="cursor:pointer;"
                                    :class="{ 'bg-info-subtle': selected && selected.value === list.value }">
                                    <td class="text-center">
                                        <img :src="list.avatar" class="avatar-xs rounded-circle" alt="user-pic"/>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 text-uppercase">{{ list.name }}</h6>
                                        <span class="fs-11 text-muted">{{ list.position }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span v-if="list.already_in_payroll" class="badge bg-secondary-subtle text-secondary">Already Added</span>
                                        <span v-else :class="['badge', list.is_complete ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning']">
                                            {{ list.completed_count }} / {{ list.total_work_days }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="!names.length">
                                    <td colspan="3" class="text-center text-muted py-4">No eligible employees found for this date range.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </BCol>
                <BCol lg="7">
                    <template v-if="selected">
                        <BRow class="align-items-center g-1 mb-2">
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
                        <hr class="text-muted"/>
                        <div v-if="selected.already_in_payroll" class="alert alert-danger alert-dismissible alert-label-icon label-arrow" role="alert">
                            <i class="ri-error-warning-line label-icon"></i>
                            <strong>Alert</strong> – This employee is already in the payroll.
                        </div>
                        <template v-else>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-2">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-check-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Completed DTR :</p>
                                                <h5 class="mb-0 fs-12">{{selected.completed_count}} / {{ selected.total_work_days }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-todo-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Holiday :</p>
                                                <h5 class="mb-0 fs-12">{{selected.holiday_count}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-map-pin-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Official Travel :</p>
                                                <h5 class="mb-0 fs-12">{{selected.travel_count}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-sm-2">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-map-pin-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Official Leave :</p>
                                                <h5 class="mb-0 fs-12">{{selected.leave_count}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-road-map-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Official Business :</p>
                                                <h5 class="mb-0 fs-12">{{selected.business_count}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="p-1 border border-dashed rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-close-circle-fill"></i></div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted mb-0 fs-12">Absent :</p>
                                                <h5 class="mb-0 fs-12">{{selected.absent_count}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="!selected.is_complete"
                                class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show material-shadow mt-2"
                                role="alert">
                                <i class="ri-alert-line label-icon"></i>
                                <strong>Warning</strong> - Employee is not eligible to be added to the payroll because their DTR is incomplete.
                            </div>
                            <div class="table-responsive" style="height: calc(100vh - 665px); overflow: auto;">
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
                                            'bg-info-subtle': list.status === 'Official Leave',
                                            'bg-danger-subtle': list.status === 'Absent'
                                        }">
                                            <td class="text-center">
                                                <h5 class="fs-11 mb-0 fw-semibold text-primary">{{list.date}}</h5>
                                                <p class="fs-10 text-muted mb-0">({{list.date_day}})</p>
                                            </td>
                                            <template v-if="list.status === 'Non-working Day'">
                                                <td class="text-center" colspan="5">{{list.title}}</td>
                                            </template>
                                             <template v-else-if="list.status === 'Holiday'">
                                                <td class="text-center text-dark fw-semibold" colspan="5">{{list.title}}</td>
                                            </template>
                                            <template v-else-if="list.status === 'Official Travel'">
                                                <td class="text-center" colspan="5">Official Travel : {{list.title}}</td>
                                            </template>
                                             <template v-else-if="list.status === 'Official Leave'">
                                                <td class="text-center text-info fw-semibold" colspan="5">{{list.title}}</td>
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
                        </template>
                    </template>
                    <div v-else class="d-flex align-items-center justify-content-center h-100 text-muted">
                        Select an employee to view their DTR details.
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button v-if="selected && !selected.already_in_payroll && selected.is_complete" @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
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
                user_ids: [],
                option: 'payroll'
            }),
            bulkForm: useForm({
                id: this.id,
                user_ids: [],
                option: 'payroll'
            }),
            selected: null,
            names: [],
            keyword: null,
            loading: false,
            showModal: false
        }
    },
    computed: {
        completeUsers() {
            return this.names.filter(list => !list.already_in_payroll && list.is_complete);
        }
    },
    methods: {
        show(){
            this.showModal = true;
            this.keyword = null;
            this.selected = null;
            this.search();
        },
        checkSearchStr: _.debounce(function (string) {
            this.keyword = string;
            this.search();
        }, 500),
        search(){
            this.loading = true;
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
                    if(this.selected){
                        const updated = this.names.find(list => list.value === this.selected.value);
                        this.selected = updated || null;
                    }
                }
            })
            .catch(err => console.log(err))
            .finally(() => {
                this.loading = false;
            });
        },
        chooseUser(data){
            this.selected = data;
        },
        submit(){
            this.form.user_ids = [this.selected.value];
            this.form.post('/payroll',{
                preserveScroll: true,
                onSuccess: () => {
                    this.selected = null;
                    this.search();
                },
            });
        },
        addAllComplete(){
            if(!this.completeUsers.length) return;
            this.bulkForm.user_ids = this.completeUsers.map(list => list.value);
            this.bulkForm.post('/payroll',{
                preserveScroll: true,
                onSuccess: () => {
                    this.selected = null;
                    this.search();
                },
            });
        },
        hide(){
            this.form.reset();
            this.bulkForm.reset();
            this.selected = null;
            this.names = [];
            this.keyword = null;
            this.showModal = false;
        }
    }
}
</script>
