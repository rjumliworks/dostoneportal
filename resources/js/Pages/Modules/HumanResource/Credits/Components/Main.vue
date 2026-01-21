<template>
    <div class="card bg-light-subtle shadow-none border">
       
        <template v-if="show">
            <div class="card-header bg-light-subtle">
                <div class="d-flex mb-n3">
                    <div class="flex-shrink-0 me-3">
                        <div style="height:2.5rem;width:2.5rem;">
                            <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                <i class="ri-time-line text-primary fs-24"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-0 fs-14"><span class="text-body">{{ selected.leave.name}}</span></h5>
                        <p class="text-muted text-truncate-two-lines fs-12">Includes official comments, document references, and chronological status updates</p>
                    </div>
                </div>
            </div>
            <div class="card bg-white border-bottom shadow-none" no-body>
                <div class="row g-3 p-3">
                    <div class="col-md-3">
                        <div class="d-flex border border-dashed rounded p-3">
                     
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 text-muted fs-12">Earned :</p>
                                <h6 class="fw-semibold fs-12 mb-0"> {{ selected.earned }} </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex border border-dashed rounded p-3">
                     
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 text-muted fs-12">Used :</p>
                                <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ selected.used }} </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex border border-dashed rounded p-3">
                        
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 text-muted fs-12">Carried Over :</p>
                                <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ selected.used }} </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex border border-dashed rounded p-3">
                    
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 text-muted fs-12">Balance :</p>
                                <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{selected.balance}} </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body bg-white rounded-bottom">
                <div class="table-responsive table-card" style="margin-top: -39px; height: calc(100vh - 502px); overflow: auto;">
                    <table class="table align-middle table-striped table-centered mb-0">
                        <thead class="table-light thead-fixed">
                            <tr class="fs-11">
                                <th class="text-center" style="width: 6%;">#</th>
                                <th class="text-center" style="width: 12%;">Credits</th>
                                <th class="text-center" style="width: 12%;">Old Balance</th>
                                <th class="text-center" style="width: 12%;">New Balance</th>
                                <th class="text-center" style="width: 25%;">Date</th>
                                <th class="text-center" style="width: 15%;">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-white fs-12">
                            <tr v-for="(list,index) in lists" v-bind:key="index">
                                <td class="text-center">{{ index+1 }}</td>
                                <td class="text-center">{{list.amount}}</td>
                                <td class="text-center">{{list.old_balance}}</td>
                                <td class="text-center">{{list.new_balance}}</td>
                                <td class="text-center">{{list.created_at}}</td>
                                <td class="text-center">
                                    <span v-if="list.type.name ==  'Earn'" class="badge bg-success">Earned</span>
                                    <span v-else class="badge bg-danger">Deducted</span>
                                </td>
                                <!-- <td class="text-center">
                                    <b-button @click="openView(list)" variant="soft-info" v-b-tooltip.hover title="View" size="sm">
                                        <i class="ri-eye-fill align-bottom"></i>
                                    </b-button>
                                </td> -->
                            </tr>
                        </tbody>
                        
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <Pagination class="ms-2 me-2 mt-n1" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
        </template>
        <template v-else>
            <div class="card-body bg-white rounded d-flex justify-content-center align-items-center" style="height: calc(100vh - 277px); overflow: auto;">
                <p class="text-muted text-center fs-12 mb-0">Please choose on the list of credits to view the history</p>
            </div>
        </template>
    </div>
</template>
<script>
import _ from 'lodash';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination },
    data(){
        return {
            lists: [],
            meta: {},
            links: {},
            selected: null,
            id: null,
            show: false
        }
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        openView(selected){
            this.selected = selected;
            this.id = selected.id;
            this.fetch();
        },
        fetch(page_url){
            page_url = page_url || '/credits';
            axios.get(page_url,{
                params : {
                    id: this.id,
                    count: 10,
                    option: 'logs'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;      
                    this.show = true;    
                }
            })
            .catch(err => console.log(err));
        }
    }
}
</script>