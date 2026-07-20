<template>
    <!-- <div class="card-body bg-white border-bottom shadow-none">
        <b-row class="mt-n3 mb-2 ms-n4 me-n4">
            <b-col lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input type="text"  placeholder="Search Venue" class="form-control" style="width: 30%;">
                    <span @click="openPrint()" class="input-group-text" v-b-tooltip.hover title="Print" style="cursor: pointer;"> 
                        <i class="ri-printer-fill search-icon"></i>
                    </span>
                    <b-button type="button" variant="primary" @click="openCreate">
                        <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                    </b-button>
                </div>
            </b-col>
        </b-row>
    </div> -->
    <div class="table-responsive table-card" style="height: calc(100vh - 390px);">
        <table class="table table-nowrap align-middle mb-0">
           
            <thead class="bg-primary text-white">
                <tr class="fs-10">
                      <th style="width: 3%;"></th>
                    <th>Name</th>
                    <th style="width: 15%;" class="text-center">Mobile</th>
                    <th style="width: 20%;" class="text-center">Email</th>
                    <th style="width: 20%;" class="text-center">Visited Date</th>
                    <th style="width: 5%;" class="text-center"></th>
                </tr>
            </thead>
            <tbody v-if="visitors.length > 0">
                <tr v-for="(list,index) in visitors" v-bind:key="index" class="fs-12">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <h5 class="fs-12 mb-0 fw-semibold text-primary">{{list.name}}</h5>
                        <p class="fs-12 text-muted mb-0">{{list.affiliation }}</p>
                    </td>
                   <td class="text-center">{{list.mobile}}</td>
                    <td class="text-center">{{list.email}}</td>
                    <td class="text-center">{{list.visited_at}}</td>
                    <td class="text-end">
                        <b-button @click="openView(list)" variant="primary" class="me-1" v-b-tooltip.hover title="View Participant" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center text-muted">No records found.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <Participant ref="participant"/>
</template>
<script>
import _ from 'lodash';
import Participant from './Modals/Participant.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Participant },
    props: ['visitors','id'],
    methods: {
    
    }
}
</script>