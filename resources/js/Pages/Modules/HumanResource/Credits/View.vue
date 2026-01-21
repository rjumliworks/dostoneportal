<template>
    <Head title="Employee Profile" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="w-100 p-4 pb-0" ref="box">
            <BRow>
                <BCol lg="12">
                    <BCard no-body class="mt-n4 mx-n4">
                        <div class="bg-info-subtle">
                            <BCardBody class="pb-0 px-4">
                                <BRow class="mb-3">
                                    <BCol md>
                                        <BRow class="align-items-center g-3">
                                            <BCol md="auto">
                                                <div style="height: 3.5rem; width: 3.5rem;">
                                                    <div class="avatar-title bg-white rounded-circle">
                                                        <img :src="information.avatar" alt="" class="avatar-sm rounded-circle">
                                                    </div>
                                                </div>
                                            </BCol>
                                            <BCol md>
                                                <div>
                                                    <h4 class="fw-bold text-uppercase">{{information.profile.name}}</h4>
                                                    <div class="hstack gap-3 flex-wrap">
                                                        <div><span class="text-muted">Position : </span> <span class="fw-medium">{{information.organization.position.name}}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Status : </span> <span class="fw-medium">{{information.organization.status.name}}</span></div>
                                                        <!-- <div class="vr" style="width: 1px;"></div> -->
                                                        <!-- <div><i class="ri-qr-code-fill align-bottom me-1"></i> {{information.code}}</div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Date Created : </span> <span class="fw-medium">{{information.created_at}}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Created By : </span> <span class="fw-medium">{{information.employee}}</span></div>
                                                        <div class="vr"></div> -->
                                                    </div>
                                                </div>
                                            </BCol>
                                        </BRow>
                                    </BCol>
                                    <BCol md="auto">
                                        <div class="hstack gap-4 flex-wrap mt-2">
                                            
                                            <div class="text-muted" @click="back()" style="cursor: pointer;">  
                                                <i class="ri-close-circle-fill fs-16"></i> Close
                                            </div>
                                            <!-- <template v-if="information_data.data.status.name == 'Pending' && $page.props.user.data.signatory.designation_id == 44">
                                                <div class="vr" style="width: 1px;"></div>
                                                <div class="me-n3" @click="openDisapprove(information.request_key,information.type)">  
                                                    <b-button variant="danger" block><i class="ri-close-circle-fill me-1"></i>Disapprove</b-button>
                                                </div>
                                                <div @click="openRecommend(information.request_key,information.type)">  
                                                    <b-button variant="secondary" block><i class="ri-checkbox-circle-fill me-1"></i>Recommend</b-button>
                                                </div>
                                            </template>
                                            <template  v-if="information_data.data.status.name == 'Recommended' && $page.props.user.data.signatory.designation_id == 43">
                                                <div class="vr" style="width: 1px;"></div>
                                                <div @click="openApprove(information.request_key,information.type)">  
                                                    <b-button variant="success" block><i class="ri-checkbox-circle-fill me-1"></i>Approve</b-button>
                                                </div>
                                            </template> -->
                                        </div>
                                    </BCol>
                                </BRow>
                            </BCardBody>
                        </div>
                    </BCard>
                </BCol>
                <BCol lg="6">
                    <Sidebar @openhistory="openHistory" :credits="information_data.data.credits"/>
                </BCol>
                <BCol lg="6">
                    <Main :information="information_data.data" ref="main"/>
                </BCol>
            </BRow>
        </div>
    </div>
</template>
<script>
import Main from './Components/Main.vue';
import Sidebar from './Components/Sidebar.vue';
export default {
    props: ['information_data'],
    components: { Main, Sidebar },
    data(){
        return {
            information: this.information_data.data
        }
    },
    methods: {
        openHistory(data){
            this.$refs.main.openView(data);
        },
        back(){
            this.$inertia.visit('/credits');
        }
    }
}
</script>