<template>
    <PageHeader class="m-3 mt-4" title="BAC Resolutions"  />
    <!-- <b-row>
        <h5>
            <div>
                <span class="font-weight-bold">
                        PR REQUEST NO:
                        <u class="text-info">
                        <span class="bg-light  p-1">
                            {{  procurement.code }}
                        </span>
                    </u>
                </span>
        </div>
        </h5>
    </b-row> -->
    <b-row class="g-2 mb-3 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <b-button type="button" variant="info" @click="goBackPage()">
                    <i class="ri-arrow-left-line align-bottom me-1"></i> Back
                </b-button>
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="filter.keyword" placeholder="Search BAC Resolutions" class="form-control" style="width: 60%;">
                <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <b-button v-if="procurement.status.name == 'For BAC Resolution' || (procurement.status.name === 'Rebid' && procurement.sub_status?.name == null) || procurement.status.name == 'Re-award'" type="button" variant="primary" @click="openBACReso()">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> New
                </b-button>
            </div>
        </b-col>
    </b-row>

    <b-card no-body>
  
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th>#</th>
                                <th>BAC Resolution No.</th>  
                                <th>Type</th>
                                <th>Date Created</th>   
                                <th>Status</th>   
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="custom-hover-row" v-for="(list, index) in lists" v-bind:key="index" @click="selectRow(list.id)" :class="{ 'bg-info-subtle': selectedRow === list.id }" >
                                <td>{{ index + 1 }}</td>
                                <td>{{ list.code }}</td>
                                <td>{{ list.type }}</td>
                                <td>{{ list.created_at }}</td>
                                <td>
                                    <b-badge :class="list.status.bg">{{ list.status?.name }}</b-badge>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-3 justify-content-center">
                                <div class="dropdown" @click.stop>
                                    <button
                                    class="btn btn-light btn-icon btn-sm dropdown material-shadow-none"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    >
                                    <i class="ri-more-fill align-bottom"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdownmenu-primary dropdown-menu-end">
                                    <li
                                        @click="printBACReso(list)"
                                        class="dropdown-item d-flex align-items-center"
                                        role="button"
                                        >
                                        <i class="ri-printer-fill align-bottom me-1"></i>Print
                                    </li>
                                    <li
                                        @click="editBACReso(list)"
                                        class="dropdown-item d-flex align-items-center"
                                        role="button"
                                        >
                                        <i class="ri-edit-2-fill align-bottom me-1"></i>Edit 
                                    </li>
                                        <li
                                        v-if="list.status.name == 'Pending'"
                                        @click="updateStatus(list)"
                                        class="dropdown-item d-flex align-items-center"
                                        role="button"
                                        >
                                        <i class="ri-edit-2-fill align-bottom me-1"></i>Update Status
                                    </li>

                                        <li
                                        v-if="list.type != 'Rebid' && list.status.name == 'Approved' || list.status.name == 'NOA Not Conformed' "
                                        @click="goNOAPage(list)"
                                        class="dropdown-item d-flex align-items-center"
                                        role="button"
                                        >
                                        <i class="ri-eye-2-fill align-bottom me-1"></i> Notice of Awards
                                    </li>
                                    
                                    </ul>
                                </div>
                                </div>


                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                        
           
    </b-card>

<BACResolution  :procurement="procurement" :logs="logs" @add="fetch()" @update="fetch()"  ref="BACReso" />
<UpdateStatus  :procurement="procurement"   @add="fetch()"  @update="fetch()"  ref="updateStatus"/>

</template>
<script>
import _ from 'lodash';

import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
import BACResolution from "../Modals/BACResolution.vue";
import UpdateStatus from "../Modals/UpdateStatus.vue";
import { router } from '@inertiajs/vue3';
export default {
props: ['procurement', 'logs'],
components: { PageHeader, Pagination, BACResolution , UpdateStatus },
data(){
    return {
        currentUrl: window.location.origin,
        lists: [],
        meta: {},
        links: {},
        filter: {
            keyword: null,
        },
        selectedRow: null,
        index: null,
        option: "",
    }
},
watch: {
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
        page_url = '/faims/bac-resolutions' ;
        axios.get(page_url,{
            params : {
                keyword: this.filter.keyword,
                option: 'lists',
                procurement_id: this.procurement.id,
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

    openBACReso(){
        let type = "";
        if(this.procurement.status.name === 'For Approval of BAC Resolution'){
            type = "Award";
        }
        else if(this.procurement.status.name === 'Re-award' || this.procurement.status.name === 'Ongoing Re-award'){
            type = "Re-award";
        }
        else{
             type = "Rebid";
        }
        this.$refs.BACReso.show(type);
    },

    editBACReso(data){
        let type = "";
        if(this.procurement.status_id === 45){
            type = "Award";
        }
        else{
             type = "Rebid";
        }
        this.$refs.BACReso.edit(data);
    },

    updateStatus(data){
        let type = "BACResolution";
        this.$refs.updateStatus.show(data, type);
    },


    printBACReso(data){
        window.open('/faims/bac-resolutions/'+data.id + '?option=print&type=bac_resolution');
    },

    goBackPage(){
        this.$inertia.visit('/faims/procurements');
    },

     goNOAPage(data) {
        router.get('/faims/procurements/'+this.procurement.id + '?bac_reso_id='+ data.id + '&option=notice_of_awards');

    
    },

     selectRow(index) {
        this.selectedRow = (this.selectedRow == index) ? null : index;
    },
}
}
</script>

<style scoped>
.custom-hover-row:hover {
background-color: hsl(0, 29%, 97%); 
}

</style>