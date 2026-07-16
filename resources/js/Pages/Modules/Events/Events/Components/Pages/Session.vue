<template>
    <div class="card-body bg-white border-bottom shadow-none">
        <b-row class="mt-n3 mb-2 ms-n4 me-n4">
            <b-col lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input type="text"  placeholder="Search Session" class="form-control" style="width: 30%;">
                    <b-button type="button" variant="primary" @click="openCreate">
                        <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                    </b-button>
                </div>
            </b-col>
        </b-row>
    </div>
    <div class="table-responsive table-card" style="height: calc(100vh - 330px);">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="bg-primary text-white thead-fixed">
                <tr class="fs-10">
                    <th style="width: 4%;"></th>
                    <th>Title</th>
                    <!-- <th style="width: 25%;" class="text-center">Venue</th> -->
                    <th style="width: 10%;" class="text-center">Participants</th>
                    <th style="width: 12%;" class="text-center">Date</th>
                    <th style="width: 10%;" class="text-center">Registration</th>
                    <th style="width: 10%;" class="text-center">Status</th>
                    <th style="width: 10%;" class="text-center"></th>
                </tr>
            </thead>
            <tbody v-if="sessions.length > 0">
                <tr v-for="(list,index) in sessions" v-bind:key="index" class="fs-12">
                    <td class="text-center">{{ index + 1 }}.</td>
                    <td>
                        <h5 class="fs-12 mb-0 fw-semibold text-primary">{{list.title}}</h5>
                        <p class="fs-12 text-muted mb-0">{{list.venue.name}}, {{ list.venue.establishment }}</p>
                    </td>
                    <!-- <td class="text-center">{{list.venue.name}}, {{ list.venue.establishment }}</td> -->
                    <td class="text-center" v-if="!list.has_registration">{{list.participants.length }}</td>
                    <td class="text-center" v-else>{{list.attendees.length}} / {{list.participants.length }}</td>
                    <td class="text-center">{{ dateRange(list.schedules) }}</td>
                    <!-- <td class="text-center">
                        <span v-if="list.has_registration" class="badge bg-success">Required</span>
                        <span v-else class="badge bg-danger">Not Required</span>
                    </td> -->
                    <td class="text-center">
                        <i v-if="list.has_registration" class="ri-checkbox-circle-fill text-success fs-18" v-b-tooltip.hover title="Required"></i>
                        <i v-else class="ri-close-circle-fill text-danger fs-18" v-b-tooltip.hover title="Not Required"></i>
                    </td>
                    <td class="text-center">
                        <span :class="'badge '+list.status.color+' '+list.status.type">{{list.status.name}}</span>
                    </td>
                     <td class="text-end">
                        <a :href="`/sessions/${list.reference}`" target="_blank">
                            <b-button variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                <i class="ri-eye-fill align-bottom"></i>
                            </b-button>
                        </a>
                        <b-button @click="openEdit(list)" class="me-1" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                            <i class="ri-pencil-fill align-bottom"></i>
                        </b-button>
                         <b-button @click="openPrint(list)" variant="soft-danger" v-b-tooltip.hover title="Print" size="sm">
                            <i class="ri-printer-fill align-bottom"></i>
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
    <Create :id="id" :venues="venues" ref="create"/>
</template>
<script>
import _ from 'lodash';
import Create from './Modals/CreateSession.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Create },
    props: ['id','sessions','venues'],
    methods: {
        openCreate(){
            this.$refs.create.show();
        },
        openEdit(list){
            this.$refs.create.edit(list);
        },
        openPrint(list){
             window.open('/sessions?option=print&type=session&id='+list.id);
        },  
        dateRange(schedules) {
            if (!schedules || schedules.length === 0) return 'No date';

            let start = schedules[0].date;
            let end = schedules[0].date;

            schedules.forEach(item => {
                if (item.date < start) start = item.date;
                if (item.date > end) end = item.date;
            });

            const formatDate = (dateStr) => {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };

            return start === end
                ? formatDate(start)
                : `${formatDate(start)} - ${formatDate(end)}`;
        }
    }
}
</script>