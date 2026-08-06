<template>
<div class="card bg-light-subtle shadow-none border">
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-briefcase-fill text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Work Experience</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">Your service record with DOST is listed automatically. Add private employment below.</p>
            </div>
            <div class="flex-shrink-0">
                <b-button variant="primary" size="sm" @click="openCreate()"><i class="ri-add-circle-fill align-bottom me-1"></i> Add</b-button>
            </div>
        </div>
    </div>
    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 291px); overflow: auto;">
        <div class="table-responsive table-card">
            <table class="table align-middle table-striped table-centered mb-0">
                <thead class="table-primary thead-fixed">
                    <tr class="fs-11">
                        <th class="text-center">Inclusive Dates</th>
                        <th>Position / Department</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Gov't Service</th>
                        <th class="text-center">Source</th>
                        <th style="width: 8%;"></th>
                    </tr>
                </thead>
                <tbody class="fs-12" v-if="merged.length > 0">
                    <tr v-for="(list,index) in merged" v-bind:key="index">
                        <td class="text-center">{{ list.start_at }} - {{ list.end_at || 'Present' }}</td>
                        <td>
                            <h5 class="fs-13 mb-0 fw-semibold text-primary">{{ list.position_title }}</h5>
                            <p class="fs-12 text-muted mb-0">{{ list.department_agency }}</p>
                        </td>
                        <td class="text-center">{{ list.appointment_status || '-' }}</td>
                        <td class="text-center">{{ list.is_government ? 'Y' : 'N' }}</td>
                        <td class="text-center">
                            <span v-if="list.source === 'system'" class="badge bg-info-subtle text-info">DOST Record</span>
                            <span v-else class="badge bg-secondary-subtle text-secondary">Self-reported</span>
                        </td>
                        <td class="text-end">
                            <template v-if="list.source !== 'system'">
                                <b-button @click="openEdit(list)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm" class="me-1">
                                    <i class="ri-pencil-fill align-bottom"></i>
                                </b-button>
                                <b-button @click="remove(list)" variant="soft-danger" v-b-tooltip.hover title="Delete" size="sm">
                                    <i class="ri-delete-bin-fill align-bottom"></i>
                                </b-button>
                            </template>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr><td colspan="6" class="text-center text-muted">No records found.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <Modal ref="modal" @success="refresh"/>
</div>
</template>
<script>
import { router } from '@inertiajs/vue3';
import Modal from './Modals/WorkExperience.vue';
export default {
    components: { Modal },
    props: ['lists','contracts'],
    computed: {
        merged(){
            const system = (this.contracts || []).map(c => ({
                id: c.id,
                start_at: c.start_at,
                end_at: c.is_active ? null : c.end_at,
                position_title: c.position?.name,
                department_agency: 'Department of Science and Technology',
                appointment_status: c.type?.name,
                is_government: 1,
                source: 'system'
            }));
            const self = (this.lists || []).map(l => ({ ...l, source: 'self' }));
            return [...system, ...self].sort((a,b) => new Date(b.start_at) - new Date(a.start_at));
        }
    },
    methods: {
        openCreate(){
            this.$refs.modal.show();
        },
        openEdit(data){
            this.$refs.modal.edit(data);
        },
        remove(data){
            if(!confirm('Remove this work experience record?')) return;
            router.delete('/profile/pds/'+data.id, {
                data: { option: 'work_experience' },
                preserveScroll: true,
                onSuccess: () => this.refresh(),
            });
        },
        refresh(){
            router.reload({ only: ['workExperiences'], preserveScroll: true });
        }
    }
}
</script>
