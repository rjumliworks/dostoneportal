<template>
  <b-modal
    v-model="showModal"
    style="--vz-modal-width: 400px;"
    header-class="p-3 bg-light"
    title="Capture"
    class="v-modal-custom"
    modal-class="zoomIn"
    centered
    no-close-on-backdrop
  >
    <div class="row">
      <div class="col-md-12 text-center">
        <!-- Live Camera Preview -->
        <video
          v-show="showCamera && !photoPreview"
          ref="cameraPreview"
          autoplay
          playsinline
          class="preview rounded border"
        ></video>

        <!-- Captured Image Preview -->
        <img
          v-if="photoPreview"
          :src="photoPreview"
          class="preview rounded border"
          alt="Captured"
        />

        <canvas ref="cameraCanvas" class="d-none"></canvas>

        <!-- Camera Controls -->
        <div class="mt-2">
          <b-button
            v-if="showCamera && !photoPreview"
            size="sm"
            variant="secondary"
            @click="capturePhoto"
          >
            <i class="ri-camera-fill me-1"></i> Capture
          </b-button>
          <b-button
            v-if="photoPreview"
            size="sm"
            variant="light"
            @click="retakePhoto"
          >
            <i class="ri-refresh-line me-1"></i> Retake
          </b-button>
        </div>
      </div>
    </div>

    <template v-slot:footer>
      <b-button @click="hide" variant="light" block>Cancel</b-button>
      <b-button
        v-if="form.photo"
        @click="submit"
        variant="secondary"
        :disabled="form.processing || !photoPreview"
        block
      >
        Submit
      </b-button>
    </template>
  </b-modal>
</template>

<script>
import { useForm } from "@inertiajs/vue3";

export default {
  data() {
    return {
      form: useForm({
        photo: null,
      }),
      showCamera: false,
      cameraStream: null,
      showModal: false,
      photoPreview: null,
    };
  },
  methods: {
    async show() {
      this.showModal = true;
      this.showCamera = true;
      this.photoPreview = null;

      try {
        this.cameraStream = await navigator.mediaDevices.getUserMedia({
          video: { width: 400, height: 400 },
        });
        this.$refs.cameraPreview.srcObject = this.cameraStream;
      } catch (error) {
        console.error("Camera access denied:", error);
        alert("Unable to access camera. Please allow permission.");
      }
    },

    capturePhoto() {
      const video = this.$refs.cameraPreview;
      const canvas = this.$refs.cameraCanvas;
      const context = canvas.getContext("2d");

      // Force fixed 200x200 output
      const size = 200;
      canvas.width = size;
      canvas.height = size;

      // Get video dimensions
      const vw = video.videoWidth;
      const vh = video.videoHeight;
      const minSide = Math.min(vw, vh);

      // Center crop (to make it square)
      const sx = (vw - minSide) / 2;
      const sy = (vh - minSide) / 2;

      // Draw cropped region into 200x200 canvas
      context.drawImage(video, sx, sy, minSide, minSide, 0, 0, size, size);

      // 🕒 Add date/time watermark
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
      this.form.photo = dataURL;
      this.photoPreview = dataURL;

      // Stop camera
      this.stopCamera();
    },

    retakePhoto() {
      this.photoPreview = null;
      this.showCamera = true;
      this.startCamera();
    },

    async startCamera() {
      try {
        this.cameraStream = await navigator.mediaDevices.getUserMedia({
          video: { width: 400, height: 400 },
        });
        this.$refs.cameraPreview.srcObject = this.cameraStream;
      } catch (error) {
        console.error("Camera access error:", error);
      }
    },

    stopCamera() {
      if (this.cameraStream) {
        this.cameraStream.getTracks().forEach((track) => track.stop());
        this.cameraStream = null;
      }
      this.showCamera = false;
    },

    submit() {
      this.$emit("success", this.form.photo);
      this.hide();
    },

    hide() {
      this.stopCamera();
      this.showModal = false;
    },
  },
};
</script>

<style scoped>
.preview {
  width: 200px;
  height: 200px;
  object-fit: cover;
  background: #000;
}
</style>
