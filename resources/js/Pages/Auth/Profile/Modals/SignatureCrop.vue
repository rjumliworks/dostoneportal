<template>
    <b-modal v-model="showModal" title="Update E-Signature" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop @hidden="reset">
        <div class="p-2">
            <template v-if="!imageSrc">
                <div class="text-center">
                    <input ref="fileInput" type="file" accept="image/png" class="d-none" @change="onFileSelected"/>
                    <div class="signature-dropzone rounded p-4" role="button" @click="$refs.fileInput.click()">
                        <i class="ri-quill-pen-line fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">Click to choose a PNG signature image</p>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="text-center">
                    <div class="text-muted mb-3 fs-13">Drag the signature to center it. The border shows the exact 500 x 200 area that will be saved.</div>
                    <div
                        ref="viewport"
                        class="signature-crop-viewport mx-auto"
                        @mousedown="startDrag"
                        @touchstart="startDrag"
                        @wheel.prevent="onWheel"
                    >
                        <img
                            ref="img"
                            :src="imageSrc"
                            class="signature-crop-image"
                            :style="imageStyle"
                            draggable="false"
                            @load="onImageLoad"
                            @dragstart.prevent
                        />
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-2 mt-3 mx-auto" style="max-width: 260px;">
                        <i class="ri-zoom-out-line text-muted"></i>
                        <input type="range" class="form-range" min="0.2" max="3" step="0.01" v-model.number="zoom" @input="onZoomInput"/>
                        <i class="ri-zoom-in-line text-muted"></i>
                    </div>
                    <button type="button" class="btn btn-link btn-sm mt-1" @click="discardImage">
                        <i class="ri-arrow-go-back-line align-bottom me-1"></i> Choose a different image
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

const VIEWPORT_WIDTH = 400;
const VIEWPORT_HEIGHT = 160;
const OUTPUT_WIDTH = 500;
const OUTPUT_HEIGHT = 200;

export default {
    data() {
        return {
            showModal: false,
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
            form: useForm({
                signature: null,
                option: 'signature',
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
    methods: {
        show() {
            this.reset();
            this.showModal = true;
        },
        onFileSelected(e) {
            const fileInput = e.target;
            const file = fileInput.files[0];
            if (!file) return;

            if (file.type !== 'image/png') {
                alert("Only PNG images are allowed.");
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
        discardImage() {
            this.imageSrc = null;
        },
        onImageLoad(e) {
            this.naturalWidth = e.target.naturalWidth;
            this.naturalHeight = e.target.naturalHeight;
            this.coverScale = Math.max(VIEWPORT_WIDTH / this.naturalWidth, VIEWPORT_HEIGHT / this.naturalHeight);
            this.zoom = 1;
            this.prevZoom = 1;
            const w = this.naturalWidth * this.coverScale;
            const h = this.naturalHeight * this.coverScale;
            this.offsetX = (VIEWPORT_WIDTH - w) / 2;
            this.offsetY = (VIEWPORT_HEIGHT - h) / 2;
        },
        onZoomInput() {
            const ratio = this.zoom / this.prevZoom;
            const centerX = VIEWPORT_WIDTH / 2;
            const centerY = VIEWPORT_HEIGHT / 2;
            this.offsetX = centerX - (centerX - this.offsetX) * ratio;
            this.offsetY = centerY - (centerY - this.offsetY) * ratio;
            this.prevZoom = this.zoom;
            this.constrainOffset();
        },
        onWheel(e) {
            const delta = e.deltaY < 0 ? 0.05 : -0.05;
            this.zoom = Math.min(3, Math.max(0.2, this.zoom + delta));
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
            // Once zoomed smaller than the frame, center that axis instead of
            // pinning it to the top-left corner (which also blocked dragging).
            this.offsetX = (w <= VIEWPORT_WIDTH) ? (VIEWPORT_WIDTH - w) / 2 : Math.min(0, Math.max(VIEWPORT_WIDTH - w, this.offsetX));
            this.offsetY = (h <= VIEWPORT_HEIGHT) ? (VIEWPORT_HEIGHT - h) / 2 : Math.min(0, Math.max(VIEWPORT_HEIGHT - h, this.offsetY));
        },
        upload() {
            if (!this.imageSrc || this.processing) return;
            this.processing = true;

            const canvas = document.createElement('canvas');
            canvas.width = OUTPUT_WIDTH;
            canvas.height = OUTPUT_HEIGHT;
            const outputScale = OUTPUT_WIDTH / VIEWPORT_WIDTH;
            const totalScale = this.coverScale * this.zoom * outputScale;
            canvas.getContext('2d').drawImage(
                this.$refs.img,
                this.offsetX * outputScale,
                this.offsetY * outputScale,
                this.naturalWidth * totalScale,
                this.naturalHeight * totalScale
            );

            canvas.toBlob((blob) => {
                this.form.signature = new File([blob], 'signature.png', { type: 'image/png' });

                this.form.post('/profile', {
                    preserveScroll: true,
                    forceFormData: true,
                    onSuccess: () => {
                        this.$emit('uploaded');
                        this.hide();
                    },
                    onFinish: () => {
                        this.processing = false;
                    },
                });
            }, 'image/png');
        },
        hide() {
            this.showModal = false;
        },
        reset() {
            this.stopDrag();
            this.imageSrc = null;
            this.naturalWidth = 0;
            this.naturalHeight = 0;
            this.coverScale = 1;
            this.zoom = 1;
            this.prevZoom = 1;
            this.offsetX = 0;
            this.offsetY = 0;
            this.processing = false;
            this.form.reset();
        },
    },
}
</script>
<style scoped>
.signature-dropzone {
    border: 2px dashed var(--vz-border-color, #ced4da);
}
.signature-crop-viewport {
    position: relative;
    width: 400px;
    height: 160px;
    max-width: 100%;
    border-radius: 6px;
    overflow: hidden;
    background-color: #e9ecef;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--vz-primary, #405189);
    cursor: move;
    touch-action: none;
    margin-bottom: 0.5rem;
}
.signature-crop-image {
    position: absolute;
    left: 0;
    top: 0;
    max-width: none;
    pointer-events: none;
    user-select: none;
}
</style>
