<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 850px;" header-class="p-3 bg-light" title="View Date Time Record" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" v-if="selected">
            <BRow>
               <div class="col-md-12">
                    <div>
                        <h4 class="fw-semibold fs-16 mb-1">{{ selected.name }}</h4>
                        <div class="hstack gap-3 flex-wrap fs-12">
                            <div><span class="text-muted">Date :</span> {{ formatDateWithDay(selected.date) }}</div>
                            <div class="vr" style="width: 1px;"></div>
                            <div><span class="text-muted">Date Created :</span> {{selected.created_at}}</div>
                        </div>
                    </div>
               </div>
                <div class="col-md-12 mb-n3">
                    <hr class="text-muted"/>
                </div>
                <div class="col-md-12 mt-3 mb-n3">
                    <div class="card bg-light-subtle shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="ri-alarm-fill text-primary fs-20"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 mt-n1 fs-12"><span class="text-body">Daily Time Logs</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-11">Tracks attendance times and logs any changes for accurate records.</p>
                                </div>
                            
                            </div>
                        </div>
                        <div class="card-body bg-white rounded-bottom">
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex border border-dashed rounded  bg-warning-subtle p-3">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-0 text-muted fs-12">Total Tardiness :</p>
                                            <h6 class="fw-semibold fs-12 mb-0"> {{ selected.tardiness }} minutes</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex border border-dashed bg-warning-subtle rounded p-3">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <p class="mb-0 text-muted fs-12">Total Undertime :</p>
                                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ selected.undertime }} minutes</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-1">
                                    <thead class="bg-primary fs-11 thead-fixed">
                                        <tr class="text-white">
                                            <th class="text-center" style="width: 17%;">Type</th>
                                            <th class="text-center" style="width: 15%;">Time</th>
                                            <th class="text-center" style="width: 15%;">Minutes</th>
                                            <th class="text-center" style="width: 20%;">IP Address</th>
                                            <th class="text-center" style="width: 20%;">PC Name</th>
                                            <th  style="width: 13%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-12">
                                        <tr>
                                            <td class="text-center text-muted">Time In (AM)</td>
                                            <td class="text-center" :class="selected.am_in_at && selected.am_in_at.is_updated ? 'fst-italic' : ''">{{ (selected.am_in_at) ? selected.am_in_at.time : '-' }}</td>
                                            <td class="text-center">{{ (selected.am_in_at) ? (selected.am_in_at.minutes == 0) ? '-' : selected.am_in_at.minutes : '-' }}</td>
                                            <td class="text-center">{{ (selected.am_in_at) ? selected.am_in_at.ip : '-' }}</td>
                                            <td class="text-center">{{ (selected.am_in_at) ? selected.am_in_at.pcname : '-' }}</td>
                                            <td class="text-center">
                                                <b-button v-if="selected.am_in_at" @click="openEdit(selected.id,selected.am_in_at,'Time In (am)')" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </b-button>
                                                <b-button v-else @click="openTime(selected.id,'Time In (am)')" variant="soft-info" v-b-tooltip.hover title="Set" size="sm">
                                                    <i class="ri-add-circle-fill align-bottom"></i>
                                                </b-button>
                                                <a v-if="selected.am_in_at" class="glightbox" :href="'/storage/' + selected.am_in_at.image">
                                                    <b-button class="ms-1" variant="soft-success" v-b-tooltip.hover title="Set" size="sm">
                                                        <i class="ri-image-fill align-bottom"></i>
                                                    </b-button>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center text-muted">Time Out (AM)</td>
                                            <td class="text-center" :class="selected.am_out_at && selected.am_out_at.is_updated ? 'fst-italic' : ''">{{ (selected.am_out_at) ? selected.am_out_at.time : '-' }}</td>
                                            <td class="text-center">{{ (selected.am_out_at) ? (selected.am_out_at.minutes == 0) ? '-' : selected.am_out_at.minutes : '-' }}</td>
                                            <td class="text-center">{{ (selected.am_out_at) ? selected.am_out_at.ip : '-' }}</td>
                                            <td class="text-center">{{ (selected.am_out_at) ? selected.am_out_at.pcname : '-' }}</td>
                                            <td class="text-center">
                                                <b-button v-if="selected.am_out_at" @click="openEdit(selected.id,selected.am_out_at,'Time Out (am)')" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </b-button>
                                                <b-button v-else @click="openTime(selected.id,'Time Out (am)')" variant="soft-info" v-b-tooltip.hover title="Set" size="sm">
                                                    <i class="ri-add-circle-fill align-bottom"></i>
                                                </b-button>
                                                <a v-if="selected.am_out_at" class="glightbox" :href="'/storage/' + selected.am_out_at.image">
                                                    <b-button class="ms-1" variant="soft-success" v-b-tooltip.hover title="Set" size="sm">
                                                        <i class="ri-image-fill align-bottom"></i>
                                                    </b-button>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center text-muted">Time In (PM)</td>
                                            <td class="text-center" :class="selected.pm_in_at && selected.pm_in_at.is_updated ? 'fst-italic' : ''">{{ (selected.pm_in_at) ? selected.pm_in_at.time : '-' }}</td>
                                            <td class="text-center">{{ (selected.pm_in_at) ? (selected.pm_in_at.minutes == 0) ? '-' : selected.pm_in_at.minutes : '-' }}</td>
                                            <td class="text-center">{{ (selected.pm_in_at) ? selected.pm_in_at.ip : '-' }}</td>
                                            <td class="text-center">{{ (selected.pm_in_at) ? selected.pm_in_at.pcname : '-' }}</td>
                                            <td class="text-center">
                                                <b-button v-if="selected.pm_in_at" @click="openEdit(selected.id,selected.pm_in_at,'Time In (pm)')" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </b-button>
                                                <b-button v-else @click="openTime(selected.id,'Time In (pm)')" variant="soft-info" v-b-tooltip.hover title="Set" size="sm">
                                                    <i class="ri-add-circle-fill align-bottom"></i>
                                                </b-button>
                                                <a v-if="selected.pm_in_at" class="glightbox" :href="'/storage/' + selected.pm_in_at.image">
                                                    <b-button class="ms-1" variant="soft-success" v-b-tooltip.hover title="Set" size="sm">
                                                        <i class="ri-image-fill align-bottom"></i>
                                                    </b-button>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center text-muted">Time Out (PM)</td>
                                            <td class="text-center" :class="selected.pm_out_at && selected.pm_out_at.is_updated ? 'fst-italic' : ''">{{ (selected.pm_out_at) ? selected.pm_out_at.time : '-' }}</td>
                                            <td class="text-center">{{ (selected.pm_out_at) ? (selected.pm_out_at.minutes == 0) ? '-' : selected.pm_out_at.minutes : '-' }}</td>
                                            <td class="text-center">{{ (selected.pm_out_at) ? selected.pm_out_at.ip : '-' }}</td>
                                            <td class="text-center">{{ (selected.pm_out_at) ? selected.pm_out_at.pcname : '-' }}</td>
                                            <td class="text-center">
                                                <b-button v-if="selected.pm_out_at" @click="openEdit(selected.id,selected.pm_out_at,'Time Out (pm)')" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </b-button>
                                                <b-button v-else @click="openTime(selected.id,'Time Out (pm)')" variant="soft-info" v-b-tooltip.hover title="Set" size="sm">
                                                    <i class="ri-add-circle-fill align-bottom"></i>
                                                </b-button>
                                                <a v-if="selected.pm_out_at" class="glightbox" :href="'/storage/' + selected.pm_out_at.image">
                                                    <b-button class="ms-1" variant="soft-success" v-b-tooltip.hover title="Set" size="sm">
                                                        <i class="ri-image-fill align-bottom"></i>
                                                    </b-button>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </BRow>
        </form>
          <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
    <Time @update="updateData" ref="time"/>
    <Edit @update="updateData" ref="edit"/>
</template>
<script>
import Edit from './Edit.vue';
import Time from './Time.vue';
import GLightbox from "glightbox";
import "glightbox/dist/css/glightbox.min.css";
export default {
    components: { Edit, Time },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            type: null,
            showModal: false
        }
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
        show(data){
            this.selected = data;
            this.$nextTick(() => {
                this.initLightbox(); 
            });
            this.showModal = true;
        },
        openEdit(id,data,type){
            this.type = type;
            this.$refs.edit.show(id,data,type);
        },
        openTime(id,type){
            this.type = type;
            this.$refs.time.show(id,type);
        },
        updateData(data){
            this.$emit('update',data);
            this.selected = data;
        },
        formatDateWithDay(date) {
            if (!date) return '-';
            const options = { weekday: 'long', year: 'numeric', month: '2-digit', day: '2-digit' };
            const parsed = new Date(date);
            const day = parsed.toLocaleDateString('en-US', { weekday: 'long' });
            return `${day} - ${date}`;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>