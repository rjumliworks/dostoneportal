<template>
<div class="card bg-light-subtle shadow-none border">
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-book-open-fill text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Learning & Development</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">L&D interventions / training programs attended.</p>
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
                        <th>Title</th>
                        <th class="text-center">Inclusive Dates</th>
                        <th class="text-center">Hours</th>
                        <th class="text-center">Type</th>
                        <th>Sponsored By</th>
                        <th style="width: 8%;"></th>
                    </tr>
                </thead>
                <tbody class="fs-12" v-if="lists.length > 0">
                    <tr v-for="(list,index) in lists" v-bind:key="index">
                        <td>{{ list.title }}</td>
                        <td class="text-center">{{ list.start_at }} - {{ list.end_at || '-' }}</td>
                        <td class="text-center">{{ list.hours || '-' }}</td>
                        <td class="text-center">{{ list.type || '-' }}</td>
                        <td>{{ list.sponsored_by || '-' }}</td>
                        <td class="text-end">
                            <b-button @click="openEdit(list)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm" class="me-1">
                                <i class="ri-pencil-fill align-bottom"></i>
                            </b-button>
                            <b-button @click="remove(list)" variant="soft-danger" v-b-tooltip.hover title="Delete" size="sm">
                                <i class="ri-delete-bin-fill align-bottom"></i>
                            </b-button>
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
import Modal from './Modals/Training.vue';
export default {
    components: { Modal },
    props: ['lists'],
    methods: {
        openCreate(){
            this.$refs.modal.show();
        },
        openEdit(data){
            this.$refs.modal.edit(data);
        },
        remove(data){
            if(!confirm('Remove this training record?')) return;
            router.delete('/profile/pds/'+data.id, {
                data: { option: 'training' },
                preserveScroll: true,
                onSuccess: () => this.refresh(),
            });
        },
        refresh(){
            router.reload({ only: ['trainings'], preserveScroll: true });
        }
    }
}
</script>
