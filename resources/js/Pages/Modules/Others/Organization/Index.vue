<template>

    <Head title="Key Officials" />
    <PageHeader title="Signatory Management" pageTitle="List" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-mark-pen-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">List of Signatories</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">A comprehensive list of campuses from
                                various schools, providing location and institutional details</p>
                        </div>
                        <!-- <div class="flex-shrink-0" style="width: 45%;">

                        </div> -->
                    </div>
                </div>


                <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 295px); overflow: auto;">
                    <div class="container text-center">
                        <!-- Level 1 -->
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-3" style="cursor: pointer;" @click="openView(designations.data[0])">
                                <div class="card-body border rounded-4 text-center">
                                    <div class="mb-2 mx-auto">
                                        <img :src="(designations.data[0].is_oic) ? designations.data[0].oic_avatar : designations.data[0].avatar" alt="" id="candidate-img" class="img-thumbnail avatar-sm rounded-circle shadow-none">
                                    </div>
                                    <h5 v-if="designations.data[0].oic" class="fs-12 mb-0 text-warning fw-semibold">{{designations.data[0].oic.name}}</h5>
                                    <h5 v-else-if="designations.data[0].user" class="fs-12 mb-0 text-primary fw-semibold">{{designations.data[0].user.name}}</h5>
                                    <h5 v-else class="fs-12 text-warning mb-0">Not Assigned</h5>
                                    <p class="fs-12 text-muted mb-0">{{designations.data[0].designation}}</p>
                                    <p v-if="designations.data[0].is_oic" class="fs-11 mt-1 mb-0">(Office-in-Charge)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Level 2 -->
                        <div class="row justify-content-center mb-4">
                            <div class="col-md-3" style="cursor: pointer;" @click="openView(designations.data[1])">
                                <div class="card-body border rounded-4 text-center">
                                    <div class="mb-2 mx-auto">
                                        <img :src="(designations.data[1].is_oic) ? designations.data[1].oic_avatar : designations.data[1].avatar" alt="" id="candidate-img" class="avatar-sm img-thumbnail rounded-circle shadow-none">
                                    </div>
                    
                                    <h5 v-if="designations.data[1].oic" class="fs-12 mb-0 text-warning fw-semibold">{{designations.data[1].oic.name}}</h5>
                                    <h5 v-else-if="designations.data[1].user" class="fs-12 mb-0 text-primary fw-semibold">{{designations.data[1].user.name}}</h5>
                                    <h5 v-else class="fs-12 text-warning mb-0">Not Assigned</h5>
                                    <p class="fs-12 text-muted mb-0"><span v-if="designations.data[1].is_oic">OIC - </span>{{designations.data[1].designation}}</p>
                                    <p class="fs-11 mt-1 mb-0">({{designations.data[1].assigned.others}})</p>
                                    <!-- <p v-if="designations.data[1].is_oic" class="fs-11 mt-1 mb-0">(Office-in-Charge)</p> -->
                                </div>
                            </div>
                            <div class="col-md-3" style="cursor: pointer;" @click="openView(designations.data[2])">
                                <div class="card-body border rounded-4 text-center">
                                    <div class="mb-2 mx-auto">
                                       <img :src="(designations.data[2].is_oic) ? designations.data[2].oic_avatar : designations.data[2].avatar" alt="" id="candidate-img" class="avatar-sm img-thumbnail rounded-circle shadow-none">
                                    </div>
                              
                                    <h5 v-if="designations.data[2].oic" class="fs-12 mb-0 text-warning fw-semibold">{{designations.data[2].oic.name}}</h5>
                                    <h5 v-else-if="designations.data[2].user" class="fs-12 mb-0 text-primary fw-semibold">{{designations.data[2].user.name}}</h5>
                                    <h5 v-else class="fs-12 text-warning mb-0">Not Assigned</h5>
                                    <p class="fs-12 text-muted mb-0"><span v-if="designations.data[2].is_oic">OIC - </span>{{designations.data[2].designation}}</p>
                                    <p class="fs-11 mt-1 mb-0">({{designations.data[2].assigned.others}})</p>
                                    <!-- <p v-if="designations.data[2].is_oic" class="fs-11 mt-1 mb-0">(Office-in-Charge)</p> -->
                                </div>
                            </div>
                        </div>

                        <!-- Level 3 -->
                        <div class="row justify-content-center">
                            <div class="col-md-3" style="cursor: pointer;" @click="openView(designations.data[3])">
                                <div class="card-body border rounded-4 text-center">
                                    <div class="mb-2 mx-auto">
                                        <img :src="(designations.data[3].is_oic) ? designations.data[3].oic_avatar : designations.data[3].avatar" alt="" id="candidate-img" class="avatar-sm img-thumbnail rounded-circle shadow-none">
                                    </div>
                    
                                    <h5 v-if="designations.data[3].oic" class="fs-12 mb-0 text-warning fw-semibold">{{designations.data[3].oic.name}}</h5>
                                    <h5 v-else-if="designations.data[3].user" class="fs-12 mb-0 text-primary fw-semibold">{{designations.data[3].user.name}}</h5>
                                    <h5 v-else class="fs-12 text-warning mb-0">Not Assigned</h5>
                                    <p class="fs-12 text-muted mb-0"><span v-if="designations.data[3].is_oic">OIC - </span>{{designations.data[3].designation}}</p>
                                    <p class="fs-11 mt-1 mb-0">({{designations.data[3].assigned.others}})</p>
                                    <!-- <p v-if="designations.data[3].is_oic" class="fs-11 mt-1 mb-0">(Office-in-Charge)</p> -->
                                </div>
                            </div>
                            <div class="col-md-3" style="cursor: pointer;" @click="openView(designations.data[4])">
                                <div class="card-body border rounded-4 text-center">
                                    <div class="mb-2 mx-auto">
                                        <img :src="(designations.data[4].is_oic) ? designations.data[4].oic_avatar : designations.data[4].avatar" alt="" id="candidate-img" class="avatar-sm img-thumbnail rounded-circle shadow-none">
                                    </div>
                    
                                    <h5 v-if="designations.data[4].oic" class="fs-12 mb-0 text-warning fw-semibold">{{designations.data[4].oic.name}}</h5>
                                    <h5 v-else-if="designations.data[4].user" class="fs-12 mb-0 text-primary fw-semibold">{{designations.data[4].user.name}}</h5>
                                    <h5 v-else class="fs-12 text-warning mb-0">Not Assigned</h5>
                                    <p class="fs-12 text-muted mb-0"><span v-if="designations.data[4].is_oic">OIC - </span>{{designations.data[4].designation}}</p>
                                    <p class="fs-11 mt-1 mb-0">({{designations.data[4].assigned.others}})</p>
                                    <!-- <p v-if="designations.data[4].is_oic" class="fs-11 mt-1 mb-0">(Office-in-Charge)</p> -->
                                </div>
                            </div>
                            <div class="col-md-3" style="cursor: pointer;" @click="openView(designations.data[5])">
                                <div class="card-body border rounded-4 text-center">
                                    <div class="mb-2 mx-auto">
                                        <img :src="(designations.data[5].is_oic) ? designations.data[5].oic_avatar : designations.data[5].avatar" alt="" id="candidate-img" class="avatar-sm img-thumbnail rounded-circle shadow-none">
                                    </div>
                    
                                    <h5 v-if="designations.data[5].oic" class="fs-12 mb-0 text-warning fw-semibold">{{designations.data[5].oic.name}}</h5>
                                    <h5 v-else-if="designations.data[5].user" class="fs-12 mb-0 text-primary fw-semibold">{{designations.data[5].user.name}}</h5>
                                    <h5 v-else class="fs-12 text-warning mb-0">Not Assigned</h5>
                                    <p class="fs-12 text-muted mb-0"><span v-if="designations.data[5].is_oic">OIC - </span>{{designations.data[5].designation}}</p>
                                    <p class="fs-11 mt-1 mb-0">({{designations.data[5].assigned.others}})</p>
                                    <!-- <p v-if="designations.data[4].is_oic" class="fs-11 mt-1 mb-0">(Office-in-Charge)</p> -->
                                </div>
                            </div>
                            <div class="col-md-3" style="cursor: pointer;" @click="openView(designations.data[6])">
                                <div class="card-body border rounded-4 text-center">
                                    <div class="mb-2 mx-auto">
                                        <img :src="(designations.data[6].is_oic) ? designations.data[6].oic_avatar : designations.data[6].avatar" alt="" id="candidate-img" class="avatar-sm img-thumbnail rounded-circle shadow-none">
                                    </div>
                    
                                    <h5 v-if="designations.data[6].oic" class="fs-12 mb-0 text-warning fw-semibold">{{designations.data[6].oic.name}}</h5>
                                    <h5 v-else-if="designations.data[6].user" class="fs-12 mb-0 text-primary fw-semibold">{{designations.data[6].user.name}}</h5>
                                    <h5 v-else class="fs-12 text-warning mb-0">Not Assigned</h5>
                                    <p class="fs-12 text-muted mb-0"><span v-if="designations.data[6].is_oic">OIC - </span>{{designations.data[6].designation}}</p>
                                    <p class="fs-11 mt-1 mb-0">({{designations.data[6].assigned.others}})</p>
                                    <!-- <p v-if="designations.data[6].is_oic" class="fs-11 mt-1 mb-0">(Office-in-Charge)</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BRow>
    <View ref="view"/>
</template>
<script>
import View from './Modals/View.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['designations'],
    components: { PageHeader, View },
    data() {
        return {
            
        }
    },
    methods: {
        openView(data){
            this.$refs.view.show(data);
        }
    }
}
</script>
