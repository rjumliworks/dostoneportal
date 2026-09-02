<template>
    <b-modal v-model="showModal" @shown="onShown" style="--vz-modal-width: 900px;" hide-footer header-class="p-3 bg-light" title="View Building" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <BRow v-if="selected" class="g-3">
            <BCol lg="6">
                <h5 class="fs-14 mb-1 fw-semibold text-primary">{{ selected.name }}</h5>
                <p class="fs-12 text-muted mb-3">{{ selected.station?.name }}</p>

                <p class="fs-12 text-muted mb-1">Address</p>
                <p class="fs-13 mb-3">
                    {{ [selected.address, selected.barangay?.name, selected.municipality?.name, selected.province?.name, selected.region?.region].filter(Boolean).join(', ') }}
                </p>

                <p class="fs-12 text-muted mb-1">Coordinates</p>
                <p class="fs-13 mb-0">
                    <span v-if="selected.latitude && selected.longitude">{{ selected.latitude }}, {{ selected.longitude }}</span>
                    <span v-else class="text-muted">Not plotted</span>
                </p>
            </BCol>
            <BCol lg="6" class="d-flex flex-column">
                <div class="flex-grow-1" style="min-height: 260px;">
                    <Map ref="map" class="leaflet-map" height="100%"/>
                </div>
            </BCol>
        </BRow>
    </b-modal>
</template>
<script>
import Map from '@/Shared/Layouts/Components/Modals/Map.vue';
export default {
    components: { Map },
    data(){
        return {
            selected: null,
            showModal: false
        }
    },
    methods: {
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        onShown(){
            this.$refs.map.view();
            if(this.selected?.latitude && this.selected?.longitude){
                this.$refs.map.setPin(parseFloat(this.selected.latitude), parseFloat(this.selected.longitude));
            }else{
                this.$refs.map.empty();
            }
        },
        hide(){
            this.selected = null;
            this.showModal = false;
        }
    }
}
</script>
