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
                                                    <h4 class="fw-bold">{{ information.title }}</h4>
                                                    <div class="hstack gap-3 flex-wrap">
                                                        <div><i class="ri-qr-code-fill align-bottom me-1"></i> {{information.code}}</div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Type : </span> <span class="fw-medium">{{information.type.name}}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Mode : </span> <span class="fw-medium">{{information.mode.name}}</span></div>
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
                                           
                                        </div>
                                    </BCol>
                                </BRow>
                            </BCardBody>
                        </div>
                    </BCard>
                </BCol>
                <BCol lg="12">
                    <List :information="information" ref="main"/>
                </BCol>
            </BRow>
        </div>
    </div>
</template>
<script>
import List from './Components/List.vue';
export default {
    components: { List },
    props: ['information_data'],
    data(){
        return {
            information: this.information_data.data
        }
    },
    methods: {
        openApprove(id,type){
            this.$refs.approve.show(id,type);
        },
        openRecommend(id,type){
            this.$refs.recommend.show(id,type);
        },
        openDisapprove(id,type){
            this.$refs.disapprove.show(id,type);
        },
        back(){
            this.$inertia.visit('/approvals');
        }
    }
}
</script>