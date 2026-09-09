<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Event Details" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div v-if="data">
            <div class="d-flex mb-3">
                <div class="flex-grow-1">
                    <h5 class="mb-1">{{ data.title }}</h5>
                    <div class="fs-12 text-muted"><i class="ri-calendar-event-fill me-1"></i>{{ data.datee }}</div>
                </div>
            </div>

            <div class="mb-3" v-if="isHoliday">
                <span class="badge fs-11" :class="data.className">{{ data.type }}</span>
            </div>
            <div class="mb-3" v-else>
                <span v-for="(t, i) in (data.types || [])" :key="i" class="badge fs-11 me-1" :class="data.className">{{ t }}</span>
            </div>

            <template v-if="isHoliday">
                <div class="mb-2 fs-13" v-if="data.full_name">
                    <i class="ri-user-fill me-1 text-muted"></i> Filed by <span class="fw-medium">{{ data.full_name }}</span>
                </div>
                <div class="mb-2 fs-13" v-if="stationNames">
                    <i class="ri-map-pin-fill me-1 text-muted"></i> {{ stationNames }}
                </div>
            </template>

            <template v-else>
                <div class="mb-2 fs-13" v-if="data.mode">
                    <i class="ri-broadcast-fill me-1 text-muted"></i> Mode: <span class="fw-medium">{{ data.mode }}</span>
                </div>
                <div class="mb-3 fs-13" v-if="data.audience">
                    <i class="ri-group-fill me-1 text-muted"></i> Audience: <span class="fw-medium">{{ data.audience }}</span>
                </div>

                <hr class="text-muted"/>

                <h6 class="fs-13 mb-2">Participants ({{ (data.participants || []).length }})</h6>
                <div class="table-responsive" style="max-height: 320px; overflow: auto;">
                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            <tr v-for="(p, i) in data.participants" :key="i">
                                <td style="width: 2.5rem;">
                                    <img :src="p.avatar" alt="" class="rounded-circle avatar-xs">
                                </td>
                                <td class="fs-13">{{ p.name }}</td>
                            </tr>
                            <tr v-if="!(data.participants || []).length">
                                <td class="text-center text-muted fs-13 py-3">No participants tagged to this event.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
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
            data: null,
            showModal: false
        }
    },
    computed: {
        isHoliday(){
            return !!(this.data && this.data.request_event_id === undefined);
        },
        stationNames(){
            if (!this.data || !this.data.stations || !this.data.stations.length) return '';
            return this.data.stations
                .map(s => s.station ? s.station.name : null)
                .filter(Boolean)
                .join(', ');
        }
    },
    methods: {
        show(data){
            this.data = data;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
            this.data = null;
        }
    }
}
</script>
