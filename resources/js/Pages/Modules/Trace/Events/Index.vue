<template>
<Head title="Events"/>
    <PageHeader title="Event Management" pageTitle="List" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-calendar-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">List of Events</span></h5>
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
                                <input type="text" v-model="filter.keyword" placeholder="Search Event" class="form-control" style="width: 20%;">
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.audiences" v-model="filter.audience" label="name" :searchable="true" placeholder="Select Audience" />
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.modes" v-model="filter.mode" label="name" :searchable="true" placeholder="Select Mode" />
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.types" v-model="filter.type" label="name" :searchable="true" placeholder="Select Type" />
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
                <div class="card bg-white border-bottom shadow-none" no-body>
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <ul class="nav nav-tabs nav-tabs-custom nav-primary fs-12" role="tablist">
                                <li class="nav-item">
                                    <BLink @click="viewStatus(null,null)" class="nav-link py-3 active" data-bs-toggle="tab" role="tab" aria-selected="true">
                                    <i class="ri-apps-2-line me-1 align-bottom"></i> All Employees
                                    </BLink>
                                </li>
                                <li class="nav-item" v-for="(list,index) in dropdowns.audiences" v-bind:key="index">
                                    <BLink @click="viewStatus(index,list.value)" class="nav-link py-3" :class="(this.index == index) ? list.others+' active' : ''" data-bs-toggle="tab" role="tab" aria-selected="false">
                                        <i :class="icons[index]" class="me-1 align-bottom"></i>
                                        {{ list.name }} 
                                        <BBadge v-if="counts[index] > 0" :class="list.color" class="align-middle ms-1">{{counts[index]}}</BBadge>
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
                                    <th>Name</th>
                                    <th style="width: 10%;" class="text-center">Participants</th>
                                    <th style="width: 17%;" class="text-center">Type</th>
                                    <th style="width: 10%;" class="text-center">Mode</th>
                                    <th style="width: 15%;" class="text-center">Date</th>
                                    <th style="width: 8%;"></th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-for="(list,index) in lists" v-bind:key="index" @click="selectRow(index)">
                                    <td class="text-center">{{ index + 1 }}</td>
                                    <td>
                                        <h5 class="fs-13 mb-0 fw-semibold text-primary">{{list.title}}</h5>
                                        <p class="fs-12 text-muted mb-0">{{list.location.name}}</p>
                                    </td>
                                     <td class="text-center">{{ list.tags.length }}</td>
                                    <td class="text-center">{{ list.type.name }}</td>
                                    <td class="text-center">{{ list.mode.name }}</td>
                                    <td class="text-center">{{formatDateRange(list.start, list.end)}}</td>
                                    <td class="text-end">
                                        <b-button @click="openEdit(list,index)" variant="soft-warning" class="me-1" v-b-tooltip.hover title="Edit" size="sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </b-button>
                                        <Link :href="`/events/${list.link}`">
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
    <Create @update="updateUser" :dropdowns="dropdowns" ref="create"/>
</template>
<script>
import _ from 'lodash';
import Create from './Modals/Create.vue';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect, Create },
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
                mode: null,
                audience: null
            },
            index: null,
            selectedRow: null,
            icons: ['ri-government-line','ri-earth-line','ri-admin-line'],
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal);
        },
        "filter.mode"(newVal){
            this.fetch();
        },
        "filter.type"(newVal){
            this.fetch();
        },
        "filter.audience"(newVal){
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
            page_url = page_url || '/events';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    status: this.filter.status,
                    type: this.filter.type,
                    mode: this.filter.mode,
                    audience: this.filter.audience,
                    count: 10, 
                    option: 'list'
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
        viewStatus(index,type){
            this.index = index;
            this.filter.type = type;
            this.fetch();
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
        openCreate(){
            this.$refs.create.show();
        },
        openEdit(data,index){
            this.index = index;
            this.$refs.create.update(data);
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