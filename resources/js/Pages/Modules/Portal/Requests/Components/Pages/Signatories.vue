<template>
    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 522px); overflow: auto;">
        
        <div class="table-responsive table-card">
            <table class="table align-middle table-bordered table-striped table-centered mb-0">
                <thead class="table-light thead-fixed">
                    <tr class="fs-11">
                        <th style="width: 7%;" class="text-center">#</th>
                        <th>Division</th>
                        <th style="width: 15%;" class="text-center">Approval</th>
                        <th style="width: 15%;" class="text-center">Recommending</th>
                        <th style="width: 18%;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="table-white fs-12">
                    <tr v-for="(list,index) in information.signatories" v-bind:key="index" >
                        <td class="text-center">{{ index+1 }}</td>
                        <td>{{ list.division.name }}</td>
                        <td class="text-center">
                            <i v-if="list.approved" class="text-success fs-14 ri-checkbox-circle-fill"></i>
                            <i v-else class="text-danger fs-14 ri-close-circle-fill"></i>
                        </td>
                        <td class="text-center">
                            <i v-if="list.approved" class="text-success fs-14 ri-checkbox-circle-fill"></i>
                            <i v-else-if="list.is_approval_only" class="text-dark fs-14 ri-indeterminate-circle-fill"></i>
                            <i v-else-if="!list.recommended" class="text-danger fs-14 ri-close-circle-fill"></i>
                        </td>
                        <td class="text-center">
                            <b-badge v-if="list.is_completed" variant="success">Completed</b-badge>
                            <b-badge v-else variant="warning">Incomplete</b-badge>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
            
    </div>
</template>
<script>
import simplebar from "simplebar-vue";
export default {
    components: { simplebar },
    props: ['information'],
   data(){
       return {
           filter: {
               keyword: null
           }
       }
   },
   methods: {
       openCreate(){
           this.$refs.create.show(this.id);
       },
       openCertification(data,id,course){
           if(data.length > 0){
               
           }else{
               this.$refs.certification.show(id,course);
           }
       }   
   }
}
</script>