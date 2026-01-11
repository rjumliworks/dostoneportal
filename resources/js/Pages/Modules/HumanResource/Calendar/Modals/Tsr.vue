<template>
    <b-modal v-if="selected" style="--vz-modal-width: 600px;" v-model="showModal" header-class="p-3 bg-light" :title="'View TSR'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="row mt-2 g-2">
            <div class="col-sm-6">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-hashtag"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12">TSR Code:</p>
                            <h5 class="mb-0 fs-12">{{selected.extendedProps.full_title}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-2-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12">Due Date :</p>
                            <h5 class="mb-0 fs-12">{{selected.extendedProps.datee}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-account-circle-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12">Customer :</p>
                            <h5 class="mb-0 fs-12">{{selected.extendedProps.full_name}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            type: null,
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(data){
            this.editable = false;
            this.selected = data;
            this.showModal = true;
        },
        hide(){
            this.type = null;
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>