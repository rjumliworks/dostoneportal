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
                                                    <h4 class="fw-bold">{{ (information.events?.length > 1) ? eventTypesSummary(information.events) : (information.events?.[0]?.title || information.purpose) }} </h4>
                                                    <div class="hstack gap-3 flex-wrap">
                                                        <div v-if="information.destination"><span class="text-muted"><i class="ri-map-pin-fill align-bottom me-1"></i> </span> <span class="fw-medium">{{information.destination.name}}</span></div>
                                                        <div v-if="information.destination && information.event_date" class="vr" style="width: 1px;"></div>
                                                        <div v-if="information.event_date"><span class="text-muted"><i class="ri-calendar-event-fill align-bottom me-1"></i> </span> <span class="fw-medium">{{information.event_date.start}}<span v-if="information.event_date.end && information.event_date.end !== information.event_date.start"> - {{information.event_date.end}}</span></span></div>
                                                    </div>
                                                </div>
                                            </BCol>
                                        </BRow>
                                    </BCol>
                                    <BCol md="auto">
                                        <div class="hstack gap-4 flex-wrap mt-2">
                                            <Link href="/approvals">
                                                <div class="text-muted">  
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
                            <!-- {{ $page.props.user.data }} -->
                            <!-- {{ information.codes }} -->
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
    <Edit ref="edit"/>
    <Approved ref="approve"/>
    <Recommend ref="recommend"/>
    <Disapproved ref="disapprove"/>
</template>
<script>
import Main from './Components/Main.vue';
import Edit from './Modals/Edit.vue';
import Approved from '../../Modals/Approved.vue';
import Recommend from '../../Modals/Recommend.vue';
import Disapproved from '../../Modals/Disapproved.vue';
import Sidebar from './Components/Sidebar.vue';
export default {
    props: ['information_data'],
    components: { Main, Sidebar, Edit, Approved, Disapproved, Recommend },
    data(){
        return {
            information: this.information_data.data
        }
    },
    methods: {
        eventTypesSummary(events) {
            const names = (events || []).flatMap(e => (e.types || []).map(t => t.name));
            return [...new Set(names)].join(', ');
        },
        back(){
            this.$inertia.visit('/travels');
        },
        openPrint(id){
            window.open('/travels?option=print&id='+id);
        },
        openEdit(selected){
            this.$refs.edit.show(selected);
        },
        openApprove(id,type,request_id){
            this.$refs.approve.show(id,type,request_id);
        },
        openRecommend(id,type,request_id){
            this.$refs.recommend.show(id,type,request_id);
        },
        openDisapprove(id,type,request_id){
            this.$refs.disapprove.show(id,type,request_id);
        }
    }
}
</script>