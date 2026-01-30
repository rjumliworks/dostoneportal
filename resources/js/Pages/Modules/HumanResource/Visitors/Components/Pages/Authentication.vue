<template>
    <b-row class="g-2 mb-4" style="margin-top: -12px;">
       <b-col lg>
           <div class="input-group mb-1">
               <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
               <input type="text" placeholder="Search Keyword" class="form-control">
               <Multiselect class="white" style="width: 13%;" :options="months" v-model="month" label="short" :searchable="true" placeholder="Select Month" />
               <input type="text" v-model="year" class="form-control" style="width: 5px;">
               <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                   <i class="bx bx-refresh search-icon"></i>
               </span>
               <b-button type="button" variant="primary">
                   Search
               </b-button>
           </div>
       </b-col>
   </b-row>
   <div class="table-responsive table-card" style="height: calc(100vh - 460px); overflow: auto;">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light thead-fixed">
                <tr class="fs-11">
                    <th class="text-center" style="width: 5%;">#</th>
                    <th>Date</th>
                    <th class="text-center" style="width: 13%;">Am In</th>
                    <th class="text-center" style="width: 13%;">Am Out</th>
                    <th class="text-center" style="width: 13%;">Pm In</th>
                    <th class="text-center" style="width: 13%;">Pm Out</th>
                    <th class="text-center" style="width: 15%;">Total Hours</th>
                    <th style="width: 6%;"></th>
                </tr>
            </thead>
           <tbody v-if="lists.length > 0">
                <tr v-for="(list,index) in lists" v-bind:key="index">
                    <td>{{ index+1 }}</td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{list.date}}</h5>
                    </td>
                    <td class="text-center">{{ list.am_in ? list.am_in.time : '-' }}</td>
                    <td class="text-center">{{ list.am_out ? list.am_out.time : '-' }}</td>
                    <td class="text-center">{{ list.pm_in ? list.pm_in.time : '-' }}</td>
                    <td class="text-center">{{ list.pm_out ? list.pm_out.time : '-' }}</td>
                    <td class="text-center">-</td>
                    <td class="text-end">
                        <b-button @click="openView(list,index)" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7" class="text-center text-muted">No records found.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <View @update="updateList" ref="view"/>
</template>
<script>
import View from './Modals/View.vue';
import Multiselect from "@vueform/multiselect";
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Multiselect, View },
    props: ['code'],
    data(){
        return {
            lists: [],
            meta: {},
            links: {},
            month: new Date().toLocaleString('default', { month: 'long' }),
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            year: new Date().getFullYear(),
            index: null
        }
   },
    created(){
        this.fetch();
    },
    methods : {
        fetch(page_url){
            page_url = page_url || '/visitors';
            return axios.get(page_url,{
                params : {
                    code: this.code,
                    option: 'authentication-logs',
                    count: 10,
                }
            })
            .then(response => {
                this.lists = response.data;    
            });
        },
        openView(data,index){
            this.index = index;
            this.$refs.view.show(data);
        },
        updateList(data){
            this.lists[this.index] = data;
        },
    }
}
</script>