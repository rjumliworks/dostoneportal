<template>
    <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2rem;width:2rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-0">
                                    <i class="ri-information-fill text-primary fs-18"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Session Details</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-11">Basic Information of the session</p>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom border-bottom">
                    <p class="mb-0 text-primary fs-11 fw-semibold">Session Status</p>
                </div>
                <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 388px); overflow: auto;">
                   
                    <ul class="list-group list-group-flush mb-n4 mt-n3 p-3">
                        <li class="list-group-item px-0 mb-n2">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs">
                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                        <i class="text-primary" :class="(selected.managers.length > 1) ? 'ri-team-fill' : 'ri-user-3-fill'"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="fs-11 mb-0 text-muted">Status</p>
                                    <h6 class="mb-0 fs-12">
                                        <span :class="'badge fs-11 '+selected.status.bg">{{selected.status.name}}</span>
                                    </h6>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 mb-n2">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs">
                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                        <i class="text-primary" :class="(selected.managers.length > 1) ? 'ri-team-fill' : 'ri-user-3-fill'"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="fs-11 mb-0 text-muted">Session Manager</p>
                                    <h6 class="mb-0 fs-12">
                                        <div v-for="(manager, index) in selected.managers" :key="index">
                                            {{ manager.name }}
                                        </div>
                                    </h6>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs"><span
                                        class="avatar-title bg-light p-1 rounded-circle"><i
                                            class="ri-map-pin-fill text-primary"></i></span></div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="fs-11 mb-0 text-muted">Venue</p>
                                    <h6 class="mb-0 fs-12">{{ selected.venue.name }}, {{ selected.venue.establishment }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <hr class="text-muted">
                    <p class="ms-3 mb-0 text-primary fs-11 fw-semibold">Links</p>
                    <hr class="text-muted mb-2">
                    
                    <div class="d-flex p-2">
                        <div class="flex-shrink-0">
                            <a :href="selected.qr" target="_blank" rel="noopener noreferrer">
                                <div style="width: 55px; height: 55px;">
                                    <img :src="selected.qr" alt="user-img" class="img-thumbnail">
                                </div>
                            </a>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <a
                                :href="selected.attendance_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn mt-2 w-100 btn-primary"
                            >
                                Attendance
                            </a>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <a
                                :href="selected.reg_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn mt-2 w-100 btn-primary"
                            >
                                Registration
                            </a>
                        </div>
                    </div>

                    <hr class="text-muted mt-2">
                    <p class="ms-3 mb-0 text-primary fs-11 fw-semibold">Settings</p>
                    <hr class="text-muted mb-2">
                    <div class="ms-n4 mb-2">
                        <div class="d-flex align-items-start gap-3 mb-2">
                            <div class="form-check form-switch form-switch-md">
                                <input class="form-check-input mt-2 ms-3 me-n2" v-model="selected.is_prereg" type="checkbox" role="switch" id="isInvitational">
                            </div>
                            <div>
                                <label class="form-check-label fs-11" for="isInvitational">Pre-Registration</label>
                                <div class="form-text fs-10 mt-0">Allow pre-registration thru link</div>
                            </div>
                        </div>
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
                    </div>
                </div>
            </div>
    
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