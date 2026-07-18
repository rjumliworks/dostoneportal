<template>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold text-primary fs-12 ms-2">Session Status</span></td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;">
                    <div class="row ms-n2 mb-0">
                        <div class="col-md-12">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-information-fill "></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h4 class="mt-2">
                                        <b-badge :class="selected.status.type">{{selected.status.name}}</b-badge>
                                    </h4>
                                    
                                    <!-- <p class="mb-1 fs-12 text-muted">Status :</p>
                                    <span :class="'badge '+selected.status.color+' '+selected.status.type">{{selected.status.name}}</span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold text-primary fs-12 ms-2">Session Information</span></td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;">
                    <div class="row ms-n2 mb-0">
                        <div class="col-md-12">
                            <div class="d-flex mt-0">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i :class="(selected.managers.length > 1) ? 'ri-team-fill' : 'ri-user-3-fill'"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Session Manager :</p> 
                                    <h6 class="mb-0 fs-12">
                                        <div v-for="(manager, index) in selected.managers" :key="index">
                                            {{ manager.user.profile.firstname }} {{ manager.user.profile.middlename[0] }}. {{ manager.user.profile.lastname }}
                                        </div>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex mt-3">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Date :</p> 
                                    <h6 class="text-truncate mb-0 fs-12">{{dateRangeText}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex mt-3">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class=" ri-map-pin-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Venue :</p> 
                                    <h6 class="text-truncate mb-0 fs-12">{{selected.venue.name}}, {{ selected.venue.establishment }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold text-primary fs-12 ms-2">QR Code</span></td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;" class="text-center">
                    <img
                        :src="selected.qr"
                        alt="QR Code"
                        style="width: 60%; height: auto; object-fit: contain; cursor: pointer;"
                        @click="openImage(selected.qr)"
                    />
                </td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold text-primary fs-12 ms-2">Session Settings</span></td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;">
                    <div class="d-flex align-items-start gap-3 mb-2">
                        <div class="form-check form-switch form-switch-md">
                            <input class="form-check-input mt-2 ms-3 me-n2" v-model="selected.is_invitational" type="checkbox" role="switch" id="isInvitational">
                        </div>
                        <div>
                            <label class="form-check-label fs-11" for="isInvitational">By Invitation Only</label>
                            <div class="form-text fs-10 mt-0">Only invited participants can register.</div>
                        </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-2">
                        <div class="form-check form-switch form-switch-md">
                            <input class="form-check-input mt-2 ms-3 me-n2" v-model="selected.is_exclusive" type="checkbox" role="switch" id="isExclusive">
                        </div>
                        <div>
                            <label class="form-check-label fs-11" for="isExclusive">Exclusive Access</label>
                            <div class="form-text fs-10 mt-0">Access is restricted to selected participants or groups.</div>
                        </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-2">
                        <div class="form-check form-switch form-switch-md">
                            <input class="form-check-input mt-2 ms-3 me-n2" v-model="selected.is_limited" type="checkbox" role="switch" id="isLimited">
                        </div>
                        <div>
                            <label class="form-check-label fs-11" for="isLimited">Limited Slots</label>
                            <div class="form-text fs-10 mt-0">Participant count is capped. First come, first served.</div>
                        </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-2">
                        <div class="form-check form-switch form-switch-md">
                            <input class="form-check-input mt-2 ms-3 me-n2" v-model="selected.has_registration" type="checkbox" role="switch" id="hasRegistration">
                        </div>
                        <div>
                            <label class="form-check-label fs-11" for="hasRegistration">Requires Registration</label>
                            <div class="form-text fs-10 mt-0">Participants must register before attending the event.</div>
                        </div>
                    </div>
                </td>
            </tr> 
            <tr v-if="selected.has_registration">
                <td style="border-right: none; border-left: none;"><span class="fw-semibold text-primary fs-12 ms-2">Registration Link</span></td>
            </tr>
            <tr v-if="selected.has_registration">
                <td style="border-right: none; border-left: none;">
                    <span class="fs-12 text-muted ms-2">http://dosteventmanager.test/registration/session/{{ selected.link }}</span>
                </td>
            </tr>
            <!-- <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold text-primary fs-12 ms-2">Event Information</span></td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;">
                    <div class="row ms-n2 mb-0">
                        <div class="col-md-12">
                            <div class="d-flex mt-0">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-information-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Event Name :</p> 
                                    <h6 class="text-truncate mb-0 fs-12">{{selected.event.name}}</h6>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-12">
                            <div class="d-flex mt-3">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Date :</p> 
                                    <h6 class="text-truncate mb-0 fs-12" v-if="selected.event.start == selected.event.end">{{selected.event.start}}</h6>
                                    <h6 class="text-truncate mb-0 fs-12" v-else>{{selected.event.start}} - {{selected.event.end}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr> -->
        </tbody>
    </table>
</template>
<script>
export default {
    props: ['selected'],
    computed: {
        dateRangeText() {
            const schedules = this.selected?.schedules || [];

            if (schedules.length === 0) return 'No date';

            let start = schedules[0].date;
            let end = schedules[0].date;

            schedules.forEach(s => {
                if (s.date < start) start = s.date;
                if (s.date > end) end = s.date;
            });

            const formatDate = (dateStr) => {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };

            return start === end
                ? formatDate(start)
                : `${formatDate(start)} - ${formatDate(end)}`;
        }
    },
    methods: {
        openImage(qr) {
            window.open(qr, '_blank');
        }
    }
}
</script>