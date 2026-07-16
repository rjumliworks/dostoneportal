<template>
    <div class="card-body bg-white border-bottom shadow-none">
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
    </div>
    <div class="table-responsive table-card" style="height: calc(100vh - 330px);">
        <table class="table table-nowrap align-middle mb-0">
           
            <thead class="bg-primary text-white">
                <tr class="fs-10">
                    <th style="width: 4%;"></th>
                    <th>Name</th>
                    <th style="width: 20%;" class="text-center">Affiliation</th>
                    <th style="width: 20%;" class="text-center">Designation</th>
                    <th style="width: 20%;" class="text-center">Attendance Record</th>
                    <th style="width: 10%;" class="text-center">Status</th>
                    <th style="width: 5%;" class="text-center"></th>
                </tr>
            </thead>
            <tbody v-if="participants.length > 0">
                <tr v-for="(list,index) in participants" v-bind:key="index" class="fs-12">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <h5 class="fs-12 mb-0 fw-semibold text-primary">{{list.participant.name}}</h5>
                        <p class="fs-12 text-muted mb-0">{{list.participant.email }}</p>
                    </td>
                    <td class="text-center">{{list.participant.affiliation}}</td>
                    <td class="text-center">{{list.participant.designation}}</td>
                    <td class="text-center">{{list.attended_at}}</td>
                    <td class="text-center">
                        <span :class="'badge '+list.status.color+' '+list.status.type">{{list.status.name}}</span>
                    </td>
                    <td class="text-end">
                        <b-button @click="openView(list)" variant="primary" class="me-1" v-b-tooltip.hover title="View Participant" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button>
                        <!-- <b-button @click="openPrint1(list.code)" variant="primary" class="me-1" v-b-tooltip.hover title="Print Appreciation" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button> -->
                        <!-- <b-button @click="openPrint2(list.code)" variant="primary" class="me-1" v-b-tooltip.hover title="Print Appearance" size="sm">
                            <i class="ri-printer-fill align-bottom"></i>
                        </b-button> -->
                        <!-- <b-button @click="openEdit(list)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                            <i class="ri-pencil-fill align-bottom"></i>
                        </b-button> -->
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
    props: ['participants','id'],
    methods: {
        openCreate(){
            this.$refs.create.show();
        },
        openView(data){
            this.$refs.participant.show(data);
        },
        openEdit(list){
            this.$refs.create.edit(list);
        },
        openPrint(){
            window.open('/print?option=attendance&krdwrks='+this.id);
        },
        openPrint1(id){
            // window.open('/print?option=session&type=appearance&krdwrks='+id);
            window.open('/print?option=session&type=appreciation&krdwrks='+id);
        },
        openPrint2(id){
            // window.open('/print?option=session&type=appearance&krdwrks='+id);
            window.open('/print?option=session&type=appearance&krdwrks='+id);
        }
    }
}
</script>