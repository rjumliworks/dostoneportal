<template>
    <Head title="Dashboard"/>
    <PageHeader title="Dashboard" pageTitle="List" />
    
    <BRow class="g-3" style="height: calc(100vh - 300px); overflow: auto;">
        <BCol xl="4">
            <Employee :employee="employee"/>
        </BCol>
        <BCol xl="8">
            
        </BCol>
    </BRow>
    <!-- <BRow>
        <div class="col-md-12 mt-0">
            <div class="row">
                <b-col lg="3" md="4" v-for="(item, index) of counts" :key="index">
                    <b-card no-body :class="item.color" >
                        <b-card-body>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                        <i :class="`bx ${item.icon} align-middle`"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
                                        {{ item.name }}
                                    </p>
                                    <h4 class="mb-0">
                                        <span class="counter-value">{{ item.total }}</span>
                                        
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 align-self-end">
                                    <apexchart class="apex-charts" height="40" width="100" type="area" dir="ltr" :series="item.series" :options="chartOptions"></apexchart>
                                </div>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
            </div>
        </div>
        <BCol lg="3">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-team-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">Overall Employees</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Summary of the employee status</p>
                        </div>
                    </div>
                </div>
                <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 400px); overflow-x: hidden; overflow-y: auto;">
                    <div class="row align-items-center p-3 mb-n3">
                        <div class="col-12">
                            <h6 class="text-primary text-uppercase fw-semibold text-truncate fs-12 mb-3">Total Employees</h6>
                            <h4 class="fs- mb-0">{{employee.all}}</h4>
                            <div class="progress mb-2 progress-sm mt-2">
                                <div class="progress-bar bg-danger" role="progressbar" :style="'width: '+employee.absent*100+'%'" :aria-valuenow="employee.all*100" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-success" role="progressbar" :style="'width: '+employee.present*100+'%'" :aria-valuenow="employee.all*100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="text-muted fs-12 d-block text-truncate"> <b>{{ employee.absent }}</b> out of <b>{{ employee.all }}</b> employees are absent</span>
                        </div>
                    </div>
                    <hr class="text-muted"/>
                        <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Employee Type Count</p>
                    <hr class="text-muted mb-2"/>
                    <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                        <li class="list-group-item px-0" v-for="(list,index) in employee.statuses" v-bind:key="index" style="cursor: pointer;">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs">
                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                        <i :class="icons[index]+ ' text-primary fs-16'"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 fs-12">{{list.name}}</h6>
                                    <p class="fs-11 mb-0 text-muted">{{ desriptions[index] }}</p>
                                </div>
                                <div class="flex-shrink-0 text-end">
                                    <h6 class="mt-2 fs-11">{{list.count}} <span class="fs-10 text-muted"> ({{ list.percentage }})</span></h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </BCol>
        <BCol lg="9">
            <div class="col-md-12 mb-n4">
                <div class="card shadow-none border">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3">
                                <div style="height:2.5rem;width:2.5rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-14"><span class="text-body">fds</span></h5>
                                <p class="text-muted text-truncate-two-lines fs-12">asd</p>
                            </div>
                            <div class="flex-shrink-0">
                            <form action="javascript:void(0);">
                            <div class="row g-3 mb-0 align-items-center">
                                <div class="col-sm-auto">
                                    <div class="input-group">
                                        <select v-model="division" class="form-select" aria-label="Default select example" :disabled="loading">
                                            <option :value="null">All Employees</option>
                                            <option :value="list.value" v-for="list in divisions" :key="list.value">{{ list.name }}</option>
                                        </select>
                                        <div class="input-group-text bg-primary border-primary text-white">
                                            <i class="ri-flask-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </BCol>
    </BRow> -->
</template>
<script>
import Employee from './Components/Employee.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['employee','counts','divisions'],
    components: { PageHeader, Employee },
    data(){
        return {
            division: null,
            desriptions: [
                'Rating for permanent employees',
                'Rating for contract-based staffs',
                'Rating for job order personnels',
                'Rating for agency-hired personnel'
            ],
            icons: [
                'ri-account-circle-fill','ri-emotion-fill','ri-user-3-fill','ri-building-fill'
            ],
             chartOptions: {
                chart: { type: 'area', height: 40, sparkline: {enabled: true}},
                stroke: { curve: 'smooth', width: 2, },
                dataLabels: {  enabled: false },
                colors: ['#03114B'],
                fill: { type: 'gradient',gradient: {shadeIntensity: 1,inverseColors: false,opacityFrom: 0.45, opacityTo: 0.05,stops: [25, 100, 100, 100] }, },
                tooltip: { fixed: { enabled: false }, x: { show: true },marker: { show: false } }
            },
        }
    }
}
</script>