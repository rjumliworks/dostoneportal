<template>
    <div class="card-body bg-white border-bottom shadow-none">
        <b-row class="mt-n3 mb-2 ms-n4 me-n4">
            <b-col lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input v-model="search" type="text"  placeholder="Search Name" class="form-control" style="width: 30%;">
                    <!-- <span @click="openPrint()" class="input-group-text" v-b-tooltip.hover title="Print" style="cursor: pointer;"> 
                        <i class="ri-printer-fill search-icon"></i>
                    </span> -->
                    <b-button type="button" variant="primary" @click="openCreate">
                        <i class="ri-add-circle-fill align-bottom me-1"></i> Search
                    </b-button>
                </div>
            </b-col>
        </b-row>
    </div>
    <div class="table-responsive table-card" style="height: calc(100vh - 460px);">
        <table class="table table-nowrap align-middle mb-0">
           
            <thead class="bg-primary text-white">
                <tr class="fs-10">
                    <th style="width: 3%;"></th>
                    <th>Name</th>
                    <th style="width: 25%;" class="text-center">Contact</th>
                    <th style="width: 12%;" class="text-center">Attendance Record</th>
                    <th style="width: 12%;" class="text-center">Status</th>
                    <th style="width: 5%;" class="text-center"></th>
                </tr>
            </thead>
            <tbody v-if="filteredParticipants.length > 0">
                <tr v-for="(list,index) in filteredParticipants" v-bind:key="index" class="fs-12">
                    <td class="text-center"> 
                        <div class="avatar-xs">
                            <img :src="list.avatar" class="rounded-circle avatar-xs" style="object-fit:cover;" alt="Avatar">
                        </div>
                    </td>
                    <td>
                        <h5 class="fs-12 mb-0 fw-semibold text-primary">{{list.name}}</h5>
                        <p class="fs-12 text-muted mb-0">{{list.affiliation.name}}</p>
                    </td>
                    <td class="text-center">
                         <h5 class="fs-11 mb-0">{{list.email}}</h5>
                        <p class="fs-11 text-muted mb-0">{{list.mobile }}</p>
                    </td>
                    <td class="text-center">{{ (list.attended_at) ? list.attended_at : '-'}}</td>
                    <td class="text-center">
                        <span :class="'badge '+list.status.color+' '+list.status.bg">{{list.status.name}}</span>
                    </td>
                    <td class="text-end">
                        <b-button @click="openView(list,index)" variant="success" class="me-1" v-b-tooltip.hover title="View Participant" size="sm">
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
    <Participant :is_exclusive="is_exclusive" ref="participant"/>
</template>
<script>
import _ from 'lodash';
import Participant from './Modals/Participant.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Participant },
    props: ['participants','id','is_exclusive'],
    data(){
        return {
            index: null,
            search: ''
        }
    },
     computed: {
        filteredParticipants() {
            if (!this.search.trim()) {
                return this.participants;
            }

            const keyword = this.search.toLowerCase();

            return this.participants.filter(participant =>
                participant.name?.toLowerCase().includes(keyword)
            );
        }
    },
    methods: {
        openView(data,index){
            this.index = index;
            this.$refs.participant.show(data);
        },
    }
}
</script>