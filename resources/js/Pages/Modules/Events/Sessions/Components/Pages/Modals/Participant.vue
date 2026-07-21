<template>
<b-modal
    v-if="selected"
    v-model="showModal"
    style="--vz-modal-width: 550px;"
    hide-footer
    header-class="p-3 bg-light"
    title="Participant Information"
    class="v-modal-custom"
    modal-class="zoomIn"
    centered
    no-close-on-backdrop
    >
    <div class="modal-body p-0">
        <div>
            <div class="text-center mt-n1">
                <img
                    :src="selected.avatar"
                    class="rounded-circle img-thumbnail" style="width: 100px; height: auto;"
                    alt="Participant"
                >
                <h4 class="fs-14 mb-0 mt-2 text-primary text-uppercase fw-semibold">{{ selected.name }}</h4>
                 <p class="text-muted fs-12 mb-0">{{selected.designation}}</p>
                <hr class="text-muted"/>
            </div>
            <div class="row g-2">
                <div class="col-sm-12">
                    <div class="p-1 border border-dashed rounded">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-2">
                                <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-hotel-fill"></i></div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0 fs-12">Affiliation :</p>
                                <h5 class="mb-0 fs-12">{{selected.affiliation}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-1 border border-dashed rounded">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-2">
                                <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-mail-fill"></i></div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0 fs-12">Email Address :</p>
                                <h5 class="mb-0 fs-12">{{selected.email}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-1 border border-dashed rounded">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-2">
                                <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-phone-fill"></i></div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="text-muted mb-0 fs-12">Mobile no. :</p>
                                <h5 class="mb-0 fs-12">{{selected.mobile}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="text-muted"/>
            <a v-if="selected.image" class="glightbox" :href="'/storage/' + selected.image">
                <b-button class="ms-1" variant="soft-success" v-b-tooltip.hover title="Set" size="sm">
                    <i class="ri-image-fill align-bottom"></i>
                </b-button>
            </a>
            <div class="d-grid gap-2" v-if="is_exclusive && selected.status.name == 'Pending'">
                <button @click="openApproval('approve')" class="btn btn-success" type="button">Approved</button>
                <button @click="openApproval('reject')" class="btn btn-soft-danger" type="button">Reject</button>
            </div>
        </div>
    </div>
</b-modal>
<Approval @success="updateParticipant" ref="approval"/>
</template>

<script>
import Approval from './Confirm.vue';
import simplebar from "simplebar-vue";
import GLightbox from "glightbox";
import "glightbox/dist/css/glightbox.min.css";
export default {
    components: { simplebar, Approval },
    props:['is_exclusive'],
    data() {
        return {
            currentUrl: window.location.origin,
            showModal: false,
            selected: null,
        };
    },
    mounted() {
        this.initLightbox();
    },
    methods: {
         initLightbox() {
            if (this.lightbox) {
                this.lightbox.destroy(); // clean up old instance
            }
            this.lightbox = GLightbox({
                selector: ".glightbox",
                touchNavigation: true,
                loop: true,
                zoomable: true,
            });
        },
        openApproval(data){
            this.$refs.approval.show(this.selected.session_id,this.selected.participant_id,data);
        },
        updateParticipant(data){
            console.log(data);
            this.selected = data;
        },
        show(data) {
            this.selected = data;
              this.$nextTick(() => {
                this.initLightbox(); 
            });
            this.showModal = true;
        },
        hide() {
            this.showModal = false;
        },
    }
};
</script>
