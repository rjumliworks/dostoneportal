<template>
    <Head title="Visitors"/>
        <PageHeader title="Visitor Management" pageTitle="List" />
        <BRow>
            <div class="col-md-12">
                <div class="card bg-light-subtle shadow-none border">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3">
                                <div style="height:2.5rem;width:2.5rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-group-2-fill text-primary fs-24"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-14"><span class="text-body">List of Visitors</span></h5>
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
                                    <Multiselect class="white" style="width: 17%;" :options="dropdowns.types" v-model="filter.type" label="name" :searchable="true" placeholder="Select Type" />
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
                                        <i class="ri-apps-2-line me-1 align-bottom"></i> All Visitors
                                        </BLink>
                                    </li>
                                    <li class="nav-item" v-for="(list,index) in dropdowns.statuses" v-bind:key="index">
                                        <BLink @click="viewStatus(index,list.value)" class="nav-link py-3" :class="(this.index == index) ? 'text-secondary active' : ''" data-bs-toggle="tab" role="tab" aria-selected="false">
                                            <i :class="icons[index]" class="me-1 align-bottom"></i>
                                            {{ list.name }} <BBadge v-if="counts[index] > 0" :class="list.bg" class="align-middle ms-1">{{counts[index]}}</BBadge>
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
                                        <th style="width: 13%;" class="text-center">Affiliation</th>
                                        <th style="width: 20%;" class="text-center">Designation</th>
                                        <th style="width: 15%;" class="text-center">Type</th>
                                        <th style="width: 8%;" class="text-center">Status</th>
                                        <th style="width: 4%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="table-white fs-12">
                                    <tr v-for="(list,index) in lists" v-bind:key="index" :class="{
                                        'bg-info-subtle': index === selectedRow,
                                        // 'bg-danger-subtle': list.is_active === 0 && index !== selectedRow
                                    }">
                                        <td class="text-center">{{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                                        <td>
                                            <h5 class="fs-13 mb-0 fw-semibold text-primary">{{list.name }}</h5>
                                            <p class="fs-12 text-muted mb-0">{{list.username}}</p>
                                        </td>
                                        <td class="text-center">{{ list.affiliation.name }}</td>
                                        <td class="text-center">{{ list.designation.name }}</td>
                                        <td class="text-center">{{ list.type.name }}</td>
                                        <td class="text-center">
                                            <span :class="'badge '+list.status.bg">{{list.status.name}}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-3 justify-content-center"> 
                                                <div class="dropdown">
                                                    <BDropdown variant="link" toggle-class="btn btn-light btn-sm dropdown" no-caret menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }"> 
                                                        <template #button-content> 
                                                            <i class="ri-more-fill"></i>
                                                        </template>
                                                        <li>
                                                            <Link :href="`/visitors/${list.code}`" class="dropdown-item d-flex align-items-center" role="button">
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
                                                            <a @click="openActivation(list,index)" class="dropdown-item d-flex align-items-center" role="button">
                                                                <span v-if="list.status.name == 'Active'" class="text-danger"><i class="ri-lock-2-fill me-2"></i> Deactivate User</span>
                                                                <span v-else class="text-success"><i class="ri-lock-unlock-line me-2"></i> Activate User</span>
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
        <Activation @update="updateData" ref="activation"/>
    </template>
    <script>
    import _ from 'lodash';
    import Create from './Modals/Create.vue';
    import Activation from './Modals/Activation.vue';
    import Multiselect from "@vueform/multiselect";
    import PageHeader from '@/Shared/Components/PageHeader.vue';
    import Pagination from "@/Shared/Components/Pagination.vue";
    export default {
        components: { PageHeader, Pagination, Multiselect, Create, Activation },
        props:['dropdowns','counts'],
        data(){
            return {
                icons: ['ri-checkbox-circle-fill','ri-close-circle-fill'],
                lists: [],
                meta: {},
                links: {},
                filter: {
                    keyword: null,
                    type: null
                },
                index: null,
                selectedRow: null,
            }
        },
        watch: {
            "filter.type"(newVal){
                this.fetch();
            },
            "filter.keyword"(newVal){
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
                page_url = page_url || '/visitors';
                axios.get(page_url,{
                    params : {
                        keyword: this.filter.keyword,
                        type: this.filter.type,
                        status: this.filter.status,
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
            viewStatus(index,status){
                this.index = index;
                this.filter.status = status;
                this.fetch();
            },
            openCreate(){
                this.$refs.create.show();
            },
            openActivation(data,index){
                this.index = index;
                this.selectedRow = index;
                this.$refs.activation.show(data);
            },
            updateData(data){
                this.lists[this.index] = data;
            }
        }
    }
    </script>