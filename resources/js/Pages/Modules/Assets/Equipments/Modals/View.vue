<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" hide-footer header-class="p-3 bg-light" title="View Equipment" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <BRow v-if="selected" class="g-3">
            <BCol lg="12" class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="fs-14 mb-1 fw-semibold text-primary">{{ selected.name }}</h5>
                    <p class="fs-12 text-muted mb-0">{{ selected.type?.name }}</p>
                </div>
                <span v-if="selected.status" :class="'badge '+selected.status.bg+' '+selected.status.type">{{ selected.status.name }}</span>
            </BCol>
            <BCol lg="12"><hr class="text-muted mt-0 mb-0"/></BCol>

            <BCol lg="4">
                <p class="fs-12 text-muted mb-1">Code</p>
                <p class="fs-13 mb-0">{{ selected.code }}</p>
            </BCol>
            <BCol lg="4">
                <p class="fs-12 text-muted mb-1">Old Code</p>
                <p class="fs-13 mb-0">{{ selected.old_code || '-' }}</p>
            </BCol>
            <BCol lg="4">
                <p class="fs-12 text-muted mb-1">Date Acquired</p>
                <p class="fs-13 mb-0">{{ selected.acquired_at || '-' }}</p>
            </BCol>

            <BCol lg="4">
                <p class="fs-12 text-muted mb-1">Brand</p>
                <p class="fs-13 mb-0">{{ selected.detail?.brand || '-' }}</p>
            </BCol>
            <BCol lg="4">
                <p class="fs-12 text-muted mb-1">Model</p>
                <p class="fs-13 mb-0">{{ selected.detail?.model || '-' }}</p>
            </BCol>
            <BCol lg="4">
                <p class="fs-12 text-muted mb-1">Price</p>
                <p class="fs-13 mb-0">{{ selected.detail?.price ? Number(selected.detail.price).toLocaleString('en-US',{minimumFractionDigits:2}) : '-' }}</p>
            </BCol>

            <BCol lg="6">
                <p class="fs-12 text-muted mb-1">Maintenance Plan</p>
                <p class="fs-13 mb-0">{{ selected.maintenance_plan || '-' }}</p>
            </BCol>
            <BCol lg="6">
                <p class="fs-12 text-muted mb-1">Maintenance Due</p>
                <p class="fs-13 mb-0">{{ selected.maintenance_due || '-' }}</p>
            </BCol>

            <BCol lg="12">
                <p class="fs-12 text-muted mb-1">Specification / Description</p>
                <ul v-if="selected.detail?.specification?.length" class="fs-13 mb-0 ps-3">
                    <li v-for="(spec,index) in selected.detail.specification" v-bind:key="index">{{ spec }}</li>
                </ul>
                <p v-else class="fs-13 text-muted mb-0">-</p>
            </BCol>

            <BCol lg="12">
                <p class="fs-12 text-muted mb-1">Remarks</p>
                <p class="fs-13 mb-0">{{ selected.remarks || '-' }}</p>
            </BCol>

            <BCol lg="12"><hr class="text-muted mt-0 mb-0"/></BCol>
            <BCol lg="12">
                <p class="fs-12 text-muted mb-1">Currently Assigned To</p>
                <p v-if="selected.current_assignment?.user" class="fs-13 mb-0">
                    {{ selected.current_assignment.user.profile?.firstname }} {{ selected.current_assignment.user.profile?.lastname }}
                    <span class="text-muted"> - since {{ selected.current_assignment.start_at }}</span>
                </p>
                <p v-else class="fs-13 text-muted mb-0">Not assigned</p>
            </BCol>
        </BRow>
    </b-modal>
</template>
<script>
export default {
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
        hide(){
            this.selected = null;
            this.showModal = false;
        }
    }
}
</script>
