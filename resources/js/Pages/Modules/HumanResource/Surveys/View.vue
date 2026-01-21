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
                                                    <h4 class="fw-bold">{{ survey.year }} - {{ survey.semester.name }} </h4>
                                                    <div class="hstack gap-3 flex-wrap">
                                                        <div><i class="ri-qr-code-fill align-bottom me-1"></i> {{survey.year}}</div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Date Finished: </span> <span class="fw-medium">{{survey.finished_at}}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Date Created: </span> <span class="fw-medium">{{survey.created_at}}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Created By: </span> <span class="fw-medium">{{survey.user.profile.fullname}}</span></div>
                                                        <div class="vr"></div>
                                                    </div>
                                                </div>
                                            </BCol>
                                        </BRow>
                                    </BCol>
                                    <BCol md="auto">
                                        <div class="hstack gap-1 flex-wrap">
                                            <button @click="back()" type="button" class="btn py-0 fs-16 text-body">
                                                <i class="ri-share-line"></i>
                                            </button>
                                        </div>
                                    </BCol>
                                </BRow>
                            </BCardBody>
                        </div>
                    </BCard>
                </BCol>
                <BCol lg="3">
                    <Sidebar :survey="survey" :counts="counts"/>
                </BCol>
                <BCol lg="9">
                    <Main :divisions="divisions" :survey="survey"/>
                </BCol>
            </BRow>
        </div>
    </div>
</template>
<script>
import Main from './Components/Main.vue';
import Sidebar from './Components/Sidebar.vue';
export default {
    components: { Main, Sidebar },
    props:['survey_data','counts','divisions'],
    data(){
        return {
            currentUrl: window.location.origin,
            survey: this.survey_data.data
        }
    },
    methods: {
        back(){
            this.$inertia.visit('/surveys');
        }
    }
}
</script>