<template>
    <Head title="DTR"/>
    <PageHeader title="Daily Time Record" pageTitle="List" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-alarm-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">{{ month }} Records</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">A detailed list of submitted travel order requests including destination, purpose, schedule, and status.</p>
                        </div>
                        <div class="flex-shrink-0" style="width: 15%;">
                        </div>
                    </div>
                </div>
                <div class="car-body bg-white border-bottom shadow-none">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg>
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <input type="text" placeholder="Search" class="form-control" style="width: 65%;">
                                <Multiselect class="white" style="width: 13%;" :options="months" v-model="month" label="short" :searchable="true" placeholder="Select Month" />
                                <input type="text" v-model="year" class="form-control" style="width: 5%;">
                                <span @click="print()" class="input-group-text" v-b-tooltip.hover title="Print" style="cursor: pointer;"> 
                                    <i class="ri-printer-line search-icon"></i>
                                </span>
                                <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                                    <i class="bx bx-refresh search-icon"></i>
                                </span>
                                <b-button type="button" variant="primary" @click="openCreate">
                                    <i class="ri-search-line search-icon"></i>
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="card bg-white border-bottom shadow-none p-4" no-body style="margin-bottom: 0px;">
                     <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-1 border border-dashed rounded">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-check-fill"></i></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0 fs-12">Completed DTR :</p>
                                        <h5 class="mb-0 fs-12">{{completedCount - (travelCount+businessCount)}} / {{ totalWorkDays }}</h5>
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
                                        <h5 class="mb-0 fs-12">{{holidayCount}}</h5>
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
                                        <h5 class="mb-0 fs-12">{{travelCount}}</h5>
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
                                        <h5 class="mb-0 fs-12">{{businessCount}}</h5>
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
                                        <h5 class="mb-0 fs-12">{{absentCount}}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive" style="height: calc(100vh - 500px); overflow: auto;">
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
                                <tr v-for="(list,index) in selected.dtrs" v-bind:key="index" :class="rowClass(list)">
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
                                    <template v-else-if="list.status === '-'">
                                        <td class="text-center" colspan="5">-</td>
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
                </div>
             </div>   
        </div>
    </BRow>
</template>
<script>
import _ from 'lodash';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Multiselect },
    data(){
        return {
            selected: {},
            month: new Date().toLocaleString('default', { month: 'long' }),
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            icons: ['ri-flight-takeoff-fill','ri-car-fill','ri-calendar-2-fill'],
            index: null,
            year: new Date().getFullYear(),
        }
    },
    watch: {
        "month"(newVal){
            this.fetch();
        },
        "year"(newVal){
            this.fetch();
        }
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
        futureCount() {
            return (this.selected?.dtrs || []).filter(item => item.status == "-").length;
        },
        totalWorkDays() {
            return (this.selected?.dtrs?.length || 0) - (this.holidayCount + this.travelCount + this.nonCount + this.absentCount + this.businessCount + this.futureCount);
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/dtr';
            axios.get(page_url,{
                params : {
                    month: this.month,
                    year: this.year,
                    count: 10, 
                    option: 'dtr'
                }
            })
            .then(response => {
                if(response){
                    this.selected = response.data;        
                }
            })
            .catch(err => console.log(err));
        },
        rowClass(list) {
            // Priority 1 — Official Travel or Business
            if (list.status === 'Official Travel' || list.status === 'Official Business') {
                return 'bg-secondary-subtle';
            }

            // Priority 2 — Holidays / Non-working Days
            if (list.status === 'Holiday' || list.status === 'Non-working Day') {
                return 'bg-dark-subtle';
            }

            // Priority 3 — Absent
            if (list.status === 'Absent') {
                return 'bg-danger-subtle';
            }

            if (list.status === '-') {
                return '';
            }

            // Priority 4 — Completed
            if (list.is_completed) {
                return 'bg-success-subtle';
            }

            // Default — Not completed
            return 'bg-warning-subtle';
        },
        print(){
            window.open('/dtr?option=print&id='+this.$page.props.user.data.id+'&month='+this.month+'&year='+this.year);
        }, 
    }
}
</script>