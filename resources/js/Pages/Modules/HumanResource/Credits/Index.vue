<template>
<Head title="Leave Credits"/>
    <PageHeader title="Leave Credits" pageTitle="List" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-file-list-line text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">Emmployee Leave Credits</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">A comprehensive list of campuses from various schools, providing location and institutional details</p>
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
                                <input type="text" v-model="filter.keyword" placeholder="Search Employee" class="form-control" style="width: 20%;">
                                <Multiselect v-if="filter.division" class="white" style="width: 11%;" :options="units" v-model="filter.unit" label="short" :searchable="true" placeholder="Select Unit" />
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.divisions" v-model="filter.division" label="others" :searchable="true" placeholder="Select Division" />
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.stations" v-model="filter.station" label="others" :searchable="true" placeholder="Select Stations" />
                                <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                                    <i class="bx bx-refresh search-icon"></i>
                                </span>
                                <b-button type="button" variant="primary" @click="openAdd">
                                    <i class="ri-add-circle-fill align-bottom me-1"></i> Add Credits
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
                                    <BLink @click="viewStatus(null,null)" class="nav-link py-3" :class="this.index === null ? 'text-primary active' : ''" data-bs-toggle="tab" role="tab" :aria-selected="this.index === null">
                                    <i class="ri-apps-2-line me-1 align-bottom"></i> All Employees
                                    </BLink>
                                </li>
                                <li class="nav-item" v-for="(list,index) in counts" v-bind:key="index">
                                    <BLink @click="viewStatus(index,list.value)" class="nav-link py-3" :class="(this.index == index) ? 'text-primary active' : ''" data-bs-toggle="tab" role="tab" :aria-selected="this.index == index">
                                        <i :class="list.icon" class="me-1 align-bottom"></i>
                                        {{ list.name }}
                                        <BBadge v-if="list.count > 0" :class="(this.index == index) ? 'bg-primary text-white' : 'text-dark bg-primary-subtle'" class="align-middle ms-1">{{list.count}}</BBadge>
                                    </BLink>
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
                                    <th>Name</th>
                                    <th style="width: 7%;" class="text-center">CTO</th>
                                    <th style="width: 7%;" class="text-center">VL</th>
                                    <th style="width: 7%;" class="text-center">SL</th>
                                    <th style="width: 7%;" class="text-center">FL</th>
                                    <th style="width: 7%;" class="text-center">SPL</th>
                                    <th style="width: 7%;" class="text-center">Maternity</th>
                                    <th style="width: 7%;" class="text-center">Paternity</th>
                                    <th style="width: 7%;" class="text-center">Study</th>
                                    <th style="width: 7%;" class="text-center">Wellness</th>
                                    <th style="width: 7%;" class="text-center">Status</th>
                                    <th style="width: 3%;"></th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-for="(list,index) in lists" v-bind:key="index" @click="selectRow(index)"
                                 :class="{ 'bg-info-subtle': selectedRow === index }">
                                    <td class="text-center"> 
                                        <div class="avatar-xs chat-user-img online">
                                            <img :src="list.avatar" alt="" class="avatar-xs rounded-circle">
                                            <!-- <span v-if="list.is_active" class="user-status text-success"></span> -->
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="fs-13 mb-0 fw-semibold text-primary text-uppercase">{{list.profile.name}}</h5>
                                        <p class="fs-12 text-muted mb-0">{{list.organization.position.name}}</p>
                                    </td>
                                    <td class="text-center">
                                        <div :class="naClass(list.leave_credits.cto)">{{ list.leave_credits.cto }}</div>
                                        <div v-if="list.leave_credits.cto_hours" class="text-muted fs-11">{{ list.leave_credits.cto_hours }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div :class="naClass(list.leave_credits.vacation)">{{ list.leave_credits.vacation }}</div>
                                        <div v-if="list.leave_credits.vacation_hours" class="text-muted fs-11">{{ list.leave_credits.vacation_hours }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div :class="naClass(list.leave_credits.sick)">{{ list.leave_credits.sick }}</div>
                                        <div v-if="list.leave_credits.sick_hours" class="text-muted fs-11">{{ list.leave_credits.sick_hours }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div :class="naClass(list.leave_credits.force)">{{ list.leave_credits.force }}</div>
                                        <div v-if="list.leave_credits.force_hours" class="text-muted fs-11">{{ list.leave_credits.force_hours }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div :class="naClass(list.leave_credits.special_privilege)">{{ list.leave_credits.special_privilege }}</div>
                                        <div v-if="list.leave_credits.special_privilege_hours" class="text-muted fs-11">{{ list.leave_credits.special_privilege_hours }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div :class="naClass(list.leave_credits.maternity)">{{ list.leave_credits.maternity }}</div>
                                        <div v-if="list.leave_credits.maternity_hours" class="text-muted fs-11">{{ list.leave_credits.maternity_hours }}</div>
                                    </td>
                                    <td class="text-center"><span :class="naClass(list.leave_credits.paternity)">{{ list.leave_credits.paternity }}</span></td>
                                    <td class="text-center"><span :class="naClass(list.leave_credits.study)">{{ list.leave_credits.study }}</span></td>
                                    <td class="text-center">
                                        <div :class="naClass(list.leave_credits.wellness)">{{ list.leave_credits.wellness }}</div>
                                        <div v-if="list.leave_credits.wellness_hours" class="text-muted fs-11">{{ list.leave_credits.wellness_hours }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span :class="'badge '+list.organization.status.color+' '+list.organization.status.bg">{{list.organization.status.name}}</span>
                                    </td>
                                    <td class="text-end">
                                        <!-- <b-button @click="openView(list)" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                            <i class="ri-eye-fill align-bottom"></i>
                                        </b-button> -->
                                        <Link :href="`/credits/${list.code}`">
                                            <b-button variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                                <i class="ri-eye-fill align-bottom"></i>
                                            </b-button>
                                        </Link>
                                    </td>
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
    <Add :leave-types="dropdowns.leave_types" @added="fetch()" ref="add"/>
    <View ref="view"/>
</template>
<script>
import _ from 'lodash';
import View from './Modals/View.vue';
import Add from './Modals/Add.vue';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect, Add, View },
    props: ['counts','dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: {
                keyword: null,
                type: null,
                division: null,
                station: null,
                unit: null
            },
            index: null,
            selectedRow: null,
            units: []
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal);
        },
        "filter.division"(newVal){
            if(!newVal){
                this.units = [];
                this.filter.unit = null;
                this.fetch();
            }else{
                this.fetchUnits(newVal);
                this.fetch();
            }
        },
        "filter.station"(newVal){
            this.fetch();
        },
        "filter.unit"(newVal){
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
            page_url = page_url || '/credits';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    type: this.filter.type,
                    division: this.filter.division,
                    unit: this.filter.unit,
                    station: this.filter.station,
                    count: 10, //Math.floor((window.innerHeight-350)/59)
                    option: 'lists'
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
        fetchUnits(code){
            axios.get('/search',{
                params: {
                    option: 'units',
                    code: code
                }
            })
            .then(response => {
                this.units = response.data;
            })
            .catch(err => console.log(err));
        },
        naClass(value){
            return value === 'N/A' ? 'text-muted fs-11' : '';
        },
        viewStatus(index,type){
            this.index = index;
            this.filter.type = type;
            this.fetch();
        },
        openAdd(){
            this.$refs.add.show();
        },
        openView(data,index){
            this.index = index;
            this.$refs.view.show(data);
        },
        updateUser(data){
            this.lists[this.index] = data;
        },
        selectRow(index) {
            this.selectedRow = index;
        }
    }
}
</script>