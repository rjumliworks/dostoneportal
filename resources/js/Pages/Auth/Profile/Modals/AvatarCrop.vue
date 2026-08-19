<template>
    <b-modal v-model="showModal" title="Update Profile Photo" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop @hidden="reset">
        <div class="p-2">
            <template v-if="!imageSrc">
                <ul class="nav nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <button type="button" class="nav-link" :class="{ active: mode === 'upload' }" @click="setMode('upload')">
                            <i class="ri-upload-2-line align-bottom me-1"></i> Upload Photo
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" :class="{ active: mode === 'capture' }" @click="setMode('capture')">
                            <i class="ri-camera-fill align-bottom me-1"></i> Use Camera
                        </button>
                    </li>
                </ul>

                <div v-if="mode === 'upload'" class="text-center">
                    <input ref="fileInput" type="file" accept="image/jpeg,image/png" class="d-none" @change="onFileSelected"/>
                    <div class="avatar-dropzone rounded p-4" role="button" @click="$refs.fileInput.click()">
                        <i class="ri-image-add-line fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">Click to choose a JPEG or PNG photo (max 2MB)</p>
                    </div>
                </div>

                <div v-else class="text-center">
                    <video v-show="!cameraError" ref="cameraPreview" autoplay playsinline muted class="camera-preview mx-auto" @loadeddata="cameraReady = true"></video>
                    <div v-if="cameraError" class="text-danger fs-13 py-5">{{ cameraError }}</div>
                    <div class="mt-2">
                        <b-button size="sm" variant="secondary" :disabled="!cameraReady" @click="capturePhoto">
                            <i class="ri-camera-fill me-1"></i> Take Photo
                        </b-button>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="text-center">
                    <div class="text-muted mb-3 fs-13">Drag the photo to center it. Everyone's avatar is saved at the same 500 x 500 size.</div>
                    <div
                        ref="viewport"
                        class="avatar-crop-viewport mx-auto"
                        @mousedown="startDrag"
                        @touchstart="startDrag"
                        @wheel.prevent="onWheel"
                    >
                        <img
                            ref="img"
                            :src="imageSrc"
                            class="avatar-crop-image"
                            :style="imageStyle"
                            draggable="false"
                            @load="onImageLoad"
                            @dragstart.prevent
                        />
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-2 mt-3 mx-auto" style="max-width: 260px;">
                        <i class="ri-zoom-out-line text-muted"></i>
                        <input type="range" class="form-range" min="1" max="3" step="0.01" v-model.number="zoom" @input="onZoomInput"/>
                        <i class="ri-zoom-in-line text-muted"></i>
                    </div>
                    <button type="button" class="btn btn-link btn-sm mt-1" @click="discardImage">
                        <i class="ri-arrow-go-back-line align-bottom me-1"></i> Choose a different photo
                    </button>
                </div>
            </template>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button v-if="imageSrc" @click="upload()" variant="primary" :disabled="processing" block><i class="ri-upload-2-line align-bottom me-1"></i> Upload</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3'

const VIEWPORT_SIZE = 260;
const OUTPUT_SIZE = 500;

export default {
    props: {
        code: { type: String, default: null },
    },
    data() {
        return {
            showModal: false,
            mode: 'upload',
            imageSrc: null,
            naturalWidth: 0,
            naturalHeight: 0,
            coverScale: 1,
            zoom: 1,
            prevZoom: 1,
            offsetX: 0,
            offsetY: 0,
            dragging: false,
            dragStart: { x: 0, y: 0 },
            offsetStart: { x: 0, y: 0 },
            processing: false,
            cameraStream: null,
            cameraReady: false,
            cameraError: null,
            form: useForm({
                image: null,
                code: null,
                option: null,
            }),
        };
    },
    computed: {
        imageStyle() {
            const scale = this.coverScale * this.zoom;
            return {
                width: (this.naturalWidth * scale) + 'px',
                height: (this.naturalHeight * scale) + 'px',
                transform: `translate(${this.offsetX}px, ${this.offsetY}px)`,
            };
        },
    },
    beforeUnmount() {
        this.stopCamera();
    },
    methods: {
        show() {
            this.reset();
            this.showModal = true;
        },
        setMode(next) {
            if (this.mode === next) return;
            if (this.mode === 'capture') this.stopCamera();
            this.mode = next;
            if (next === 'capture') this.startCamera();
        },
        onFileSelected(e) {
            const fileInput = e.target;
            const file = fileInput.files[0];
            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert("Only JPEG or PNG images are allowed.");
                fileInput.value = '';
                return;
            }

            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                alert("The image must be less than 2MB.");
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.addEventListener('load', () => {
                this.imageSrc = reader.result;
            });
            reader.readAsDataURL(file);
            fileInput.value = '';
        },
        async startCamera() {
            this.cameraError = null;
            this.cameraReady = false;
            try {
                this.cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 500 }, height: { ideal: 500 } },
                });
                this.$refs.cameraPreview.srcObject = this.cameraStream;
            } catch (error) {
                console.error('Camera access denied:', error);
                this.cameraError = 'Unable to access camera. Please allow camera permission and try again.';
            }
        },
        stopCamera() {
            if (this.cameraStream) {
                this.cameraStream.getTracks().forEach((track) => track.stop());
                this.cameraStream = null;
            }
            this.cameraReady = false;
        },
        capturePhoto() {
            const video = this.$refs.cameraPreview;
            if (!video || !video.videoWidth) return;

            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            this.imageSrc = canvas.toDataURL('image/png');
            this.stopCamera();
        },
        discardImage() {
            this.imageSrc = null;
            if (this.mode === 'capture') this.startCamera();
        },
        onImageLoad(e) {
            this.naturalWidth = e.target.naturalWidth;
            this.naturalHeight = e.target.naturalHeight;
            this.coverScale = Math.max(VIEWPORT_SIZE / this.naturalWidth, VIEWPORT_SIZE / this.naturalHeight);
            this.zoom = 1;
            this.prevZoom = 1;
            const w = this.naturalWidth * this.coverScale;
            const h = this.naturalHeight * this.coverScale;
            this.offsetX = (VIEWPORT_SIZE - w) / 2;
            this.offsetY = (VIEWPORT_SIZE - h) / 2;
        },
        onZoomInput() {
            const ratio = this.zoom / this.prevZoom;
            const center = VIEWPORT_SIZE / 2;
            this.offsetX = center - (center - this.offsetX) * ratio;
            this.offsetY = center - (center - this.offsetY) * ratio;
            this.prevZoom = this.zoom;
            this.constrainOffset();
        },
        onWheel(e) {
            const delta = e.deltaY < 0 ? 0.05 : -0.05;
            this.zoom = Math.min(3, Math.max(1, this.zoom + delta));
            this.onZoomInput();
        },
        startDrag(e) {
            if (!this.imageSrc) return;
            this.dragging = true;
            const point = e.touches ? e.touches[0] : e;
            this.dragStart = { x: point.clientX, y: point.clientY };
            this.offsetStart = { x: this.offsetX, y: this.offsetY };
            window.addEventListener('mousemove', this.onDrag);
            window.addEventListener('mouseup', this.stopDrag);
            window.addEventListener('touchmove', this.onDrag, { passive: false });
            window.addEventListener('touchend', this.stopDrag);
        },
        onDrag(e) {
            if (!this.dragging) return;
            if (e.touches) e.preventDefault();
            const point = e.touches ? e.touches[0] : e;
            this.offsetX = this.offsetStart.x + (point.clientX - this.dragStart.x);
            this.offsetY = this.offsetStart.y + (point.clientY - this.dragStart.y);
            this.constrainOffset();
        },
        stopDrag() {
            this.dragging = false;
            window.removeEventListener('mousemove', this.onDrag);
            window.removeEventListener('mouseup', this.stopDrag);
            window.removeEventListener('touchmove', this.onDrag);
            window.removeEventListener('touchend', this.stopDrag);
        },
        constrainOffset() {
            const w = this.naturalWidth * this.coverScale * this.zoom;
            const h = this.naturalHeight * this.coverScale * this.zoom;
            this.offsetX = Math.min(0, Math.max(VIEWPORT_SIZE - w, this.offsetX));
            this.offsetY = Math.min(0, Math.max(VIEWPORT_SIZE - h, this.offsetY));
        },
        upload() {
            if (!this.imageSrc || this.processing) return;
            this.processing = true;

            const canvas = document.createElement('canvas');
            canvas.width = OUTPUT_SIZE;
            canvas.height = OUTPUT_SIZE;
            const outputScale = OUTPUT_SIZE / VIEWPORT_SIZE;
            const totalScale = this.coverScale * this.zoom * outputScale;
            canvas.getContext('2d').drawImage(
                this.$refs.img,
                this.offsetX * outputScale,
                this.offsetY * outputScale,
                this.naturalWidth * totalScale,
                this.naturalHeight * totalScale
            );

            canvas.toBlob((blob) => {
                this.form.image = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });

                const isAdmin = !!this.code;
                if (isAdmin) {
                    this.form.code = this.code;
                    this.form.option = 'avatar';
                }

                this.form.post(isAdmin ? '/users' : '/photo', {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$emit('uploaded');
                        this.hide();
                    },
                    onFinish: () => {
                        this.processing = false;
                    },
                });
            }, 'image/jpeg', 0.92);
        },
        hide() {
            this.showModal = false;
        },
        reset() {
            this.stopDrag();
            this.stopCamera();
            this.mode = 'upload';
            this.imageSrc = null;
            this.naturalWidth = 0;
            this.naturalHeight = 0;
            this.coverScale = 1;
            this.zoom = 1;
            this.prevZoom = 1;
            this.offsetX = 0;
            this.offsetY = 0;
            this.processing = false;
            this.cameraError = null;
            this.form.reset();
        },
    },
}
</script>
<style scoped>
.avatar-dropzone {
    border: 2px dashed var(--vz-border-color, #ced4da);
}
.camera-preview {
    display: block;
    width: 260px;
    height: 260px;
    object-fit: cover;
    background: #000;
    border-radius: 50%;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--vz-primary, #405189);
}
.avatar-crop-viewport {
    position: relative;
    width: 260px;
    height: 260px;
    border-radius: 50%;
    overflow: hidden;
    background-color: #e9ecef;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--vz-primary, #405189);
    cursor: move;
    touch-action: none;
    margin-bottom: 0.5rem;
}
.avatar-crop-image {
    position: absolute;
    left: 0;
    top: 0;
    max-width: none;
    pointer-events: none;
    user-select: none;
}
</style>
