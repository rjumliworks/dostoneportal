<template>
    <b-modal v-if="selected" v-model="showModal" style="--vz-modal-width: 700px;" hide-footer header-class="p-3 bg-light" title="View Participant" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="row align-items-center g-3">
                    <div class="col-md">
                        <div>
                            <h6><span class="fw-bold text-primary fs-16 text-uppercase">{{ selected.participant.firstname }} {{ selected.participant.lastname }}</span></h6>
                            <div class="hstack gap-3 fs-12 flex-wrap mt-n1">
                                <div>Email : 
                                    <span class="fw-medium"> {{ selected.participant.email}}</span>
                                </div>
                                <div class="vr" style="width: 1px;"></div>
                                <div>Contact no. : 
                                    <span class="fw-medium">{{selected.participant.contact_no}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="text-muted"/>
        <div class="row g-1 mt-2">
            <!-- <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class=" ri-checkbox-blank-circle-line"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12">Status :</p>
                            <h5 class="mb-0 fs-12">{{selected.status.name}}</h5>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-sm-6">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-hotel-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12">Affiliation :</p>
                            <h5 class="mb-0 fs-12">{{selected.participant.affiliation}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-account-circle-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12">Designation :</p>
                            <h5 class="mb-0 fs-12">{{selected.participant.designation}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <hr class="text-muted"/>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table align-middle table-centered table-bordered table-striped mb-0">
                        <thead class="table-light thead-fixed">
                            <tr class="fs-11">
                                <th style="width: 60%;" class="text-center">Attendance</th>
                                <th style="width: 20%;" class="text-center">CSF</th>
                                <th style="width: 20%;" class="text-center">Certificate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"> 
                                    {{selected.attended_at}}
                                </td>
                                <td class="text-center">
                                    <i v-if="selected.participant.has_csf" class="ri-checkbox-circle-fill text-success fs-24"></i>
                                    <i v-else class="ri-close-circle-fill text-danger fs-24"></i>
                                </td>
                                <td class="text-center">
                                    <i v-if="selected.participant.has_csf" class="ri-checkbox-circle-fill text-success fs-24"></i>
                                    <i v-else class="ri-close-circle-fill text-danger fs-24"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
            <div class="mt-4">
                <b-button @click="openPrint1(selected.code)" variant="primary" class="me-1" v-b-tooltip.hover title="Print Appreciation">
                    <i class="ri-eye-fill align-bottom"></i> Appreciation
                </b-button> 
                <b-button @click="openPrint2(selected.code)" variant="primary" class="me-1" v-b-tooltip.hover title="Print Appearance">
                    <i class="ri-printer-fill align-bottom"></i> Appearance
                </b-button>
            </div>
        <template v-slot:footer>
            
            <!-- <b-button @click="hide" variant="light" block>Close</b-button> -->
            <!-- <b-button @click="openResult" variant="primary" block>Preview</b-button> -->
        </template>
    </b-modal>
</template>

<script>
import simplebar from "simplebar-vue";
export default {
  components: { simplebar },
  data() {
        return {
            currentUrl: window.location.origin,
            showModal: false,
            selected: null,
        };
    },
    methods: {
        show(data) {
            this.selected = data;
            this.showModal = true;
        },
        hide() {
            this.showModal = false;
        },
        openPrint1(id){
            // window.open('/print?option=session&type=appearance&krdwrks='+id);
            window.open('/print?option=session&type=appreciation&krdwrks='+id);
        },
        openPrint2(id){
            // window.open('/print?option=session&type=appearance&krdwrks='+id);
            window.open('/print?option=session&type=appearance&krdwrks='+id);
        }
    }
};
</script>
