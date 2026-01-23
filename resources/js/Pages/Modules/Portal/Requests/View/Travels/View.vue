<template>
    <Head title="Travel Order" />
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
                                                    <h4 class="fw-bold">{{ information.location.name }} </h4>
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
                                            <Link href="/requests">
                                                <div class="text-muted">  
                                                    <i class="ri-close-circle-fill fs-16"></i> Close
                                                </div>
                                            </Link>
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
                    <Main :information="information_data.data" :attachments="attachments"/>
                </BCol>
            </BRow>
        </div>
    </div>
    <Edit ref="edit"/>
    <Approved ref="approve"/>
    <Recommend ref="recommend"/>
    <Disapproved ref="disapprove"/>
</template>
<script>
import Main from './Components/Main.vue';
import Edit from './Modals/Edit.vue';
import Approved from './Modals/Approved.vue';
import Recommend from './Modals/Recommend.vue';
import Disapproved from './Modals/Disapproved.vue';
import Sidebar from './Components/Sidebar.vue';
export default {
    props: ['information_data','attachments'],
    components: { Main, Sidebar, Edit, Approved, Disapproved, Recommend },
    data(){
        return {
            information: this.information_data.data
        }
    },
    methods: {
        back(){
            this.$inertia.visit('/travels');
        },
        openPrint(id){
            window.open('/travels?option=print&id='+id);
        },
        openEdit(selected){
            this.$refs.edit.show(selected);
        },
        openApprove(id,type){
            this.$refs.approve.show(id,type);
        },
        openRecommend(id,type){
            this.$refs.recommend.show(id,type);
        },
        openDisapprove(id,type){
            this.$refs.disapprove.show(id,type);
        }
    }
}
</script>