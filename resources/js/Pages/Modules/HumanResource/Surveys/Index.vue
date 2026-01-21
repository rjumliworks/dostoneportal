<template>
    <Head title="Employees"/>
        <PageHeader title="Morale Survey Management" pageTitle="List" />
        <BRow>
            <div class="col-md-12">
                <div class="card bg-light-subtle shadow-none border">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3">
                                <div style="height:2.5rem;width:2.5rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-survey-fill text-primary fs-24"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-14"><span class="text-body">List of Morale Survey</span></h5>
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
                                    <input type="text" v-model="filter.keyword" placeholder="Search Employee" class="form-control" style="width: 50%;">
                                    <input type="text" v-model="filter.year" placeholder="Enter Year" class="form-control" style="width: 5%;">
                                    <Multiselect class="white" style="width: 17%;" :options="dropdowns.semesters" v-model="filter.semester" label="name" :searchable="true" placeholder="Select Semester" />
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
                                        <i class="ri-apps-2-line me-1 align-bottom"></i> All Surveys
                                        </BLink>
                                    </li>
                                    <li class="nav-item" v-for="(list,index) in counts" v-bind:key="index">
                                        <BLink @click="viewStatus(index,list.value)" class="nav-link py-3" :class="(this.index == index) ? 'text-primary active' : ''" data-bs-toggle="tab" role="tab" aria-selected="false">
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
                            <table class="table align-middle table-centered mb-0">
                                <thead class="table-light thead-fixed">
                                    <tr class="fs-11">
                                        <th style="width: 3%;"></th>
                                        <th>Year</th>
                                        <th style="width: 13%;" class="text-center">Count</th>
                                        <th style="width: 15%;" class="text-center">Date Created</th>
                                        <th style="width: 15%;" class="text-center">Date Finished</th>
                                        <th style="width: 10%;" class="text-center">Status</th>
                                        <th style="width: 6%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="table-white fs-12">
                                    <tr v-for="(list,index) in lists" v-bind:key="index" >
                                        <td class="text-center">{{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                                        <td>
                                            <h5 class="fs-13 mb-0 fw-semibold text-primary">{{list.year}} - {{ list.semester.name }}</h5>
                                        </td>
                                        <td class="text-center">{{list.answers_count}}</td>
                                        <td class="text-center">{{ list.created_at }}</td>
                                        <td class="text-center">{{ list.finished_at }}</td>
                                        <td class="text-center">
                                            <span v-if="list.is_active" class="badge bg-success">Active</span>
                                            <span v-else class="badge bg-danger">Inactive</span>
                                        </td>
                                        <td class="text-end">
                                            <Link :href="`/surveys/${list.code}`">
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
        <Create :dropdowns="dropdowns" ref="create"/>
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
                    year: null,
                    semester: null
                },
                index: null,
                units: []
            }
        },
        watch: {
            "filter.semester"(newVal){
                this.fetch();
            },
            "filter.year"(newVal){
                this.checkSearchStr(newVal);
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
                page_url = page_url || '/surveys';
                axios.get(page_url,{
                    params : {
                        keyword: this.filter.keyword,
                        semester: this.filter.semester,
                        year: this.filter.year,
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
            openCreate(){
                this.$refs.create.show();
            }
        }
    }
    </script>