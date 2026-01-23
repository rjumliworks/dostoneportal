<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-pin-distance-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Render Overtime Service </span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Detailed overview of the Travel Order request</p>
                </div>
            </div>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 342px); overflow: hidden;">
            
            <div class="row g-3 p-3">
                <div class="col-md-6">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                               <img :src="information.tags[0].avatar" alt="" class="rounded-circle avatar-xs">
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Employee :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0">{{information.tags[0].name}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-todo-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Date(s) :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{formatDateRange(information.start, information.end)}} </h6>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-12" v-if="information.status.name == 'Pending' || information.status.name == 'Recommended'">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-file-text-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Purpose :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ information.purpose }} </h6>
                        </div>
                    </div>
                </div> -->
            </div>

            <!-- <template v-if="information.status.name == 'Approved'">
                <hr class="text-muted mt-0"/>
                    <div class="d-flex mb-n2 ms-3 me-3">
                    <div class="flex-shrink-0 me-3">
                        <p class="mb-0 text-primary fs-12 fw-semibold">Individual Accomplishment Report</p>
                    </div>
                    <div class="flex-grow-1 mt-n1">
                        <i @click="openAdd(information.overtime.id)" class="ri-add-circle-fill float-end text-muted fs-20" style="cursor: pointer;"></i>
                    </div>
                </div>
                <hr class="text-muted mb-0"/>
            </template> -->

            <div v-if="information.overtime" class="row g-3 p-3">
                <div class="col-md-4">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                               <i class="ri-information-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Status :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0" :class="information.overtime.status.others">{{information.overtime.status.name}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                               <i class="ri-hashtag"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">RROS No. :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0">{{information.overtime.code}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-asterisk"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">COC Earned :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0">{{(information.overtime.earned) ? information.overtime.earned : '-'}} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive bg-white" style="height: calc(100vh - 610px); overflow: auto;">
                        <table class="table align-middle table-bordered table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th style="width: 50%;" class="text-center">Target Deliverables</th>
                                    <th style="width: 50%;" class="text-center">Actual Accomplshments</th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-for="(list,index) in information.overtime.targets" v-bind:key="index" class="text-dark">
                                    <td class="text-center text-muted fs-11">{{list.target}}</td>
                                    <td class="text-center text-muted fs-11">{{list.accomplishment}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Add @saveTargets="handleSaveTargets" ref="add"/>
</template>
<script>
import Add from './Pages/Add.vue';
export default {
    components: { Add },
    props: ['information'],
    data(){
        return {
            showName: false
        }
    },
    computed: {
        chunkedTags() {
            const tags = this.information.tags || [];
            let chunkSize = 3;

            if (tags.length >= 6) chunkSize = 2;  
            else if (tags.length >= 4) chunkSize = 2; 

            const chunks = [];
            for (let i = 0; i < tags.length; i += chunkSize) {
                chunks.push(tags.slice(i, i + chunkSize));
            }
            return chunks;
        }
    },
    methods: {
        formatDateRange(start, end) {
            const startDate = new Date(start);
            const endDate = new Date(end);

            const options = { month: 'long', day: 'numeric' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            const endStr = endDate.toLocaleDateString('en-US', { day: 'numeric' });

            if (start === end) {
            return startDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            }

            const year = startDate.getFullYear(); 
            return `${startStr}-${endStr}, ${year}`;
        },
        handleSaveTargets({ id, targets }) {
            this.information.overtime.targets = targets;
        },
        openAdd(id){
            this.$refs.add.show(id,this.information.overtime.targets);
        }
    }
}
</script>
<style>
    .my-tooltip-class {
        max-width: 1000px !important;  
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
</style>