<template>
    <Head title="Buildings"/>
        <PageHeader title="Building Management" pageTitle="List" />
        <BRow>
            <div class="col-md-12">
                <div class="card bg-light-subtle shadow-none border">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3">
                                <div style="height:2.5rem;width:2.5rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-government-fill text-primary fs-24"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-14"><span class="text-body">List of Buildings</span></h5>
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
                                    <input type="text" v-model="filter.keyword" placeholder="Search Building" class="form-control" style="width: 50%;">
                                    <Multiselect class="white" style="width: 17%;" :options="dropdowns.stations" v-model="filter.station" label="name" :searchable="true" placeholder="Select Station" />
                                    <span @click="fetch()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;">
                                        <i class="bx bx-refresh search-icon"></i>
                                    </span>
                                    <b-button type="button" variant="primary" @click="openCreate">
                                        <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                                    </b-button>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                    <div class="card-body bg-white rounded-bottom">
                        <div class="table-responsive table-card" style="height: calc(100vh - 420px); overflow: auto;">
                            <table class="table align-middle table-centered mb-0">
                                <thead class="table-light thead-fixed">
                                    <tr class="fs-11">
                                        <th style="width: 3%;"></th>
                                        <th style="width: 12%;">Code</th>
                                        <th>Name</th>
                                        <th style="width: 15%;" class="text-center">Station</th>
                                        <th style="width: 12%;" class="text-center">Date Added</th>
                                        <th style="width: 6%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="table-white fs-12">
                                    <tr v-for="(list,index) in lists" v-bind:key="index" >
                                        <td class="text-center">{{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                                        <td class="fs-13 fw-semibold text-primary">{{ list.code }}</td>
                                        <td>
                                            <h5 class="fs-13 mb-0 fw-semibold text-primary">{{ list.name }}</h5>
                                            <p class="fs-12 text-muted mb-0">{{ [list.address, list.barangay?.name, list.municipality?.name, list.province?.name].filter(Boolean).join(', ') }}</p>
                                        </td>
                                        <td class="text-center">{{ list.station?.name }}</td>
                                        <td class="text-center">{{ list.created_at }}</td>
                                        <td class="text-end">
                                            <div class="d-flex gap-3 justify-content-center">
                                                <div class="dropdown">
                                                    <BDropdown variant="link" toggle-class="btn btn-light btn-sm dropdown" no-caret menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }">
                                                        <template #button-content>
                                                            <i class="ri-more-fill"></i>
                                                        </template>
                                                        <li>
                                                            <Link :href="`/buildings/${list.id}`" class="dropdown-item d-flex align-items-center" role="button">
                                                                <i class="ri-eye-fill me-2"></i> View
                                                            </Link>
                                                        </li>
                                                        <li>
                                                            <a @click="openUpdate(list)" class="dropdown-item d-flex align-items-center" role="button">
                                                                <i class="ri-edit-2-fill me-2"></i> Update
                                                            </a>
                                                        </li>
                                                    </BDropdown>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="!lists.length">
                                        <td colspan="5" class="text-center text-muted py-4">No buildings found</td>
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
        <Create :dropdowns="dropdowns" ref="create" @update="onCreated"/>
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
                    keyword: null,
                    station: null
                },
                index: null
            }
        },
        watch: {
            "filter.station"(newVal){
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
            openCreate(){
                this.$refs.create.show();
            },
            openUpdate(list){
                this.$refs.create.edit(list);
            },
            onCreated(){
                this.fetch();
            },
            checkSearchStr: _.debounce(function(string) {
                this.fetch();
            }, 300),
            fetch(page_url){
                page_url = page_url || '/buildings';
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
