<template>
    <Head title="Asset Dashboard"/>
    <PageHeader title="Asset Management" pageTitle="Dashboard" />
    <b-row class="g-2 mt-n2">
        <div class="col-md-12">
            <b-card no-body class="bg-white-subtle border shadow-none">
                <b-card-body>
                    <div class="d-flex flex-lg-row flex-column align-items-lg-center">
                        <div class="flex-grow-1">
                            <h4 class="fs-14 mb-0">Asset Summary View</h4>
                            <p class="text-muted mb-0">An overview of equipments, vehicles, and buildings under asset management.</p>
                        </div>
                        <div class="mt-3 mt-lg-0">
                           <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-sm-auto">
                                        <div class="input-group">
                                            <select v-model="month" style="width: 170px;"  class="form-select" aria-label="Default select example">
                                                <option :value="null">All Months</option>
                                                <option :value="list" v-for="list in months" v-bind:key="list">{{list}}</option>
                                            </select>
                                            <select v-model="year" style="width: 150px;"  class="form-select" aria-label="Default select example">
                                                <option :value="null">All Years</option>
                                                <option :value="list" v-for="list in years" v-bind:key="list">{{list}}</option>
                                            </select>
                                            <div class="input-group-text bg-primary border-primary text-white">
                                                <i class="ri-calendar-2-line"></i> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </b-card-body>
            </b-card>
        </div>
    </b-row>
    <simplebar data-simplebar style="margin-top: -5px; height: calc(100vh - 300px); overflow-x: hidden; overflow-y: auto;">
        <BRow class="g-3">
            <b-col lg="9">
                <BRow class="g-3">
                    <div class="col-md-4">
                        <div class="card shadow-none border">
                            <div class="card-header bg-light-subtle">
                                <div class="d-flex mb-n3">
                                    <div class="flex-shrink-0 me-3">
                                        <div style="height:2rem;width:2rem;">
                                            <span class="avatar-title bg-primary text-primary rounded-circle fs-4">
                                                <i class="ri-mac-fill text-light align-middle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 fs-14"><span class="text-body">Equipments</span></h5>
                                        <p class="text-muted text-truncate-two-lines fs-12">Total registered equipment</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="mb-0 mt-2 text-primary text-center fw-semibold fs-16">{{ counts.equipments }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-light-subtle border-bottom">
                                <p class="mb-0 text-primary text-center fw-semibold fs-16">{{ counts.equipments }}</p>
                            </div>
                            <div class="card bg-white shadow-none mb-0" no-body style="height: 190px; overflow: auto;">
                                <ul class="list-group list-group-flush border-dashed mb-0 mt-0 p-2" v-if="equipment_statuses.length">
                                    <li class="list-group-item" v-for="(list,index) in equipment_statuses" v-bind:key="index">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 avatar-xs">
                                                <span class="avatar-title rounded-circle" :class="'bg-'+statusColor(list.bg)+'-subtle text-'+statusColor(list.bg)">
                                                    <i class="ri-checkbox-circle-fill"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0 fs-13">{{ list.name }}</h6>
                                                <p class="text-muted mb-0 fs-11">Equipment marked {{ list.name.toLowerCase() }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="mb-0 fs-14">{{ list.count }}</h5>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <p v-else class="text-muted text-center mt-3">No data available.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-none border">
                            <div class="card-header bg-light-subtle">
                                <div class="d-flex mb-n3">
                                    <div class="flex-shrink-0 me-3">
                                        <div style="height:2rem;width:2rem;">
                                            <span class="avatar-title bg-primary text-primary rounded-circle fs-4">
                                                <i class="ri-car-fill text-light align-middle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 fs-14"><span class="text-body">Vehicles</span></h5>
                                        <p class="text-muted text-truncate-two-lines fs-12">Total registered vehicles</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="mb-0 mt-2 text-primary text-center fw-semibold fs-16">{{ counts.vehicles }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-light-subtle border-bottom bg-white">
                                <p class="mb-0 text-primary fs-16 fw-semibold text-center">{{ counts.vehicles }}</p>
                            </div>
                            <div class="card bg-white shadow-none mb-0" no-body style="height: 190px; overflow: auto;">
                                <ul class="list-group list-group-flush border-dashed mb-0 mt-0 p-2" v-if="vehicle_statuses.length">
                                    <li class="list-group-item" v-for="(list,index) in vehicle_statuses" v-bind:key="index">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 avatar-xs">
                                                <span class="avatar-title rounded-circle" :class="'bg-'+statusColor(list.bg)+'-subtle text-'+statusColor(list.bg)">
                                                    <i class="ri-checkbox-circle-fill"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0 fs-13">{{ list.name }}</h6>
                                                <p class="text-muted mb-0 fs-11">Vehicles marked {{ list.name.toLowerCase() }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="mb-0 fs-14">{{ list.count }}</h5>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <p v-else class="text-muted text-center mt-3">No data available.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-none border">
                            <div class="card-header bg-light-subtle">
                                <div class="d-flex mb-n3">
                                    <div class="flex-shrink-0 me-3">
                                        <div style="height:2rem;width:2rem;">
                                            <span class="avatar-title bg-primary text-primary rounded-circle fs-4">
                                                <i class="ri-government-fill text-light align-middle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 fs-14"><span class="text-body">Buildings</span></h5>
                                        <p class="text-muted text-truncate-two-lines fs-12">Total registered buildings</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="mb-0 mt-2 text-primary text-center fw-semibold fs-16">{{ counts.buildings }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-light-subtle border-bottom bg-white">
                                <p class="mb-0 text-primary fs-16 fw-semibold text-center">{{ counts.buildings }}</p>
                            </div>
                            <div class="card bg-white shadow-none mb-0" no-body style="height: 190px; overflow: auto;">
                                <p class="text-muted text-center mt-3">Buildings are not tracked by status.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-n2">
                        <EquipmentSchedule :lists="equipment_schedule" :dropdowns="dropdowns"/>
                    </div>
                </BRow>
            </b-col>
            <b-col lg="3">
                <BRow class="g-2 mb-2">
                    <b-col lg="12">
                        <b-card no-body class="bg-danger-subtle border shadow-none" style="height: 94px;">
                            <b-card-body class="d-flex align-items-center h-100">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-light text-danger rounded-circle fs-2">
                                        <i class="ri-error-warning-fill"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Overdue</p>
                                    <h2 class="fs-20 mb-0 text-danger"><span class="counter-value">{{ overdueCount }}</span></h2>
                                    <p class="fs-11 text-muted mb-0">Past their scheduled month</p>
                                </div>
                            </b-card-body>
                        </b-card>
                    </b-col>
                    <b-col lg="12" class="mt-n2">
                        <b-card no-body class="bg-warning-subtle border shadow-none" style="height: 94px;">
                            <b-card-body class="d-flex align-items-center h-100">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-light text-warning rounded-circle fs-2">
                                        <i class="ri-tools-fill"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">For Maintenance</p>
                                    <h2 class="fs-20 mb-0 text-warning"><span class="counter-value">{{ forMaintenanceCount }}</span></h2>
                                    <p class="fs-11 text-muted mb-0">Scheduled, not yet due</p>
                                </div>
                            </b-card-body>
                        </b-card>
                    </b-col>
                    <b-col lg="12" class="mt-n2">
                        <b-card no-body class="bg-success-subtle border shadow-none" style="height: 94px;">
                            <b-card-body class="d-flex align-items-center h-100">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-light text-success rounded-circle fs-2">
                                        <i class="ri-checkbox-circle-fill"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-0">Completed</p>
                                    <h2 class="fs-20 mb-0 text-success"><span class="counter-value">{{ completedCount }}</span></h2>
                                    <p class="fs-11 text-muted mb-0">Fully maintained this year</p>
                                </div>
                            </b-card-body>
                        </b-card>
                    </b-col>
                </BRow>
                <PendingRequests :lists="pending_requests"/>
                <UpcomingMaintenance :lists="upcoming_maintenance"/>
            </b-col>
        </BRow>
    </simplebar>
</template>
<script>
import simplebar from "simplebar-vue";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import UpcomingMaintenance from './Components/UpcomingMaintenance.vue';
import RecentRecords from './Components/RecentRecords.vue';
import PendingRequests from './Components/PendingRequests.vue';
import EquipmentSchedule from './Components/EquipmentSchedule.vue';
export default {
    components: { PageHeader, UpcomingMaintenance, RecentRecords, PendingRequests, EquipmentSchedule, simplebar },
    props: ['years','counts','equipment_statuses','vehicle_statuses','upcoming_maintenance','recent_records','pending_requests','equipment_schedule','dropdowns'],
    data(){
        return {
            month: new Date().toLocaleString('default', { month: 'long' }),
            year: new Date().getFullYear(),
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
        }
    },
    computed: {
        overdueCount(){
            const currentMonth = new Date().getMonth() + 1;
            return this.equipment_schedule.filter(equipment => {
                const completed = equipment.completed || [];
                return (equipment.maintenance_schedule || []).some(month => month < currentMonth && !completed.includes(month));
            }).length;
        },
        forMaintenanceCount(){
            const currentMonth = new Date().getMonth() + 1;
            return this.equipment_schedule.filter(equipment => {
                const completed = equipment.completed || [];
                return (equipment.maintenance_schedule || []).some(month => month >= currentMonth && !completed.includes(month));
            }).length;
        },
        completedCount(){
            return this.equipment_schedule.filter(equipment => {
                const scheduled = equipment.maintenance_schedule || [];
                const completed = equipment.completed || [];
                return scheduled.length > 0 && scheduled.every(month => completed.includes(month));
            }).length;
        }
    },
    methods: {
        print(){
            window.print();
        },
        statusColor(bg){
            return (bg || '').replace('bg-','') || 'secondary';
        }
    }
}
</script>
