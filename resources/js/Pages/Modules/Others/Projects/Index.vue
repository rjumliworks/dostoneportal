<template>
<Head title="Budget"/>
    <PageHeader title="Budgeting Dashboard" pageTitle="List" />
    <b-row class="g-3">

        <div class="col-md-12">
            <b-card no-body class="bg-white-subtle border shadow-none">
                <b-card-body>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-lg-row flex-column">
                                <div class="flex-grow-1">
                                    <h4 class="fs-14 mb-0">{{monthName}} Summary View</h4>
                                    <p class="text-muted mb-0">Here's what's happening with the office for month of {{monthName}}.</p>
                                </div>
                                <div class="mt-3 mt-lg-0">
                                    <form action="javascript:void(0);">
                                        <div class="row g-3 mb-0 align-items-center">
                                            <div class="col-sm-auto">
                                                <div class="input-group">
                                                    <select style="width: 160px;" v-model="monthName" class="form-select" aria-label="Default select example">
                                                        <option :value="null">All Months</option>
                                                        <option :value="list" v-for="list in months" v-bind:key="list">{{list}}</option>
                                                    </select>
                                                    <select style="width: 100px;" v-model="filter.year" class="form-select" aria-label="Default select example">
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
                        </div>
                    </div>
                </b-card-body>
            </b-card>
        </div>

        <div class="col-md-3 mt-n1">
            <b-col lg="12">
                <b-card no-body class="bg-warning-subtle border shadow-none">
                    <b-card-body>
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-light text-primary rounded-circle fs-4">
                                    <i class="ri-loader-2-line align-middle`"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase text-truncate fw-semibold fs-10 text-muted mb-1">
                                Total Allocated Budget
                                </p>
                                <h4 class="mb-0">
                                    <span class="counter-value">{{  formatMoney(info.total) }}</span>
                                </h4>
                            </div>
                        </div>
                    </b-card-body>
                </b-card>
            </b-col>
            <b-col lg="12" class="mt-n2">
                <div class="card shadow-none border">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-secure-payment-fill text-primary fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Budget Allocation Summary</span></h5>
                                <p class="text-muted text-truncate-two-lines fs-11">Expense Class Summary</p>
                            </div>
                        </div>
                    </div>
                    <div class="card border-bottom shadow-none" no-body style="height: 330px;">
                        <ul class="list-group list-group-flush border-dashed mb-n4 p-3 mt-n2">
                            <li class="list-group-item px-0 mt-1" v-for="(list,index) in info.collection" v-bind:key="index">
                                <div class="d-flex mb-n1">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <span class="avatar-title bg-light p-1 rounded-circle">
                                            <i :class="list.icon+' '+list.color"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-0 fs-12">{{formatMoney(list.total)}}</h6>
                                        <p class="fs-11 mb-0 text-muted">{{ list.name }}</p>
                                    </div>
                                    <div class="flex-shrink-0 text-end" style="width: 60px;">
                                        <apexchart v-b-tooltip.hover :title="list.percent_allocated+'%'" class="apex-charts" height="40" dir="ltr" :series="[list.percent_allocated]" :options="{ ...chartOptions }"></apexchart>
                                        <!-- <h6 class="mt-2 fs-12">{{list.percent_allocated}}%</h6> -->
                                        <!-- <p class="text-success fs-12 mb-0">$19,405.12</p> -->
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </b-col>
        </div>

        <div class="col-md-6 mt-n1">
            <div class="row g-3">
                
                <b-col lg="4" v-for="(item, index) of info.statuses" :key="index">
                    <b-card no-body :class="item.color" class="border shadow-none">
                        <b-card-body>
                            <div class="d-flex align-items-center">
                                <!-- <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                                        <i :class="`${item.icon} align-middle`"></i>
                                    </span>
                                </div> -->
                                <div class="flex-grow-1">
                                    <p class="text-uppercase text-truncate fw-semibold fs-10 text-muted mb-1">
                                        {{ item.name }}
                                    </p>
                                    <h4 class="mb-0">
                                        <span class="counter-value">{{formatMoney(item.total)}}</span>
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 align-self-end">
                                    <apexchart class="apex-charts" height="4" width="100" type="area" dir="ltr" :series="item.series" :options="chartOptions"></apexchart>
                                </div>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>

                <b-col lg="4" class="mt-n2">
                    <div class="card bg-light-subtle shadow-none border">
                        
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <!-- <div class="flex-shrink-0 me-3 mt-1">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="ri-trophy-fill text-primary fs-20"></i>
                                        </span>
                                    </div>
                                </div> -->
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-13"><span class="text-body">Personnel Services</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-10">Salaries, wages, and employee benefits.</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <!-- <input type="date" v-model="date" placeholder="Search Request" class="form-control"> -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-bottom">
                            <apexchart
                                type="bar"
                                height="100"
                                :series="chartOptions1.series"
                                :options="chartOptions1"
                                />
                          
                        </div>

                    </div>
                </b-col>
                <b-col lg="4" class="mt-n2">
                    <div class="card bg-light-subtle shadow-none border">
                        
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <!-- <div class="flex-shrink-0 me-3 mt-1">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="ri-trophy-fill text-primary fs-20"></i>
                                        </span>
                                    </div>
                                </div> -->
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-13"><span class="text-body">MOOE</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-10"> Day-to-day operational expenses.</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <!-- <input type="date" v-model="date" placeholder="Search Request" class="form-control"> -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-bottom">
                            <apexchart
                                type="bar"
                                height="100"
                                :series="chartOptions1.series"
                                :options="chartOptions1"
                                />
                          
                        </div>

                    </div>
                </b-col>
                <b-col lg="4" class="mt-n2">
                    <div class="card bg-light-subtle shadow-none border">
                        
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <!-- <div class="flex-shrink-0 me-3 mt-1">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="ri-trophy-fill text-primary fs-20"></i>
                                        </span>
                                    </div>
                                </div> -->
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-13"><span class="text-body">Capital Outlay</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-10">Investments in assets and infrastructure.</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <!-- <input type="date" v-model="date" placeholder="Search Request" class="form-control"> -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-bottom">
                            <apexchart
                                type="bar"
                                height="100"
                                :series="chartOptions1.series"
                                :options="chartOptions1"
                                />
                          
                        </div>

                    </div>
                </b-col>

                <b-col lg="12" class="mt-n2">
                    <div class="card bg-light-subtle shadow-none border">
                        
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <!-- <div class="flex-shrink-0 me-3 mt-1">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="ri-trophy-fill text-primary fs-20"></i>
                                        </span>
                                    </div>
                                </div> -->
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-13"><span class="text-body">Programs</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-11">A summary of programs, activities, and milestones achieved within a specific reporting period in the office.</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <!-- <input type="date" v-model="date" placeholder="Search Request" class="form-control"> -->
                                </div>
                            </div>
                        </div>
                        <div class="car-body border-bottom shadow-none">
                            <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                                <b-col lg>
                                    <div class="input-group mb-1">
                                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                        <input type="text" v-model="filter.keyword" placeholder="Search Request" class="form-control" style="width: 40%;">
                                        
                                        
                                        <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                                            <i class="bx bx-refresh search-icon"></i>
                                        </span>
                                        <b-button type="button" variant="primary" @click="openCreate">
                                            <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                                        </b-button>
                                    </div>
                                </b-col>
                            </b-row>
                        </div>
                        <div class="card-body border-bottom">
                            <div class="table-responsive table-card" style="height: 291px; overflow: auto;">
                                <table class="table table-nowrap align-middle mb-0">
                                    <thead class="table-light thead-fixed">
                                        <tr class="fs-10">
                                            <th style="width: 7%;" class="text-center"></th>
                                            <th >Program</th>
                                            <!-- <th style="width: 9%;" class="text-center">Status</th> -->
                                            <th style="width: 20%;" class="text-center">Allocated</th>
                                            <th style="width: 20%;" class="text-center">Obligated</th>
                                            <th style="width: 20%;" class="text-center">Disbursed</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-12">
                                        <tr v-for="(list,index) in info.programs" v-bind:key="index">
                                            <td class="text-center"> 
                                                {{ index + 1 }}.
                                            </td>
                                            <td>
                                                <h5 class="fs-11 mb-0 fw-semibold text-primary">{{list.name}} </h5>
                                                <!-- <p class="fs-11 text-muted mb-0">-</p> -->
                                            </td>
                                            <!-- <td class="text-center">
                                                <span v-if="list.is_active" class="badge bg-success">Active</span>
                                                <span v-else class="badge bg-danger">Inactive</span>
                                            </td> -->
                                            
                                            <td class="text-center">
                                                {{ formatMoney(getTotalAllocation(list)) }}
                                            </td>
                                            <td class="text-center">
                                                {{ formatMoney(0) }}
                                            </td>
                                            <td class="text-center">
                                                {{ formatMoney(0) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </b-col>

                
            </div>
        </div>

        <div class="col-md-3 mt-n1">
            <b-col lg="12">
                <b-card no-body class="bg-success-subtle border shadow-none">
                    <b-card-body>
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs flex-shrink-0">
                                <span class="avatar-title bg-light text-primary rounded-circle fs-4">
                                    <i class="ri-hand-coin-fill align-middle`"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase text-truncate fw-semibold fs-10 text-muted mb-1">
                                Total Budget Utilization
                                </p>
                                <h4 class="mb-0">
                                    <span class="counter-value">0%</span>
                                </h4>
                            </div>
                        </div>
                    </b-card-body>
                </b-card>
            </b-col>
            <b-col lg="12" class="mt-n2">
                <div class="card shadow-none border">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3 mt-1">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-secure-payment-fill text-primary fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Budget Utilization Summary</span></h5>
                                <p class="text-muted text-truncate-two-lines fs-11">Expense Class Summary</p>
                            </div>
                        </div>
                    </div>
                    <div class="card border-bottom shadow-none" no-body style="height: 330px;">
                        <ul class="list-group list-group-flush border-dashed mb-1 p-3 mt-n2">
                            <li class="list-group-item px-0 mt-1" v-for="(list,index) in info.collection" v-bind:key="index">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <span class="avatar-title bg-light p-1 rounded-circle">
                                            <i :class="icons[index]+' '+colors[index]+' fs-18'"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-0 fs-12">{{formatMoney(0)}}</h6>
                                        <p class="fs-11 mb-0 text-muted">{{ list.name }}</p>
                                    </div>
                                    <div class="flex-shrink-0 text-end" style="width: 60px;">
                                        <!-- <apexchart v-b-tooltip.hover :title="list.percent_allocated+'%'" class="apex-charts" height="40" dir="ltr" :series="[list.percent_allocated]" :options="{ ...chartOptions }"></apexchart> -->
                                        <h6 class="mt-2 fs-12">0%</h6>
                                        <!-- <p class="text-success fs-12 mb-0">$19,405.12</p> -->
                                    </div>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>
            </b-col>
        </div>

    </b-row>
</template>
<script>
import _ from 'lodash';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect },
    props: ['years','info'],
    data(){
        return {
            icons: ['ri-account-circle-fill','ri-todo-fill','ri-home-6-fill'],
            colors: ['text-primary','text-danger','text-success'],
            filter: {
                keyword: null,
                month: new Date().toLocaleString('default', { month: 'long' }),
                year: new Date().getFullYear()
            },
            month: new Date().getMonth() + 1,
            monthName: new Date().toLocaleString('default', { month: 'long' }),
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            chartOptions: {
                chart: {
                type: "radialBar",
                    sparkline: {
                        enabled: true,
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: "30%",
                        },
                        track: {
                            margin: 1,
                        },
                        dataLabels: {
                            show: false,
                        },
                    },
                },
                colors: ["#099885"],
            },
            chartOptions1: {
                chart: {
                    type: "bar",
                    sparkline: {
                        enabled: true // removes axes for clean UI
                    }
                },
                series: [
                    {
                        name: "Budget",
                        data: [70, 45, 30] // Allocated, Obligated, Disbursed
                    }
                ],
                xaxis: {
                    categories: ["Allocated", "Obligated", "Disbursed"],
                    labels: { show: false },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },

                yaxis: {
                    show: false
                },

                plotOptions: {
                    bar: {
                        columnWidth: "100%",
                        borderRadius: 0,
                        distributed: true // 👈 each bar gets its own color
                    }
                },

                dataLabels: {
                    enabled: false
                },

                grid: {
                    show: false
                },

                colors: ["#099885", "#FEB019", "#FF4560"]
            }
        }
    },
    methods: {
        getTotalAllocation(program) {
            return (program.projects || []).reduce((sum, project) => {
                return sum + parseFloat(project.allocations_sum_amount || 0);
            }, 0);
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>