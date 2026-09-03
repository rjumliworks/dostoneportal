<template>
    <div class="d-flex justify-content-end mb-2">
        <b-button type="button" variant="primary" size="sm" @click="$emit('create')">
            <i class="ri-add-circle-fill align-bottom me-1"></i> Request Maintenance
        </b-button>
    </div>
    <div class="table-responsive table-card">
        <simplebar data-simplebar style="height: calc(100vh - 470px);">
            <table class="table table-nowrap align-middle mb-0">
                <thead class="table-light thead-fixed">
                    <tr class="fs-11">
                        <th style="width: 4%;" class="text-center">#</th>
                        <th style="width: 10%;" class="text-center">Code</th>
                        <th style="width: 9%;" class="text-center">Date Requested</th>
                        <th>Work Requested</th>
                        <th style="width: 12%;" class="text-center">Requested By</th>
                        <th style="width: 9%;" class="text-center">Priority</th>
                        <th style="width: 10%;" class="text-center">Status</th>
                        <th style="width: 6%;"></th>
                    </tr>
                </thead>
                <tbody v-if="lists.length > 0">
                    <tr class="fs-12" v-for="(list,index) in lists" v-bind:key="index">
                        <td class="text-center">{{ index+1 }}</td>
                        <td class="text-center text-primary fw-semibold">{{ list.code }}</td>
                        <td class="text-center text-muted">{{ list.requested_at }}</td>
                        <td>
                            <p class="mb-0">{{ list.work_requested }}</p>
                            <p v-if="list.location" class="fs-11 text-muted mb-0"><i class="ri-map-pin-line align-bottom"></i> {{ list.location }}</p>
                            <p v-if="list.record" class="fs-11 mb-0">
                                <i class="ri-links-line align-bottom"></i> Linked to a maintenance record dated {{ list.record.date }}
                            </p>
                        </td>
                        <td class="text-center text-muted">{{ list.requester?.profile?.fullname || '-' }}</td>
                        <td class="text-center">
                            <span v-if="list.priority" class="badge bg-secondary-subtle text-secondary">{{ list.priority.name }}</span>
                            <span v-else class="text-muted">-</span>
                        </td>
                        <td class="text-center">
                            <span v-if="list.status" :class="'badge '+list.status.bg+' '+list.status.type">{{ list.status.name }}</span>
                            <span v-else class="text-muted">-</span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <BDropdown variant="link" toggle-class="btn btn-light btn-sm dropdown" no-caret menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }">
                                    <template #button-content>
                                        <i class="ri-more-fill"></i>
                                    </template>
                                    <li v-if="!list.record">
                                        <a @click="$emit('fulfill', list)" class="dropdown-item d-flex align-items-center" role="button">
                                            <i class="ri-tools-fill me-2"></i> Fulfill (Add Record)
                                        </a>
                                    </li>
                                    <li>
                                        <a @click="$emit('edit', list)" class="dropdown-item d-flex align-items-center" role="button">
                                            <i class="ri-edit-2-fill me-2"></i> Update
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a @click="remove(list)" class="dropdown-item d-flex align-items-center" role="button">
                                            <i class="ri-delete-bin-fill me-2"></i> Delete
                                        </a>
                                    </li>
                                </BDropdown>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No records found.</td>
                    </tr>
                </tbody>
            </table>
        </simplebar>
    </div>
</template>
<script>
import simplebar from "simplebar-vue";
import { router } from '@inertiajs/vue3';
export default {
    components: { simplebar },
    props: ['dropdowns','maintainable'],
    computed: {
        lists(){
            return this.maintainable.maintenance_requests || [];
        }
    },
    methods: {
        remove(request){
            if(!confirm('Delete this maintenance request? This cannot be undone.')){ return; }
            router.delete(`/maintenance/requests/${request.id}`,{
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    const index = this.maintainable.maintenance_requests.findIndex(r => r.id === request.id);
                    if(index > -1){ this.maintainable.maintenance_requests.splice(index,1); }
                },
            });
        }
    }
}
</script>
