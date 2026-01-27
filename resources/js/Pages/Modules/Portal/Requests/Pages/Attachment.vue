<template>
    <div class="table-responsive table-card" style="height: calc(100vh - 535px);">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="bg-primary text-white">
                <tr class="fs-10">
                    <th style="width: 5%;">#</th>
                    <th>Type</th>
                    <th style="width: 20%;" class="text-center">Date Created</th>
                    <th style="width: 20%;" class="text-center">Status</th>
                    <th style="width: 5%;" class="text-center"></th>
                </tr>
            </thead>
            <tbody class="table-white fs-12">
                <tr v-for="(list, index) in information.documents" :key="index" class="text-dark">
                    <td>{{ index + 1 }}</td>
                    <td>{{ list.type.name }}</td>
                    <td>{{ list.created_at }}</td>
                    <td class="text-center"><span :class="'badge '+list.status.bg">{{list.status.name}}</span></td>
                    <td class="text-center">
                        <div class="d-flex gap-3 justify-content-center"> 
                            <div class="dropdown">
                                <BDropdown variant="link" toggle-class="btn btn-light btn-sm dropdown" no-caret menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }"> 
                                    <template #button-content> 
                                        <i class="ri-more-fill"></i>
                                    </template>
                                    <li>
                                        <a @click="openRole(list,index)" class="dropdown-item d-flex align-items-center" role="button">
                                            <i class="ri-printer-line me-2"></i> Print
                                        </a>
                                    </li>
                                    <li>
                                        <a @click="openUpdate(list,index)" class="dropdown-item d-flex align-items-center" role="button">
                                            <i class="ri-edit-2-fill me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a @click="openUpdate(list,index)" class="dropdown-item d-flex align-items-center" role="button">
                                            <span class="text-danger"><i class="ri-delete-bin-line me-2"></i> Delete</span>
                                        </a>
                                    </li>
                                </BDropdown>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer table-card">
        <div class="d-flex">
            <div class="flex-grow-1 text-muted">
               {{ information.documents.length }} of 5 documents uploaded
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