<template>
    <BModal
    v-model="showModal"
    dialog-class="modal-80"
    hide-footer
    body-class="p-0"
    header-class="p-0"
    class="v-modal-custom"
    content-class="border-0"
    centered
    hide-header-close
>
    <BRow class="g-0">
        <BCol lg="12">
            <div class="modal-body p-4 text-center">

                <div class="avatar-sm mx-auto mb-3">
                    <div
                        class="avatar-title rounded-circle"
                        :class="config.avatarClass"
                    >
                        <i :class="config.icon" class="fs-24"></i>
                    </div>
                </div>

                <h4 class="fw-semibold fs-14">
                    {{ config.title }}
                </h4>

                <p class="text-muted fs-12 mb-4">
                    {{ config.body }}
                </p>

                <div class="d-flex justify-content-center gap-2">
                    <button
                        class="btn btn-light"
                        @click="showModal = false"
                    >
                        Cancel
                    </button>

                    <button
                        class="btn"
                        :class="config.btnClass"
                        @click="save"
                    >
                        {{ config.btnLabel }}
                    </button>
                </div>

            </div>
        </BCol>
    </BRow>
</BModal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    data(){
        return {
            selected: null,
            form: useForm({
                id: null,
                action: null,
                session_id: null,
                participant_id: null,
                status_id: null,
                is_approved: null,
                option: 'participant'
            }),
            action: null,
            showModal: false
        }
    },
    computed: {
        config() {
            const configs = {
                approve: {
                    avatarClass: 'bg-success-subtle text-success',
                    icon: 'ri-checkbox-circle-line',
                    title: 'Approve Participant?',
                    body: 'Are you sure you want to approve this participant? They will be able to attend this exclusive session.',
                    btnClass: 'btn-success',
                    btnLabel: 'Approve',
                },
                reject: {
                    avatarClass: 'bg-danger-subtle text-danger',
                    icon: 'ri-close-circle-line',
                    title: 'Reject Participant?',
                    body: 'Are you sure you want to reject this participant? They will not be allowed to attend this exclusive session.',
                    btnClass: 'btn-danger',
                    btnLabel: 'Reject',
                },
                promote: {
                    avatarClass: 'bg-primary-subtle text-primary',
                    icon: 'ri-arrow-up-circle-line',
                    title: 'Promote Participant?',
                    body: 'Are you sure you want to promote this participant from the waitlist? They will be given a confirmed seat in this session.',
                    btnClass: 'btn-primary',
                    btnLabel: 'Promote',
                },
                pending: {
                    avatarClass: 'bg-warning-subtle text-warning',
                    icon: 'ri-time-line',
                    title: 'Mark as Pending?',
                    body: 'Are you sure you want to mark this participant as pending? Their approval status will be reset for review.',
                    btnClass: 'btn-warning',
                    btnLabel: 'Mark as Pending',
                },
                cancel: {
                    avatarClass: 'bg-warning-subtle text-warning',
                    icon: 'ri-forbid-line',
                    title: 'Cancel Attendance?',
                    body: 'Are you sure you want to cancel this participant\'s attendance? This indicates the participant no longer wishes to attend this session.',
                    btnClass: 'btn-warning',
                    btnLabel: 'Cancel Attendance',
                },
            };
            return configs[this.action] || configs.reject;
        },
    },
    methods: {
        show(session,participant,data){
            this.action = data;
            this.form.action = data;
            if (data === 'approve') {
                this.form.is_approved = 1;
                this.form.status_id = 58;
            } else if (data === 'promote' || data === 'pending') {
                this.form.is_approved = 0;
                this.form.status_id = 52;
            } else if (data === 'cancel') {
                this.form.is_approved = 0;
                this.form.status_id = 59;
            } else {
                this.form.is_approved = 0;
                this.form.status_id = 57;
            }
            this.form.session_id = session;
            this.form.participant_id = participant;
            this.showModal = true;
        },
        save(){
            this.form.put('/sessions/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('success',response.props.flash.data.data);
                    this.hide();
                },
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>