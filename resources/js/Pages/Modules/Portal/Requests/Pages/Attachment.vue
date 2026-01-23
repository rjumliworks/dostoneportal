<template>
    <div class="table-responsive table-card" style="height: calc(100vh - 535px);">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="bg-primary text-white">
                <tr class="fs-10">
                    <th style="width: 7%;">#</th>
                    <th>Type</th>
                    <th style="width: 20%;" class="text-center">Status</th>
                    <th style="width: 15%;" class="text-center"></th>
                </tr>
            </thead>
            <tbody class="table-white fs-12">
                <!-- <tr v-for="(list, index) in information.credits" :key="index" class="text-dark">
                    <template v-if="information.status.name !== 'Disapproved' ? !list.is_returned : list.is_returned">
                        <td class="text-center">{{ list.credit.leave.name }}</td>
                        <td class="text-center">{{ list.log.old_balance }}</td>
                        <td class="text-center">{{ list.log.amount }}</td>
                        <td class="text-center">{{ list.log.new_balance }}</td>
                        <td class="text-center">
                            <span v-if="information.status.name === 'Disapproved'" class="badge bg-warning">Returned</span>
                            <span v-else-if="!list.is_borrowed" class="badge bg-success">Deducted</span>
                            <span v-else class="badge bg-danger">Borrowed</span>
                        </td>
                    </template>
                </tr> -->
            </tbody>
        </table>
    </div>
    <div class="card-footer table-card">
        <div class="d-flex">
            <div class="flex-grow-1 text-muted">
               0 of 5 documents uploaded
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex flex-wrap">
                    <b-button @click="openAdd()" class="btn-sm" variant="danger" block><i class="ri-add-circle-fill me-1"></i>Add Document</b-button>
                </div>
            </div>
        </div>
    </div>
    <Document :attachments="attachments" ref="document"/>
</template>
<script>
import Document from '../Modals/Document.vue';
import simplebar from "simplebar-vue";
export default {
    components: { simplebar, Document },
    props: ['information','attachments'],
    methods: {
        openAdd(){
            this.$refs.document.show(this.information.key);
        }
   }
}
</script>