<template>
    <div class="d-flex justify-content-end gap-2 mb-2">
        <a :href="`/maintenance/${maintainableType}/${maintainable.id}/print`" target="_blank" class="btn btn-light btn-sm" title="Print / View as PDF">
            <i class="ri-printer-fill align-bottom me-1"></i> Print
        </a>
        <b-button type="button" variant="primary" size="sm" @click="$emit('create')">
            <i class="ri-add-circle-fill align-bottom me-1"></i> Add Record
        </b-button>
    </div>
    <div class="table-responsive table-card">
        <simplebar data-simplebar style="height: calc(100vh - 470px);">
            <table class="table table-nowrap align-middle mb-0">
                <thead class="table-light thead-fixed">
                    <tr class="fs-11">
                        <th style="width: 4%;" class="text-center">#</th>
                        <th style="width: 10%;" class="text-center">Date</th>
                        <th>Operation Performed</th>
                        <th style="width: 12%;" class="text-center">Type</th>
                        <th style="width: 13%;" class="text-center">Performed By</th>
                        <th style="width: 9%;" class="text-center">Cost</th>
                        <th style="width: 10%;" class="text-center">Status</th>
                        <th style="width: 9%;" class="text-center">Source</th>
                        <th style="width: 6%;"></th>
                    </tr>
                </thead>
                <tbody v-if="lists.length > 0">
                    <tr class="fs-12" v-for="(list,index) in lists" v-bind:key="index">
                        <td class="text-center">{{ index+1 }}</td>
                        <td class="text-center text-muted">{{ list.date }}</td>
                        <td>
                            <p class="mb-0">{{ list.operation_performed }}</p>
                            <p v-if="list.attachment" class="fs-11 mb-0">
                                <a :href="`/storage/${list.attachment}`" target="_blank"><i class="ri-attachment-2 align-bottom"></i> View attachment</a>
                            </p>
                        </td>
                        <td class="text-center text-muted">{{ list.type?.name || '-' }}</td>
                        <td class="text-center text-muted">{{ list.performer?.profile?.fullname || '-' }}</td>
                        <td class="text-center text-muted">{{ list.cost ? Number(list.cost).toLocaleString('en-US',{minimumFractionDigits:2}) : '-' }}</td>
                        <td class="text-center">
                            <span v-if="list.status" :class="'badge '+list.status.bg+' '+list.status.type">{{ list.status.name }}</span>
                            <span v-else class="text-muted">-</span>
                        </td>
                        <td class="text-center">
                            <span v-if="list.request_id" class="badge bg-info-subtle text-info" v-b-tooltip.hover title="Fulfills a maintenance request">
                                <i class="ri-file-list-3-line align-bottom"></i> Requested
                            </span>
                            <span v-else class="badge bg-light text-muted">
                                <i class="ri-calendar-event-line align-bottom"></i> Scheduled
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <BDropdown variant="link" toggle-class="btn btn-light btn-sm dropdown" no-caret menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }">
                                    <template #button-content>
                                        <i class="ri-more-fill"></i>
                                    </template>
                                    <li>
                                        <a @click="$emit('edit', list)" class="dropdown-item d-flex align-items-center" role="button">
                                            <i class="ri-edit-2-fill me-2"></i> Update
                                        </a>
                                    </li>
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
                        <td colspan="9" class="text-center text-muted">No records found.</td>
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
    props: ['dropdowns','maintainable','maintainableType'],
    computed: {
        lists(){
            return this.maintainable.records || [];
        }
    },
    methods: {
        remove(record){
            if(!confirm('Delete this maintenance record? This cannot be undone.')){ return; }
            router.delete(`/maintenance/records/${record.id}`,{
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    const index = this.maintainable.records.findIndex(r => r.id === record.id);
                    if(index > -1){ this.maintainable.records.splice(index,1); }
                },
            });
        }
    }
}
</script>
