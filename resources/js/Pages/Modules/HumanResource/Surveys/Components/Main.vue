<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-survey-line text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Consolidated data of Morale Survey</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">More Information for the employee</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white border-bottom">
            <b-row class="mb-n2 mt-n1">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" placeholder="Search.." class="form-control" style="width: 20%;">
                        <Multiselect v-if="menu == 'Unit'" :options="divisions" class="white" :searchable="true" label="name" v-model="division" placeholder="Select Division" style="width: 35%"/>
                        <Multiselect :options="menus" class="white" :searchable="true" v-model="menu" placeholder="Select Semester" style="width: 20%"/>
                        <b-button type="button" variant="primary" @click="openCreate">
                            <i class="ri-printer-fill search-icon"></i>
                        </b-button>
                    </div>
                </b-col>
            </b-row>
        </div>

        <div class="card-body bg-white rounded-bottom">
            <div class="table-responsive table-card">
                
                <simplebar data-simplebar style="height: calc(100vh - 435px); ">
                    <table class="table align-middle table-centered table-nowrap mb-0">
                        <thead class="text-muted table-light fs-11 thead-fixed">
                            <tr>
                                <th width="3%;"> #</th>
                                <th>{{menu}}</th>
                                <th width="7%;" class="text-center">Definityle No</th>
                                <th width="7%;" class="text-center">No</th>
                                <th width="7%;" class="text-center">Not Sure</th>
                                <th width="7%;" class="text-center">Yes</th>
                                <th width="7%;" class="text-center">Definitely Yes</th>
                                <th width="10%;"  class="text-center">Total</th>
                            </tr>
                        </thead>
                        
                        <tbody class="fs-12">
                            <tr v-for="(list,index) in overall.body" v-bind:key="index">
                                <td>{{index+1}}</td>
                                <td>{{list.name}}</td>
                                <td class="text-center">{{list.dn}}</td>
                                <td class="text-center">{{list.n}}</td>
                                <td class="text-center">{{list.ns}}</td>
                                <td class="text-center">{{list.y}}</td>
                                <td class="text-center">{{list.dy}}</td>
                                <td class="text-center fw-semibold">{{list.total}}</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-light tfoot-fixed">
                            <tr class="fs-12">
                                <th width="3%;"></th>
                                <th></th>  
                                <th width="7%;" class="text-center">{{overall.footer['dn']}}</th>  
                                <th width="7%;" class="text-center">{{overall.footer['n']}}</th> 
                                <th width="7%;" class="text-center">{{overall.footer['ns']}}</th>  
                                <th width="7%;" class="text-center">{{overall.footer['y']}}</th>    
                                <th width="7%;" class="text-center">{{overall.footer['dy']}}</th> 
                                <th width="10%;" class="text-center">{{overall.footer['total']}}</th> 
                            </tr>   
                        </tfoot>
                    </table>
                    
                </simplebar>
            </div>
        </div>

        <div class="card-body bg-white rounded-bottom"></div>
        
    </div>
</template>
<script>
import simplebar from "simplebar-vue";
import Multiselect from "@vueform/multiselect";
export default {
    components: { simplebar, Multiselect },
    props: ['employee','divisions'],
    data(){
        return {
            menus: [
                'Question','Station','Division','Unit'
            ],
            menu: 'Question',
            division: null,
            index: null,
            overall: {
                footer: []
            }
        }
    },
    watch: {
        "menu"(newVal){
            if(newVal != 'Unit'){
                this.division = null;
            }
            this.fetch();
        },
        "division"(newVal){
            this.fetch();
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(page_url){
            page_url = page_url || '/surveys';
            axios.get(page_url,{
                params : {
                    option: this.menu,
                    division: this.division
                }
            })
            .then(response => {
                this.overall = response.data;
            })
            .catch(err => console.log(err));
        },
    }
}
</script>