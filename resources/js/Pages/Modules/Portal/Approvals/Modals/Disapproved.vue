<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 550px;" header-class="p-3 bg-light" title="Disapprove Request" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
         <div class="d-flex w-100 p-2 justify-content-center align-items-center">
            <div class="p-4 w-100 border rounded bg-light-subtle text-center">
                <h1 class="bx-tada"><i class="ri-close-circle-fill text-danger"></i></h1>
                <p class="mb-3 text-danger fw-semibold">Are you sure you want to disapprove this <b>{{ type }}</b> request?</p>
                <p class="mb-0 text-dark fs-11">
                    Please ensure you have reviewed the details before proceeding. Once disapproved, 
                    this request will be marked as rejected and the requester will be notified of your decision.
                </p>
                
            </div>
        </div>
        <!-- <input type="text" v-model="form.comment" class="form-control chat-input bg-light border-light" placeholder="Add Comment.." /> -->
        <BRow class="g-3 p-2">
            <BCol lg="12" class="mt-3">
                <Textarea id="name" v-model="form.reason" type="text" rows="3" placeholder="Add reason" class="form-control" :class="{ 'is-invalid': form.errors.reason }" @input="handleInput('reason')" :light="true"/>
            </BCol>
            
        </BRow>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="danger" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
    export default {
        components: { Textarea },
        data() {
            return {
                form: useForm({
                    id: null,
                    request_id: null,
                    type: null,
                    status_id: 30,
                    photo: null,
                    reason: null,
                    option: "status",
                }),
                type: null,
                showModal: false,
                cameraStream: null,
            };
        },
        methods: {
            show(id, type, request_id) {
                this.form.id = id;
                this.form.request_id = request_id;
                this.form.type = type;
                this.type = type;
                this.showModal = true;
            },

            async submit() {
                try {
                    // 1️⃣ Capture photo first
                    const photo = await this.autoCapturePhoto();
                    if (!photo) {
                        alert("Camera access failed or denied.");
                        return;
                    }

                    // 2️⃣ Attach photo to form
                    this.form.photo = photo;

                    // 3️⃣ Submit to backend
                    this.form.put("/approvals/update", {
                        preserveScroll: true,
                        onSuccess: () => {
                            this.form.reset();
                            this.hide();
                        },
                        onError: () => {
                            alert("Submission failed. Please try again.");
                        },
                    });
                } catch (error) {
                    console.error("Auto capture error:", error);
                    alert("Unable to capture photo.");
                }
            },

            async autoCapturePhoto() {
                try {
                    // Request camera permission
                    this.cameraStream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: 400,
                            height: 400
                        },
                    });

                    const video = document.createElement("video");
                    video.autoplay = true;
                    video.playsInline = true;
                    video.srcObject = this.cameraStream;

                    // Wait for camera ready
                    await new Promise((resolve) => {
                        video.onloadedmetadata = () => resolve();
                    });

                    // Wait a short time for focus/exposure
                    await new Promise((resolve) => setTimeout(resolve, 1000));

                    // Create hidden canvas
                    const canvas = document.createElement("canvas");
                    const context = canvas.getContext("2d");
                    const size = 200;
                    canvas.width = size;
                    canvas.height = size;

                    const vw = video.videoWidth;
                    const vh = video.videoHeight;
                    const minSide = Math.min(vw, vh);
                    const sx = (vw - minSide) / 2;
                    const sy = (vh - minSide) / 2;

                    // Draw video frame
                    context.drawImage(video, sx, sy, minSide, minSide, 0, 0, size, size);

                    // Add timestamp
                    const now = new Date();
                    const timestamp = now.toLocaleString("en-PH", {
                        dateStyle: "medium",
                        timeStyle: "short",
                    });
                    context.font = "12px Arial";
                    context.fillStyle = "rgba(255,255,255,0.9)";
                    context.strokeStyle = "rgba(0,0,0,0.7)";
                    context.lineWidth = 2;
                    const x = 8;
                    const y = size - 10;
                    context.strokeText(timestamp, x, y);
                    context.fillText(timestamp, x, y);

                    // Convert to Base64
                    const dataURL = canvas.toDataURL("image/png");

                    // Stop the camera
                    this.stopCamera(video);

                    return dataURL;
                } catch (error) {
                    console.error("Camera error:", error);
                    this.stopCamera();
                    return null;
                }
            },

            stopCamera(videoEl) {
                if (this.cameraStream) {
                    this.cameraStream.getTracks().forEach((track) => track.stop());
                    this.cameraStream = null;
                }
                if (videoEl && videoEl.srcObject) {
                    videoEl.srcObject = null;
                }
            },
            handleInput(field) {
                this.form.errors[field] = false;
            },
            hide() {
                this.showModal = false;
            },
        },
    };

</script>
