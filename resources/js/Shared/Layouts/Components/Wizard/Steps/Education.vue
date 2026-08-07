<template>
    <div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fs-14 text-primary mb-0">Educational Background</h5>
            <b-button variant="primary" size="sm" type="button" @click="$refs.modal.show()"><i class="ri-add-circle-fill align-bottom me-1"></i> Add</b-button>
        </div>
        <div class="table-responsive table-card">
            <table class="table align-middle table-striped table-centered mb-0">
                <thead class="table-primary thead-fixed">
                    <tr class="fs-11">
                        <th>School</th>
                        <th class="text-center">Level</th>
                        <th class="text-center">Period</th>
                        <th class="text-center">Year Graduated</th>
                        <th style="width: 8%;"></th>
                    </tr>
                </thead>
                <tbody class="fs-12" v-if="rows.length > 0">
                    <tr v-for="row in rows" :key="row.id">
                        <td>
                            <h5 class="fs-13 mb-0 fw-semibold text-primary">{{ row.school?.name }}</h5>
                            <p class="fs-12 text-muted mb-0">{{ row.course?.name }}</p>
                        </td>
                        <td class="text-center">{{ row.level?.name }}</td>
                        <td class="text-center">{{ row.attended_from || '-' }} - {{ row.attended_to || '-' }}</td>
                        <td class="text-center">
                            <span v-if="row.is_ongoing" class="badge bg-warning">Ongoing</span>
                            <span v-else>{{ row.graduated_at }}</span>
                        </td>
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
        <Modal :levels="dropdowns.levels" ref="modal" @success="$emit('refresh')"/>
    </div>
</template>
<script>
import { router } from '@inertiajs/vue3';
import Modal from '@/Pages/Auth/Profile/Pages/Modals/Academic.vue';
export default {
    components: { Modal },
    props: ['data', 'dropdowns'],
    computed: {
        rows(){ return this.data.academics || []; }
    },
    methods: {
        remove(row){
            if (!confirm('Remove this education record?')) return;
            router.delete('/profile/pds/'+row.id, {
                data: { option: 'academic' },
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
