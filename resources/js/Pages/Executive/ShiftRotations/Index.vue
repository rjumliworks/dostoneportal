<template>
    <Head title="Guard Shift Rotation"/>
    <PageHeader title="Guard Shift Rotation" pageTitle="List" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-shield-user-line text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">Security Guard Rotation</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Guards in this list are automatically rotated weekly (Morning &rarr; Afternoon &rarr; Night &rarr; Morning). Add, edit, or remove a guard here when the roster changes.</p>
                        </div>
                    </div>
                </div>
                <div class="car-body bg-white border-bottom shadow-none">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg class="d-flex justify-content-end">
                            <b-button type="button" variant="primary" @click="openCreate">
                                <i class="ri-add-circle-fill align-bottom me-1"></i> Add Guard
                            </b-button>
                        </b-col>
                    </b-row>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th style="width: 6%;" class="text-center">Order</th>
                                    <th>Guard</th>
                                    <th class="text-center">Current Shift</th>
                                    <th style="width: 10%;" class="text-center">Status</th>
                                    <th style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody v-if="lists.data.length" class="table-white fs-12">
                                <tr v-for="list in lists.data" :key="list.id">
                                    <td class="text-center">{{ list.order }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img :src="list.user.avatar" class="rounded-circle avatar-xxs me-2" alt="">
                                            <div>
                                                <h5 class="fs-13 mb-0 fw-semibold text-primary text-uppercase">{{ list.user.name }}</h5>
                                                <p class="fs-11 text-muted mb-0">{{ list.user.position }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ list.current_shift ?? '-' }}</td>
                                    <td class="text-center">
                                        <span v-if="list.is_active" class="badge bg-success">Active</span>
                                        <span v-else class="badge bg-danger">Inactive</span>
                                    </td>
                                    <td class="text-end">
                                        <b-button @click="openEdit(list)" variant="soft-warning" class="me-1" v-b-tooltip.hover title="Edit" size="sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </b-button>
                                        <b-button @click="remove(list.id)" variant="soft-danger" v-b-tooltip.hover title="Remove" size="sm">
                                            <i class="ri-delete-bin-fill align-bottom"></i>
                                        </b-button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr><td colspan="5" class="text-center text-muted">No guards in the rotation yet.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BRow>
    <Create :guards="guards" ref="create"/>
</template>
<script>
import { router } from '@inertiajs/vue3';
import Create from './Modals/Create.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Create },
    props: ['lists','guards'],
    methods: {
        openCreate(){
            this.$refs.create.show();
        },
        openEdit(data){
            this.$refs.create.edit(data);
        },
        remove(id){
            if (confirm('Remove this guard from the rotation?')) {
                router.delete(`/shift-rotations/${id}`, { preserveScroll: true });
            }
        }
    }
}
</script>
