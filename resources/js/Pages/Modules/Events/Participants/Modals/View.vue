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
            <div class="position-relative d-inline-block">
                <img
                    v-if="!capturing"
                    :src="selected.avatar"
                    class="rounded-circle img-thumbnail face-capture__media"
                    alt="Participant"
                >
                <div v-else class="rounded-circle img-thumbnail face-capture__media face-capture__stage">
                    <video v-show="!photoPreview" ref="video" autoplay playsinline muted class="face-capture__media"></video>
                    <img v-if="photoPreview" :src="photoPreview" class="face-capture__media" alt="New photo preview">
                </div>
                <button
                    v-if="!capturing"
                    type="button"
                    class="btn btn-icon btn-sm btn-primary rounded-circle face-capture__trigger"
                    v-b-tooltip.hover title="Retake photo (facial recognition fallback)"
                    @click="openCapture"
                >
                    <i class="ri-camera-fill"></i>
                </button>
            </div>
            <canvas ref="canvas" class="d-none"></canvas>
            <h4 class="fs-14 mb-0 mt-2 text-primary text-uppercase fw-semibold">{{ selected.name }}</h4>
            <p class="text-muted fs-12 mb-0">{{ selected.designation }}</p>
            <p class="text-muted fs-11 mb-0">{{ selected.code }}</p>

            <div v-if="capturing" class="mt-2 mx-3">
                <p class="fs-11 text-muted mb-2">
                    Use this if facial recognition fails at check-in. Taking a new photo
                    replaces this participant's registered Face ID.
                </p>
                <div v-if="cameraError" class="alert alert-danger fs-12 py-1 px-2 mb-2">{{ cameraError }}</div>
                <div v-if="saveError" class="alert alert-danger fs-12 py-1 px-2 mb-2">{{ saveError }}</div>
                <div v-if="saveSuccess" class="alert alert-success fs-12 py-1 px-2 mb-2">Face ID updated successfully.</div>

                <div class="d-flex justify-content-center gap-2">
                    <b-button v-if="cameraOn && !photoPreview" size="sm" variant="primary" @click="capturePhoto">
                        <i class="ri-camera-fill me-1"></i> Capture
                    </b-button>
                    <b-button v-if="photoPreview" size="sm" variant="light" :disabled="saving" @click="retakePhoto">
                        <i class="ri-refresh-line me-1"></i> Retake
                    </b-button>
                    <b-button v-if="photoPreview" size="sm" variant="success" :disabled="saving" @click="saveFaceId">
                        <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
                        <i v-else class="ri-check-line me-1"></i> Save as Face ID
                    </b-button>
                    <b-button size="sm" variant="light" :disabled="saving" @click="cancelCapture">
                        Cancel
                    </b-button>
                </div>
            </div>

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
            <div class="col-sm-12" v-if="selected.signature">
                <div class="p-1 border border-dashed rounded">
                    <p class="text-muted mb-1 fs-12">Signature :</p>
                    <div class="text-center bg-light rounded p-2">
                        <img :src="selected.signature" alt="Signature" style="max-height: 80px; max-width: 100%;">
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
            capturing: false,
            cameraOn: false,
            cameraStream: null,
            cameraError: null,
            photoPreview: null,
            photoFile: null,
            saving: false,
            saveError: null,
            saveSuccess: false,
        };
    },
    watch: {
        // Covers every way the modal can close (Cancel, the header's own
        // close button, backdrop) so a live camera stream never keeps
        // running after the modal is gone.
        showModal(value) {
            if (!value) this.cancelCapture();
        },
    },
    beforeUnmount() {
        this.stopCamera();
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
        async openCapture() {
            this.capturing = true;
            this.cameraError = null;
            this.saveError = null;
            this.saveSuccess = false;
            this.photoPreview = null;
            this.photoFile = null;
            await this.startCamera();
        },
        async startCamera() {
            if (!navigator.mediaDevices?.getUserMedia) {
                this.cameraError = 'Camera is not supported on this device or browser.';
                return;
            }
            try {
                this.cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user' },
                    audio: false,
                });
                this.cameraOn = true;
                await this.$nextTick();
                const video = this.$refs.video;
                if (video) {
                    video.srcObject = this.cameraStream;
                    await video.play().catch(() => {});
                }
            } catch (e) {
                this.cameraError = 'Unable to access the camera. Please allow camera permission and try again.';
                this.stopCamera();
            }
        },
        stopCamera() {
            if (this.cameraStream) {
                this.cameraStream.getTracks().forEach(t => t.stop());
                this.cameraStream = null;
            }
            const video = this.$refs.video;
            if (video) video.srcObject = null;
            this.cameraOn = false;
        },
        capturePhoto() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            if (!video || !canvas || !video.videoWidth) return;

            // Center-crop to square so the capture matches the round avatar
            // preview and the framing participants saw during registration.
            const size = Math.min(video.videoWidth, video.videoHeight);
            const sx = (video.videoWidth - size) / 2;
            const sy = (video.videoHeight - size) / 2;
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, sx, sy, size, size, 0, 0, size, size);

            this.photoPreview = canvas.toDataURL('image/jpeg', 0.9);
            canvas.toBlob((blob) => {
                this.photoFile = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
            }, 'image/jpeg', 0.9);

            this.stopCamera();
        },
        retakePhoto() {
            this.photoPreview = null;
            this.photoFile = null;
            this.saveError = null;
            this.startCamera();
        },
        cancelCapture() {
            this.stopCamera();
            this.capturing = false;
            this.photoPreview = null;
            this.photoFile = null;
            this.cameraError = null;
            this.saveError = null;
            this.saveSuccess = false;
        },
        saveFaceId() {
            if (!this.photoFile || this.saving) return;
            this.saving = true;
            this.saveError = null;

            const formData = new FormData();
            formData.append('image', this.photoFile);

            axios.post(`/participants/${this.selected.id}/avatar`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
            })
                .then(response => {
                    this.selected.avatar = response.data.data;
                    this.saveSuccess = true;
                    this.photoPreview = null;
                    this.photoFile = null;
                    setTimeout(() => {
                        this.capturing = false;
                        this.saveSuccess = false;
                    }, 1500);
                })
                .catch(err => {
                    this.saveError = err.response?.data?.message || 'Failed to update face ID. Please try again.';
                })
                .finally(() => {
                    this.saving = false;
                });
        },
    }
};
</script>

<style scoped>
.face-capture__media {
    width: 100px;
    height: 100px;
    object-fit: cover;
}
.face-capture__stage {
    display: block;
    overflow: hidden;
    background: #000;
}
.face-capture__trigger {
    position: absolute;
    right: -2px;
    bottom: -2px;
    width: 28px;
    height: 28px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
