<template>
    <Head title="Building Profile" />
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
                                                    <span class="avatar-title bg-white rounded-circle" style="width:70px;height:70px;">
                                                        <i class="ri-government-fill text-primary fs-24"></i>
                                                    </span>
                                                </div>
                                            </BCol>
                                            <BCol md>
                                                <div class="ms-2">
                                                    <h4 class="fw-bold text-uppercase">{{ building.name }}</h4>
                                                    <div class="hstack gap-3 flex-wrap">
                                                        <div><i class="ri-qr-code-fill align-bottom me-1"></i> {{ building.code }}</div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><i class="ri-map-pin-fill align-bottom me-1"></i> {{ [building.address, building.barangay?.name, building.municipality?.name, building.province?.name].filter(Boolean).join(', ') || '-' }}</div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Station :</span> <span class="fw-medium">{{ building.station?.name || '-' }}</span></div>
                                                        <div class="vr" style="width: 1px;"></div>
                                                        <div><span class="text-muted">Date Added :</span> <span class="fw-medium">{{ building.created_at || '-' }}</span></div>
                                                        <div class="vr"></div>
                                                    </div>
                                                </div>
                                            </BCol>
                                        </BRow>
                                    </BCol>
                                    <BCol md="auto">
                                        <div class="hstack gap-1 flex-wrap mt-3">
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
                    <Sidebar :building="building"/>
                </BCol>
                <BCol lg="9">
                    <Main :dropdowns="dropdowns" :building="building"/>
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
    props: ['building_data','dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            building: this.building_data.data
        }
    },
    methods: {
        back(){
            this.$inertia.visit('/buildings');
        }
    }
}
</script>
