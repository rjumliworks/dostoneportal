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
                        :class="action === 'approve'
                            ? 'bg-success-subtle text-success'
                            : 'bg-danger-subtle text-danger'"
                    >
                        <i
                            :class="action === 'approve'
                                ? 'ri-checkbox-circle-line'
                                : 'ri-close-circle-line'"
                            class="fs-24"
                        ></i>
                    </div>
                </div>

                <h4 class="fw-semibold fs-14">
                    {{ action === 'approve'
                        ? 'Approve Participant?'
                        : 'Reject Participant?' }}
                </h4>

                <p class="text-muted fs-12 mb-4">
                    {{ action === 'approve'
                        ? 'Are you sure you want to approve this participant? They will be able to attend this exclusive session.'
                        : 'Are you sure you want to reject this participant? They will not be allowed to attend this exclusive session.' }}
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
                        :class="action === 'approve'
                            ? 'btn-success'
                            : 'btn-danger'"
                        @click="save"
                    >
                        {{ action === 'approve'
                            ? 'Approve'
                            : 'Reject' }}
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
    methods: { 
        show(session,participant,data){
            this.action = data;
            this.form.action = data;
            this.form.is_approved = (data == 'approve') ? 1 : 0; 
            this.form.status_id = (data == 'approve') ? 58 : 57; 
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