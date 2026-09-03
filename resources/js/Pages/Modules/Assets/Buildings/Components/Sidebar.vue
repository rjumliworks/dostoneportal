<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-information-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Building Details</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Location details of the building</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom border-bottom">
            <p class="mb-0 text-primary fs-12 fw-semibold">Address</p>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 398px); overflow: auto;">
            <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-price-tag-3-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ building.code || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Code</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-map-pin-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ building.address || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Street Address</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-community-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ [building.barangay?.name, building.municipality?.name, building.province?.name, building.region?.region].filter(Boolean).join(', ') || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Barangay / Municipality / Province</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-building-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ building.station?.name || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Station</p>
                        </div>
                    </div>
                </li>
            </ul>
            <hr class="text-muted"/>
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Coordinates</p>
            <hr class="text-muted mb-2"/>
            <div class="px-3 pb-3" style="height: 220px;">
                <Map ref="map" class="leaflet-map" height="100%"/>
            </div>
        </div>
    </div>
</template>
<script>
import Map from '@/Shared/Layouts/Components/Modals/Map.vue';
export default {
    components: { Map },
    props: ['building'],
    mounted(){
        this.$nextTick(() => {
            this.$refs.map.view();
            if(this.building.latitude && this.building.longitude){
                this.$refs.map.setPin(parseFloat(this.building.latitude), parseFloat(this.building.longitude));
            }
        });
    }
}
</script>
