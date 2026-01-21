<template>
<Head title="Payroll Contractual"/>
<PageHeader title="Payroll Management" pageTitle="List" />
<BRow>
    <div class="col-md-12">
        <div class="card bg-light-subtle shadow-none border">
            <div class="card-header bg-light-subtle">
                <div class="d-flex mb-n3">
                    <div class="flex-shrink-0 me-3">
                        <div style="height:2.5rem;width:2.5rem;">
                            <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                <i class="ri-hand-coin-fill text-primary fs-24"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-0 fs-14"><span class="text-body">Contractual Employees Payroll</span></h5>
                        <p class="text-muted text-truncate-two-lines fs-12">Manage and review payroll records for contractual employees, including salary details, deductions, and pay summaries.</p>
                    </div>
                    <div class="flex-shrink-0" style="width: 45%;">
                        
                    </div>
                </div>
            </div>
            <div class="car-body bg-white border-bottom shadow-none">
                <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                    <b-col lg>
                        <div class="input-group mb-1">
                            <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                            <input type="text" v-model="filter.keyword" placeholder="Search Payroll" class="form-control" style="width: 65%;">
                            <input type="date" v-model="filter.date" class="form-control">
                            <b-button type="button" variant="primary" @click="openCreate()">
                                <i class="ri-add-circle-fill search-icon"></i> Create
                            </b-button>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <div class="card bg-white border-bottom shadow-none" no-body>
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <ul class="nav nav-tabs nav-tabs-custom nav-primary fs-12" role="tablist">
                            <li class="nav-item">
                                <BLink @click="viewStatus(null,null)" class="nav-link py-3 active" data-bs-toggle="tab" role="tab" aria-selected="true">
                                <i class="ri-apps-2-line me-1 align-bottom"></i> All Payroll
                                </BLink>
                            </li>
                            <li class="nav-item" v-for="(list,index) in counts" v-bind:key="index">
                              
                            </li>
                        </ul>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body bg-white rounded-bottom">
                <div class="table-responsive table-card" style="margin-top: -39px; height: calc(100vh - 465px); overflow: auto;">
                    <table class="table align-middle table-striped table-centered mb-0">
                        <thead class="table-light thead-fixed">
                            <tr class="fs-11">
                                <th style="width: 3%;"></th>
                                <th>Batch Name</th>
                                <th style="width: 8%;" class="text-center">Employee</th>
                                <th style="width: 13%;" class="text-center">Compensation</th>
                                <th style="width: 13%;" class="text-center">Deduction</th>
                                <th style="width: 13%;" class="text-center">Net Amount Due</th>
                                <th style="width: 10%;" class="text-center">Status</th>
                                <th style="width: 6%;"></th>
                            </tr>
                        </thead>
                        <tbody class="table-white fs-12" v-if="lists.length > 0">
                            <tr v-for="(list,index) in lists" v-bind:key="index" >
                                <td class="text-center">{{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                                <td>
                                    <h5 class="fs-12 text-primary text-uppercase fw-semibold mb-0">{{ list.cycle.month }} {{ list.cycle.year }} - {{ list.type }}  QUINCENA</h5>
                                    <p class="fs-12 text-muted mb-0">Batch {{ list.batch }} ({{ list.start }} to {{ list.end }})</p>
                                </td>
                                <td class="text-center">{{list.count}}</td>
                                <td class="text-center">{{formatMoney(list.compensation)}}</td>
                                <td class="text-center">{{formatMoney(list.deduction)}}</td>
                                <td class="text-center">{{formatMoney(list.total)}}</td>
                                <td class="text-center">
                                    <span :class="'badge '+list.status.bg">{{list.status.name}}</span>
                                </td>
                                <td class="text-end">
                                    <Link :href="`/payroll/contractual/${list.code}`">
                                        <b-button variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                            <i class="ri-eye-fill align-bottom"></i>
                                        </b-button>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                        <tbody class="table-white fs-12" v-else>
                            <tr>
                                <td colspan="8" class="text-muted text-center">No employees have been selected or added for this payroll cutoff.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <Pagination class="ms-2 me-2 mt-n1" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
        </div>
    </div>
</BRow>
<Create :payrolls="dropdowns.payrolls" @success="fetch()" ref="create"/>
</template>
<script>
import _ from 'lodash';
import Create from './Modals/Create.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Create },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: {
                keyword: null,
                date: null
            },
            index: null
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal);
        },
        "filter.date"(newVal){
            this.fetch();
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
            page_url = page_url || '/payroll';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    date: this.filter.date,
                    count: 10,
                    option: 'contractual'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
        openView(data){
            this.$refs.view.show(data);
        },
        openCreate(){
            this.$refs.create.show();
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>