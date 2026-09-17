<template>
    <div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fs-14 text-primary mb-0">References</h5>
            <b-button class="mb-2" variant="primary" size="sm" type="button" @click="$refs.modal.show()"><i class="ri-add-circle-fill align-bottom me-1"></i> Add</b-button>
        </div>
        <div class="table-responsive table-card" style="height: calc(100vh - 460px); overflow: auto;">
            <table class="table align-middle table-striped table-centered mb-0">
                <thead class="table-primary thead-fixed">
                    <tr class="fs-11"><th>Name</th><th>Address</th><th>Contact</th><th style="width: 8%;"></th></tr>
                </thead>
                <tbody class="fs-12" v-if="references.length > 0">
                    <tr v-for="row in references" :key="row.id">
                        <td>{{ row.name }}</td>
                        <td>{{ row.address }}</td>
                        <td>{{ row.contact }}</td>
                        <td class="text-end">
                            <b-button @click="$refs.modal.edit(row)" variant="soft-warning" size="sm" class="me-1" type="button"><i class="ri-pencil-fill align-bottom"></i></b-button>
                            <b-button @click="removeReference(row)" variant="soft-danger" size="sm" type="button"><i class="ri-delete-bin-fill align-bottom"></i></b-button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr><td colspan="4" class="text-center text-muted fs-12 py-3">No references added yet — you can skip this and add it later.</td></tr>
                </tbody>
            </table>
        </div>
        <Modal ref="modal" @success="$emit('refresh')"/>
    </div>
</template>
<script>
import { router } from '@inertiajs/vue3';
import Modal from '@/Pages/Auth/Profile/Pages/Modals/Reference.vue';
export default {
    components: { Modal },
    props: ['data'],
    computed: {
        references(){ return this.data.references || []; },
    },
    methods: {
        removeReference(row){
            if (!confirm('Remove this reference?')) return;
            router.delete('/profile/pds/'+row.id, {
                data: { option: 'reference' },
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
