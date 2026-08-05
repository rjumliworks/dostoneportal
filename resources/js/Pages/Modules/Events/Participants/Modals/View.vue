<template>
<b-modal
    v-model="showModal"
    style="--vz-modal-width: 650px;"
    hide-footer
    header-class="p-3 bg-light"
    title="Participant Information"
    class="v-modal-custom"
    modal-class="zoomIn"
    centered
    no-close-on-backdrop
    >
    <div class="modal-body p-0" v-if="selected">
        <div class="text-center mt-n1">
            <img
                :src="selected.avatar"
                class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;"
                alt="Participant"
            >
            <h4 class="fs-14 mb-0 mt-2 text-primary text-uppercase fw-semibold">{{ selected.name }}</h4>
            <p class="text-muted fs-12 mb-0">{{ selected.designation }}</p>
            <p class="text-muted fs-11 mb-0">{{ selected.code }}</p>
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
                            <h5 class="mb-0 fs-12">{{ selected.affiliation?.name === 'Others' ? selected.others : selected.affiliation?.name }}</h5>
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
                            <h5 class="mb-0 fs-12">{{ selected.email }}</h5>
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
                            <h5 class="mb-0 fs-12">{{ selected.mobile }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="text-muted"/>

        <h5 class="fs-13 mb-2">Registered Sessions</h5>
        <div v-if="selected.sessions?.length" class="table-responsive" style="max-height: 260px; overflow-y: auto;">
            <table class="table table-sm table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr class="fs-11">
                        <th>Session</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="fs-12">
                    <tr v-for="(session,index) in selected.sessions" :key="index">
                        <td>
                            <h5 class="fs-12 mb-0 fw-semibold">{{ session.title }}</h5>
                            <p class="fs-11 text-muted mb-0">{{ session.venue?.establishment }}</p>
                        </td>
                        <td class="text-center fs-11">{{ session.schedules?.[0]?.date }}</td>
                        <td class="text-center">
                            <span class="badge" :class="session.status?.color+' '+session.status?.bg">{{ session.status?.name }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p v-else class="text-muted fs-12 text-center mb-0">No session registrations yet.</p>
    </div>
    <div v-else class="text-center py-4">
        <span class="spinner-border spinner-border-sm text-primary"></span>
    </div>
</b-modal>
</template>

<script>
export default {
    data() {
        return {
            showModal: false,
            selected: null,
        };
    },
    methods: {
        show(id) {
            this.selected = null;
            this.showModal = true;
            axios.get('/participants', { params: { option: 'show', id } })
                .then(response => {
                    this.selected = response.data.data;
                })
                .catch(err => console.log(err));
        },
        hide() {
            this.showModal = false;
        },
    }
};
</script>
