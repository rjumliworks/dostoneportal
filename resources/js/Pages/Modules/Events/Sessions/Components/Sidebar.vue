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
                <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(-557px + 100vh); overflow: auto;">
                   
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
                    <p class="ms-3 mb-0 text-primary fs-11 fw-semibold">Registration Link</p>
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
                                :href="selected.reg_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn mt-2 w-100 btn-primary"
                            >
                                Click here
                            </a>
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