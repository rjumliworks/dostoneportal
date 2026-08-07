<template>
    <div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fs-14 text-primary mb-0">Learning & Development / Trainings</h5>
            <b-button variant="primary" size="sm" type="button" @click="$refs.modal.show()"><i class="ri-add-circle-fill align-bottom me-1"></i> Add</b-button>
        </div>
        <div class="table-responsive table-card">
            <table class="table align-middle table-striped table-centered mb-0">
                <thead class="table-primary thead-fixed">
                    <tr class="fs-11">
                        <th>Title</th>
                        <th class="text-center">Period</th>
                        <th class="text-center">Hours</th>
                        <th class="text-center">Type</th>
                        <th style="width: 8%;"></th>
                    </tr>
                </thead>
                <tbody class="fs-12" v-if="rows.length > 0">
                    <tr v-for="row in rows" :key="row.id">
                        <td>
                            <h5 class="fs-13 mb-0 fw-semibold text-primary">{{ row.title }}</h5>
                            <p class="fs-12 text-muted mb-0">{{ row.sponsored_by }}</p>
                        </td>
                        <td class="text-center">{{ row.start_at }} - {{ row.end_at || '-' }}</td>
                        <td class="text-center">{{ row.hours || '-' }}</td>
                        <td class="text-center">{{ row.type || '-' }}</td>
                        <td class="text-end">
                            <b-button @click="$refs.modal.edit(row)" variant="soft-warning" size="sm" class="me-1" type="button"><i class="ri-pencil-fill align-bottom"></i></b-button>
                            <b-button @click="remove(row)" variant="soft-danger" size="sm" type="button"><i class="ri-delete-bin-fill align-bottom"></i></b-button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr><td colspan="5" class="text-center text-muted fs-12 py-3">No records added yet — you can skip this and add it later.</td></tr>
                </tbody>
            </table>
        </div>
        <Modal ref="modal" @success="$emit('refresh')"/>
    </div>
</template>
<script>
import { router } from '@inertiajs/vue3';
import Modal from '@/Pages/Auth/Profile/Pages/Modals/Training.vue';
export default {
    components: { Modal },
    props: ['data'],
    computed: {
        rows(){ return this.data.trainings || []; }
    },
    methods: {
        remove(row){
            if (!confirm('Remove this training record?')) return;
            router.delete('/profile/pds/'+row.id, {
                data: { option: 'training' },
                preserveScroll: true,
                onSuccess: () => this.$emit('refresh'),
            });
        },
        proceed(){
            this.$emit('saved');
        }
    }
}
</script>
