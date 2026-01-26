<template>
    <Head title="Vehicles"/>
        <PageHeader title="Vehicle Management" pageTitle="List" />
        <BRow>
            <div class="col-md-12">
                <div class="card bg-light-subtle shadow-none border">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3">
                                <div style="height:2.5rem;width:2.5rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-car-fill text-primary fs-24"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-14"><span class="text-body">List of Vehicles</span></h5>
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
                                    <input type="text" v-model="filter.keyword" placeholder="Search Vehicle" class="form-control" style="width: 50%;">
                                    <!-- <input type="text" v-model="filter.year" placeholder="Enter Year" class="form-control" style="width: 5%;"> -->
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
                                        <th>Name</th>
                                        <th style="width: 15%;" class="text-center">Driver</th>
                                        <th style="width: 8%;" class="text-center">Plate</th>
                                        <th style="width: 11%;" class="text-center">Type</th>
                                        <th style="width: 11%;" class="text-center">Date Acquired</th>
                                        <th style="width: 8%;" class="text-center">Status</th>
                                        <th style="width: 6%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="table-white fs-12">
                                    <tr v-for="(list,index) in lists" v-bind:key="index" >
                                        <td class="text-center">{{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                                        <td>
                                            <h5 class="fs-13 mb-0 fw-semibold text-primary">{{list.name }}</h5>
                                            <p class="fs-12 text-muted mb-0">{{list.station.name}}</p>
                                        </td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">{{ list.plate }}</td>
                                        <td class="text-center">{{ list.type }}</td>
                                        <td class="text-center">{{ list.acquired_at }}</td>
                                        <td class="text-center">
                                            <span :class="'badge '+list.status.bg+' '+list.status.type">{{list.status.name}}</span>
                                        </td>
                                        <td class="text-end">
                                        <div class="d-flex gap-3 justify-content-center">
                                            <div class="dropdown">
                                                <BDropdown variant="link" toggle-class="btn btn-light btn-sm dropdown" no-caret menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }"> 
                                                    <template #button-content> 
                                                        <i class="ri-more-fill"></i>
                                                    </template>
                                                    <li>
                                                        <Link :href="`/users/${list.code}`" class="dropdown-item d-flex align-items-center" role="button">
                                                            <i class="ri-eye-fill me-2"></i> View
                                                        </Link>
                                                    </li>
                                                    <li>
                                                        <a @click="openUpdate(list,index)" class="dropdown-item d-flex align-items-center" role="button">
                                                            <i class="ri-edit-2-fill me-2"></i> Update
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a @click="openUpdate(list,index)" class="dropdown-item d-flex align-items-center" role="button">
                                                            <i class="ri-user-2-fill me-2"></i> Update Driver
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a @click="openActivation('activation',list,index)" class="dropdown-item d-flex align-items-center" :class="(list.is_active) ? 'text-danger' : 'text-success'" href="#removeFileItemModal" data-id="1" data-bs-toggle="modal" role="button">
                                                            <span v-if="list.is_active" class="text-success"><i class="ri-tools-fill me-2"></i> End Maintenance</span>
                                                            <span v-else class="text-danger"><i class="ri-tools-fill me-2"></i>Start Maintenance</span>
                                                        </a>
                                                    </li>
                                                </BDropdown>
                                            </div>
                                        </div>
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
        props: ['dropdowns'],
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
        created(){
           this.fetch();
        },
        methods: { 
            openCreate(){
                this.$refs.create.show();
            },
            checkSearchStr: _.debounce(function(string) {
                this.fetch();
            }, 300),
            fetch(page_url){
                page_url = page_url || '/vehicles';
                axios.get(page_url,{
                    params : {
                        keyword: this.filter.keyword,
                        station: this.filter.station,
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
        }
    }
    </script>