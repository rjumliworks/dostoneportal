<template>
    <div class="card-body bg-white shadow-none">
        <b-row class="mt-n3 mb-n2 ms-n4 me-n4">
            <b-col lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input v-model="search" type="text"  placeholder="Search Name" class="form-control" style="width: 30%;">
                    <span @click="openPrint()" class="input-group-text" v-b-tooltip.hover title="Print" style="cursor: pointer;">
                        <i class="ri-printer-fill search-icon"></i>
                    </span>
                    <b-button type="button" variant="primary" @click="openCreate">
                        <i class="ri-add-circle-fill align-bottom me-1"></i> Search
                    </b-button>
                </div>
            </b-col>
        </b-row>
    </div>
    <div class="card bg-white border-top shadow-none mt-0 mb-3 ms-n3 me-n3" no-body>
        <div class="d-flex">
            <div class="flex-grow-1">
                <ul class="nav nav-tabs nav-tabs-custom nav-primary fs-12" role="tablist">
                    <li class="nav-item">
                        <BLink @click="viewStatus(null,null)" class="nav-link py-3" :class="(statusIndex == null) ? 'text-primary active' : ''" data-bs-toggle="tab" role="tab" aria-selected="true">
                        <i class="ri-apps-2-line me-1 align-bottom"></i> All Participants
                        <BBadge :class="(statusIndex == null) ? 'bg-primary text-white' : 'text-dark bg-primary-subtle'" class="align-middle ms-1">{{participants.length}}</BBadge>
                        </BLink>
                    </li>
                    <li class="nav-item" v-for="(list,index) in registrationStatuses" v-bind:key="index">
                        <BLink @click="viewStatus(index,list.value)" class="nav-link py-3" :class="(statusIndex == index) ? 'text-primary active' : ''" data-bs-toggle="tab" role="tab" aria-selected="false">
                            <i :class="list.icon" class="me-1 align-bottom"></i>
                            {{ list.name }}
                            <BBadge :class="(statusIndex == index) ? 'bg-primary text-white' : 'text-dark bg-primary-subtle'" class="align-middle ms-1">{{list.count}}</BBadge>
                        </BLink>
                    </li>
                </ul>
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <b-button v-if="unmailedApprovedCount > 0" @click="confirmNotify = true" variant="soft-warning" size="sm">
                        <i class="ri-mail-send-line align-bottom me-1"></i> Send Approval Emails ({{ unmailedApprovedCount }})
                    </b-button>
                </div>
            </div>
        </div>
    </div>

    <b-modal
        v-model="confirmNotify"
        hide-footer
        header-class="p-0"
        body-class="p-0"
        class="v-modal-custom"
        content-class="border-0"
        centered
        hide-header-close
    >
        <b-row class="g-0">
            <b-col lg="12">
                <div class="modal-body p-4 text-center">
                    <div class="avatar-sm mx-auto mb-3">
                        <div class="avatar-title rounded-circle bg-warning-subtle text-warning">
                            <i class="ri-mail-send-line fs-24"></i>
                        </div>
                    </div>
                    <h4 class="fw-semibold fs-14">Send Approval Emails?</h4>
                    <p class="text-muted fs-12 mb-4">
                        This will send the approval confirmation email to {{ unmailedApprovedCount }} approved
                        participant{{ unmailedApprovedCount === 1 ? '' : 's' }} who haven't received it yet.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-light" @click="confirmNotify = false" :disabled="notifyForm.processing">Cancel</button>
                        <button class="btn btn-warning" @click="sendBulkApprovalEmails" :disabled="notifyForm.processing">Send</button>
                    </div>
                </div>
            </b-col>
        </b-row>
    </b-modal>
    
        <div class="table-responsive table-card" style="height: calc(100vh - 520px);">
            <table class="table table-nowrap align-middle mb-0">
            
                <thead class="bg-primary text-white thead-fixed">
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
import { useForm } from '@inertiajs/vue3';
import Participant from './Modals/Participant.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Participant },
    props: ['participants','id','is_exclusive','statuses'],
    data(){
        return {
            index: null,
            statusIndex: null,
            search: '',
            status: null,
            confirmNotify: false,
            notifyForm: useForm({
                id: null,
                option: 'notify-approved'
            })
        }
    },
     computed: {
        registrationStatuses() {
            return this.statuses
                .filter(status => status.type === 'Registration')
                .map(status => ({
                    ...status,
                    count: this.participants.filter(participant => participant.status.id === status.value).length
                }));
        },
        filteredParticipants() {
            const keyword = this.search.trim().toLowerCase();

            return this.participants
                .filter(participant => !keyword || participant.name?.toLowerCase().includes(keyword))
                .filter(participant => !this.status || participant.status.id === this.status);
        },
        unmailedApprovedCount() {
            if (!this.is_exclusive) return 0;
            return this.participants.filter(participant => participant.is_approved && !participant.approval_mailed_at).length;
        }
    },
    methods: {
        openView(data,index){
            this.index = index;
            this.$refs.participant.show(data);
        },
        openPrint(){
            let url = '/sessions?option=participants&id='+this.id;
            if(this.status){
                url += '&status='+this.status;
            }
            window.open(url);
        },
        viewStatus(index,value){
            this.statusIndex = index;
            this.status = value;
        },
        sendBulkApprovalEmails(){
            this.notifyForm.id = this.id;
            this.notifyForm.put('/sessions/update', {
                preserveScroll: true,
                onSuccess: () => {
                    this.confirmNotify = false;
                },
            });
        },
    }
}
</script>