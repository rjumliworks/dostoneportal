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
                                          
                                            <BCol md>
                                                <div>
                                                    <h4 class="fw-bold">asda </h4>
                                                    <div class="hstack gap-3 flex-wrap">
                                                        <div><i class="ri-qr-code-fill align-bottom me-1"></i> {{information.code}}</div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Date Created : </span> <span class="fw-medium">{{information.created_at}}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Created By : </span> <span class="fw-medium">{{information.employee}}</span></div>
                                                        <div class="vr"></div>
                                                    </div>
                                                </div>
                                            </BCol>
                                        </BRow>
                                    </BCol>
                                    <BCol md="auto">
                                        <div class="hstack gap-4 flex-wrap mt-2">
                                            <Link href="/approvals">
                                                <div class="text-muted" @click="hide()">  
                                                    <i class="ri-close-circle-fill fs-16"></i> Close
                                                </div>
                                            </Link>
                                            <template v-if="information_data.data.status.name == 'Pending' && $page.props.user.data.signatory.designationable.designation_id == 44">
                                                <div class="vr" style="width: 1px;"></div>
                                                <div class="me-n3" @click="openDisapprove(information.key,information.type,information.request_key)">  
                                                    <b-button variant="danger" block><i class="ri-close-circle-fill me-1"></i>Disapprove</b-button>
                                                </div>
                                                <div @click="openRecommend(information.key,information.type,information.request_key)">  
                                                    <b-button variant="secondary" block><i class="ri-checkbox-circle-fill me-1"></i>Recommend</b-button>
                                                </div>
                                            </template>
                                            <template  v-if="information_data.data.status.name == 'Recommended' && $page.props.user.data.signatory.designationable.designation_id == 43">
                                                <div class="vr" style="width: 1px;"></div>
                                                <div @click="openApprove(information.key,information.type,information.request_key)">  
                                                    <b-button variant="success" block><i class="ri-checkbox-circle-fill me-1"></i>Approve</b-button>
                                                </div>
                                            </template>
                                        </div>
                                    </BCol>
                                </BRow>
                            </BCardBody>
                        </div>
                    </BCard>
                </BCol>
                <BCol lg="6">
                    <Sidebar :information="information_data.data"/>
                </BCol>
                <BCol lg="6">
                    <Main :information="information_data.data"/>
                </BCol>
            </BRow>
        </div>
    </div>
    <Approved ref="approve"/>
    <Recommend ref="recommend"/>
    <Disapproved ref="disapprove"/>
</template>
<script>
import Approved from '../../Modals/Approved.vue';
import Recommend from '../../Modals/Recommend.vue';
import Disapproved from '../../Modals/Disapproved.vue';
import Main from './Components/Main.vue';
import Sidebar from './Components/Sidebar.vue';
export default {
    props: ['information_data'],
    components: { Main, Sidebar, Approved, Recommend, Disapproved },
    data(){
        return {
            information: this.information_data.data
        }
    },
    methods: {
        openApprove(id,type,request_id){
            this.$refs.approve.show(id,type,request_id);
        },
        openRecommend(id,type,request_id){
            this.$refs.recommend.show(id,type,request_id);
        },
        openDisapprove(id,type,request_id){
            this.$refs.disapprove.show(id,type,request_id);
        },
        back(){
            this.$inertia.visit('/travels');
        }
    }
}
</script>