<template>

    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-information-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Exhibitor Details</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Basic Information of the employee</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom border-bottom">
            <p class="mb-0 text-primary fs-12 fw-semibold">Assignment Information</p>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 392px); overflow: auto;">
            <ul class="list-group list-group-flush border-dashed mb-n4 mt-n2 p-3">
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-government-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ selected.institution }}</h6>
                            <p class="fs-11 mb-0 text-muted">Institution</p>
                        </div>
                    </div>
                </li> 
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-sun-foggy-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{selected.type.name}}</h6>
                            <p class="fs-11 mb-0 text-muted">Type</p>
                        </div>
                    </div>
                </li>
            </ul>
            <hr class="text-muted"/>
                <div class="d-flex align-items-center justify-content-between ms-3 me-3">
                    <div class="d-flex align-items-center gap-1">
                        <button v-if="!selected.contact" type="button" class="btn btn-icon btn-sm btn-soft-primary rounded-circle" title="Add contact" @click="openContact()">
                            <i class="ri-add-line"></i>
                        </button>
                        <p class="mb-0 text-primary fs-12 fw-semibold">Contact Details</p>
                    </div>
                    <button type="button" class="btn btn-icon btn-sm btn-soft-secondary rounded-circle" title="Open QR code to print" @click="openImage(selected.qr)">
                        <i class="ri-qr-code-line"></i>
                    </button>
                </div>
            <hr class="text-muted mb-2"/>
            <ul v-if="selected.contact" class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3"  style="cursor: pointer;" @click="openSalary()">
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-account-circle-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ selected.contact.name }}</h6>
                            <p class="fs-11 mb-0 text-muted">Name</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-mail-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ selected.contact.email }}</h6>
                            <p class="fs-11 mb-0 text-muted">Email</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-phone-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ selected.contact.contact_no }}</h6>
                            <p class="fs-11 mb-0 text-muted">Mobile Phone</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <CreateContact ref="contact"/>
</template>
<script>
import CreateContact from './Modals/CreateContact.vue';
export default {
    components: { CreateContact },
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
        },
        openContact() {
            this.$refs.contact.show(this.selected.id);
        }
    }
}
</script>