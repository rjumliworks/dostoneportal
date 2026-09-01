<template>
    <Head title="Request Monitoring"/>
    <PageHeader title="Request Monitoring" pageTitle="List" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-pin-distance-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">All Requests</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">A monitoring view of all requests submitted by employees, across all divisions.</p>
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
                                <input type="text" v-model="filter.keyword" placeholder="Search by employee last name" class="form-control" style="width: 40%;">
                                <Multiselect class="white" style="width: 15%;" :options="statuses" v-model="filter.status" label="name" :searchable="true" placeholder="Select Status" />
                                <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;">
                                    <i class="bx bx-refresh search-icon"></i>
                                </span>
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
                                    <i class="ri-apps-2-line me-1 align-bottom"></i> All Requests
                                    </BLink>
                                </li>
                                <li class="nav-item" v-for="(list,index) in dropdowns.requests" v-bind:key="index">
                                    <BLink @click="viewStatus(index,list.value)" class="nav-link py-3" :class="(this.index == index) ? 'text-secondary active' : ''" data-bs-toggle="tab" role="tab" aria-selected="false">
                                        <i :class="icons[index]" class="me-1 align-bottom"></i>
                                        {{ list.name }} <BBadge v-if="counts[index] > 0" class="align-middle ms-1 bg-primary-subtle text-primary">{{counts[index]}}</BBadge>
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
                                    <th style="width: 3%;" class="text-center">#</th>
                                    <th>Code</th>
                                    <th v-if="!filter.type" style="width: 14%;" class="text-center">Type</th>
                                    <th v-else style="width: 14%;" class="text-center">{{ filter.type == 156 ? 'Mode' : (filter.type == 157 ? 'Vehicle' : 'Type') }}</th>
                                    <th style="width: 15%;" class="text-center">Requested By</th>
                                    <th style="width: 12%;" class="text-center">Dates</th>
                                    <th style="width: 12%;" class="text-center">Date Filed</th>
                                    <th style="width: 10%;" class="text-center">Status</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-if="lists.length == 0" style="height: calc(100vh - 506px);">
                                    <td colspan="8" class="text-center">
                                        <div class="d-flex flex-column align-items-center py-4">
                                            <div class="avatar-lg mb-3">
                                                <div class="avatar-title bg-light rounded-circle text-muted">
                                                    <i class="ri-file-list-3-line fs-24"></i>
                                                </div>
                                            </div>
                                            <h5 class="mb-1">No requests found</h5>
                                            <p class="text-muted mb-0">Try adjusting your search or filter to find what you're looking for.</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(list,index) in lists" v-bind:key="index" >
                                    <td class="text-center">{{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                                    <td>
                                        <h5 class="fs-13 mb-0 fw-semibold text-primary">{{list.code }}</h5>
                                    </td>
                                    <td class="text-center" v-if="!filter.type">
                                        <span v-if="list.type == 'Vehicle Reservation'" class="badge bg-secondary-subtle text-secondary">{{list.type}}</span>
                                        <span v-else-if="list.type == 'Travel Order'" class="badge bg-success-subtle text-success">{{list.type}}</span>
                                        <span v-else-if="list.type == 'Leave Form'" class="badge bg-danger-subtle text-danger">{{list.type}}</span>
                                        <span v-else-if="list.type == 'Render Overtime Service'" class="badge bg-info-subtle text-info">{{list.type}}</span>
                                        <span v-else-if="list.type == 'Training'" class="badge bg-primary-subtle text-dark">{{list.type}}</span>
                                    </td>
                                    <td class="text-center" v-else>
                                        <span :class="'badge bg-primary'">{{list.subtype}}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <img :src="list.requested_by_avatar" alt="" class="rounded-circle avatar-xxs">
                                            <span>{{ list.requested_by }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{formatDateRange(list.start, list.end)}}</td>
                                    <td class="text-center">{{ list.created_at }}</td>
                                    <td class="text-center">
                                        <span :class="list.is_completed ? 'badge bg-success-subtle text-success' : 'badge bg-warning-subtle text-warning'">
                                            {{ list.is_completed ? 'Completed' : 'In Progress' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <Link :href="`/requests/${list.link}`">
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
</template>
<script>
import _ from 'lodash';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect },
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
                status: null
            },
            statuses: [
                { value: 1, name: 'Completed' },
                { value: 0, name: 'In Progress' }
            ],
            icons: ['ri-flight-takeoff-fill','ri-car-fill','ri-calendar-2-fill','ri-alarm-fill'],
            index: null
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        },
        "filter.status"(newVal){
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
            page_url = page_url || '/request-monitoring';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    type: this.filter.type,
                    status: this.filter.status,
                    count: 10,
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
        formatDateRange(start, end) {
            const startDate = new Date(start);
            const endDate = new Date(end);

            const options = { month: 'long', day: 'numeric' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            const endStr = endDate.toLocaleDateString('en-US', { day: 'numeric' });

            if (start === end) {
            return startDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            }

            const year = startDate.getFullYear(); // assume same year
            return `${startStr}-${endStr}, ${year}`;
        },
        viewStatus(index,type){
            this.index = index;
            this.filter.type = type;
            this.fetch();
        },
        refresh(){
            this.filter.keyword = null;
            this.filter.status = null;
            this.fetch();
        }
    }
}
</script>
