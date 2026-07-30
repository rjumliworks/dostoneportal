<template>
    <Head :title="selected.title"/>
    <div class="session-page d-flex justify-content-center align-items-stretch vh-100 bg-light position-relative overflow-hidden" :style="pageHeight ? { height: pageHeight + 'px' } : null">
        <svg class="pillars-page-bg" viewBox="-900 -900 1800 1800" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <path d="M 0 0 L -1400 0 A 1400 1400 0 0 1 0 -1400 Z" fill="#E8940C" opacity="0.035"/>
            <path d="M 0 0 L 0 -1400 A 1400 1400 0 0 1 1400 0 Z" fill="#274F79" opacity="0.035"/>
            <path d="M 0 0 L 1400 0 A 1400 1400 0 0 1 0 1400 Z" fill="#C4577B" opacity="0.035"/>
            <path d="M 0 0 L 0 1400 A 1400 1400 0 0 1 -1400 0 Z" fill="#3D8149" opacity="0.035"/>

            <g
                v-for="(shape, i) in floatingShapes"
                :key="i"
                :transform="`translate(${shape.x},${shape.y})`">
                <g :transform="`rotate(${shape.rotate}) scale(${shape.scale})`" :opacity="shape.opacity">
                    <animateTransform
                        attributeName="transform"
                        type="translate"
                        :values="`0,0; 0,${shape.drift}; 0,0`"
                        :dur="shape.duration + 's'"
                        :begin="shape.delay + 's'"
                        repeatCount="indefinite"
                        additive="sum"/>

                    <template v-if="shape.quadrant === 1">
                        <template v-if="shape.iconIndex === 0">
                            <rect x="-24" y="-22" width="12" height="22" :fill="shape.color"/>
                            <rect x="-8" y="-38" width="12" height="38" :fill="shape.color"/>
                            <rect x="8" y="-55" width="12" height="55" :fill="shape.color"/>
                            <rect x="24" y="-70" width="12" height="70" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 1">
                            <ellipse cx="0" cy="-8" rx="26" ry="9" :fill="shape.color"/>
                            <ellipse cx="0" cy="-21" rx="26" ry="9" :fill="shape.color"/>
                            <ellipse cx="0" cy="-34" rx="26" ry="9" :fill="shape.color"/>
                            <ellipse cx="0" cy="-47" rx="26" ry="9" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 2">
                            <rect x="-4" y="-60" width="8" height="60" :fill="shape.color"/>
                            <circle cx="0" cy="-72" r="18" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 3">
                            <path d="M -26 0 L -10 -30 L 4 -14 L 26 -55" :stroke="shape.color" stroke-width="7" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M 6 -55 L 26 -55 L 26 -35" :stroke="shape.color" stroke-width="7" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </template>
                        <template v-else>
                            <rect x="-24" y="-40" width="48" height="34" rx="4" :fill="shape.color"/>
                            <rect x="-10" y="-50" width="20" height="12" rx="2" fill="none" :stroke="shape.color" stroke-width="5"/>
                        </template>
                    </template>

                    <template v-else-if="shape.quadrant === 2">
                        <template v-if="shape.iconIndex === 0">
                            <path d="M 0 -80 L 28 -66 L 28 -30 Q 28 4 0 18 Q -28 4 -28 -30 L -28 -66 Z" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 1">
                            <rect x="-20" y="-55" width="40" height="55" :fill="shape.color"/>
                            <rect x="-20" y="-65" width="8" height="12" :fill="shape.color"/>
                            <rect x="-8" y="-65" width="8" height="12" :fill="shape.color"/>
                            <rect x="4" y="-65" width="8" height="12" :fill="shape.color"/>
                            <rect x="16" y="-65" width="8" height="12" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 2">
                            <rect x="-18" y="-42" width="36" height="42" rx="4" :fill="shape.color"/>
                            <path d="M -11 -42 L -11 -58 A 11 11 0 0 1 11 -58 L 11 -42" fill="none" :stroke="shape.color" stroke-width="7"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 3">
                            <rect x="-3" y="-20" width="6" height="20" :fill="shape.color"/>
                            <path d="M -32 -20 Q -32 -62 0 -62 Q 32 -62 32 -20 Z" :fill="shape.color"/>
                            <path d="M -32 -20 Q -22 -10 -12 -20" fill="none" :stroke="shape.color" stroke-width="4"/>
                            <path d="M -12 -20 Q -2 -10 8 -20" fill="none" :stroke="shape.color" stroke-width="4"/>
                            <path d="M 8 -20 Q 18 -10 28 -20" fill="none" :stroke="shape.color" stroke-width="4"/>
                        </template>
                        <template v-else>
                            <rect x="-24" y="-40" width="48" height="40" rx="4" :fill="shape.color"/>
                            <circle cx="0" cy="-20" r="10" fill="none" :stroke="shape.color" stroke-width="3"/>
                        </template>
                    </template>

                    <template v-else-if="shape.quadrant === 3">
                        <template v-if="shape.iconIndex === 0">
                            <rect x="-3" y="-25" width="6" height="25" :fill="shape.color"/>
                            <path d="M 0 -30 C -32 -55 -20 -85 0 -65 C 20 -85 32 -55 0 -30 Z" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 1">
                            <circle cx="-14" cy="-58" r="10" :fill="shape.color"/>
                            <path d="M -26 0 Q -26 -42 -14 -42 Q -2 -42 -2 0 Z" :fill="shape.color"/>
                            <circle cx="14" cy="-58" r="10" :fill="shape.color"/>
                            <path d="M 2 0 Q 2 -42 14 -42 Q 26 -42 26 0 Z" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 2">
                            <rect x="-6" y="-70" width="12" height="70" :fill="shape.color"/>
                            <rect x="-24" y="-52" width="48" height="14" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 3">
                            <rect x="-8" y="-40" width="16" height="40" rx="3" :fill="shape.color"/>
                            <rect x="-24" y="-28" width="48" height="16" rx="3" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 4">
                            <path d="M -26 0 Q -26 -35 0 -35 Q 26 -35 26 0" fill="none" :stroke="shape.color" stroke-width="8" stroke-linecap="round"/>
                            <circle cx="0" cy="-48" r="16" :fill="shape.color"/>
                        </template>
                        <template v-else>
                            <ellipse cx="0" cy="0" rx="24" ry="9" fill="none" :stroke="shape.color" stroke-width="3"/>
                            <ellipse cx="0" cy="0" rx="24" ry="9" fill="none" :stroke="shape.color" stroke-width="3" transform="rotate(60)"/>
                            <ellipse cx="0" cy="0" rx="24" ry="9" fill="none" :stroke="shape.color" stroke-width="3" transform="rotate(120)"/>
                            <circle cx="0" cy="0" r="5" :fill="shape.color"/>
                        </template>
                    </template>

                    <template v-else>
                        <template v-if="shape.iconIndex === 0">
                            <rect x="-4" y="-18" width="8" height="18" :fill="shape.color"/>
                            <path d="M 0 -85 L 22 -50 L 10 -50 L 28 -22 L -28 -22 L -10 -50 L -22 -50 Z" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 1">
                            <rect x="-4" y="-14" width="8" height="14" :fill="shape.color"/>
                            <circle cx="0" cy="-42" r="26" :fill="shape.color"/>
                            <circle cx="-14" cy="-28" r="16" :fill="shape.color"/>
                            <circle cx="14" cy="-28" r="16" :fill="shape.color"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 2">
                            <path d="M -14 0 Q -22 -20 -26.8 -40" :stroke="shape.color" stroke-width="6" fill="none" stroke-linecap="round"/>
                            <path d="M -5 0 Q -1 -27.5 1.4 -55" :stroke="shape.color" stroke-width="6" fill="none" stroke-linecap="round"/>
                            <path d="M 5 0 Q 1 -29 -1.4 -58" :stroke="shape.color" stroke-width="6" fill="none" stroke-linecap="round"/>
                            <path d="M 14 0 Q 22 -21 26.8 -42" :stroke="shape.color" stroke-width="6" fill="none" stroke-linecap="round"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 3">
                            <rect x="-3" y="-95" width="6" height="95" :fill="shape.color"/>
                            <circle cx="0" cy="-95" r="5" :fill="shape.color"/>
                            <path d="M 0 -95 Q 0 -107 0 -125" :stroke="shape.color" stroke-width="8" fill="none" stroke-linecap="round"/>
                            <path d="M 0 -95 Q 12.99 -107 25.98 -80" :stroke="shape.color" stroke-width="8" fill="none" stroke-linecap="round"/>
                            <path d="M 0 -95 Q -12.99 -107 -25.98 -80" :stroke="shape.color" stroke-width="8" fill="none" stroke-linecap="round"/>
                        </template>
                        <template v-else-if="shape.iconIndex === 4">
                            <rect x="-2" y="-70" width="4" height="70" :fill="shape.color"/>
                            <g transform="translate(0,-70)">
                                <path d="M 0 0 L -6 -32 Q 0 -38 6 -32 Z" :fill="shape.color"/>
                                <path d="M 0 0 L -6 -32 Q 0 -38 6 -32 Z" :fill="shape.color" transform="rotate(120)"/>
                                <path d="M 0 0 L -6 -32 Q 0 -38 6 -32 Z" :fill="shape.color" transform="rotate(240)"/>
                                <circle cx="0" cy="0" r="4" :fill="shape.color"/>
                            </g>
                        </template>
                        <template v-else>
                            <path d="M 0 0 Q -30 -35 0 -75 Q 30 -35 0 0 Z" :fill="shape.color"/>
                        </template>
                    </template>
                </g>
            </g>
        </svg>

        <div class="session-layout position-relative">
            <div class="session-header text-center mb-5">
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <img src="@assets/images/logos/logo-sm.png" alt="" class="avatar-xs">
                    <img src="@assets/images/logos/bagongpilipinas.png" alt="" class="avatar-xs">
                </div>
                <h1 class="session-title mb-0">{{ selected.title }}</h1>
                <p class="session-event mb-0">{{ selected.event.name }}</p>
                <p class="session-venue mb-0">{{ selected.venue.address }}</p>
            </div>

            <div class="session-body">
                <div class="session-camera-col">
                    <div class="camera-stage" :style="cameraSize ? { height: cameraSize + 'px', width: cameraSize + 'px' } : null">
                        <div class="camera-ring" :class="'ring-' + ringState" :style="{ '--ring-color': ringColor }">
                            <div class="camera-circle">
                                <video
                                    ref="video"
                                    autoplay
                                    playsinline
                                    class="camera-circle-video">
                                </video>

                                <div class="camera-shutter" :class="{ 'shutter-open': faceDetected }">
                                    <img src="@assets/images/logos/logo-sm.png" alt="" class="brand-spinner">
                                </div>

                                <div class="capture-flash" v-if="flashActive"></div>

                                <div class="capture-result capture-result-success" v-if="ringState === 'success'">
                                    <i class="ri-check-line"></i>
                                </div>
                                <div class="capture-result capture-result-warning" v-else-if="ringState === 'duplicate'">
                                    <i class="ri-error-warning-line"></i>
                                </div>
                                <div class="capture-result capture-result-error" v-else-if="ringState === 'error'">
                                    <i class="ri-close-line"></i>
                                </div>

                                <button
                                    v-if="faceDetected"
                                    type="button"
                                    class="btn-scan-trigger"
                                    :disabled="isScanning"
                                    @click="captureFrame">
                                    <span class="spinner-border spinner-border-sm" v-if="isScanning"></span>
                                    <i class="ri-camera-fill" v-else></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- <p class="camera-hint text-center mt-3 mb-0">Position your face in the frame, then tap the camera to check in.</p> -->
                </div>

                <div class="session-info-col" ref="infoCol">
                    <div class="status-card" :class="'status-card-' + (status || 'idle')">
                        <div v-if="status === 'Success'" class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img :src="employee.avatar" alt="user-img" class="status-avatar">
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-16 text-uppercase fw-semibold">{{ employee.name }}</h5>
                                <p class="text-muted mb-0 fs-12">{{ employee.affiliation }}</p>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0">{{ employee.datetime }}</h6>
                                <p class="text-muted mb-0 fs-12">{{ employee.datetype }}</p>
                            </div>
                        </div>
                        <div v-else-if="status === 'Duplicate'" class="text-center">
                            <p class="mb-0 fw-bold fs-14">Duplicate attendance detected<span v-if="employee"> for <b class="text-uppercase">{{ employee.name }}</b></span></p>
                            <p class="mb-0 fs-12">This participant's attendance has already been recorded. No additional entry is needed.</p>
                        </div>
                        <div v-else-if="status === 'Error'" class="text-center">
                            <p class="mb-0 fw-bold fs-14">Participant not registered in the session.</p>
                            <p class="mb-0 fs-12">No matching participant was found based on the face data. Please register first.</p>
                        </div>
                        <div v-else class="text-center">
                            <p class="mb-0 fw-semibold fs-14">Ready to check in</p>
                            <p class="mb-0 fs-12">Face the camera and tap the shutter button to record your attendance.</p>
                        </div>
                    </div>

                    <div class="attendees-card">
                        <div class="attendees-card-header">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <span class="attendees-icon">
                                        <i class="ri-file-list-3-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-14">List of Attendees</h5>
                                    <p class="text-muted mb-0 fs-12">Shows participants who have successfully scanned the QR code.</p>
                                </div>
                            </div>
                        </div>
                        <div class="attendees-card-body">
                            <div class="table-responsive attendees-table-wrap">
                                <table class="table table-nowrap align-middle mb-0">
                                    <thead class="bg-light thead-fixed">
                                        <tr class="fs-11">
                                            <th width="8%"></th>
                                            <th>Name</th>
                                            <th width="30%" class="text-center">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="attendees.length">
                                        <tr v-for="(list, index) in attendees" :key="index">
                                            <td class="text-center">
                                                <img :src="list.avatar" class="rounded-circle avatar-xs" style="object-fit:cover;" alt="Avatar">
                                            </td>
                                            <td>
                                                <h5 class="fs-12 mb-0 fw-semibold">{{ list.name }}</h5>
                                                <p class="fs-11 text-muted mb-0">{{ list.affiliation }}</p>
                                            </td>
                                            <td class="text-center">{{ list.datetime }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr><td colspan="3" class="text-center text-muted">No participants found.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio ref="successBeep" src="/sounds/success.mp3"></audio>
    <audio ref="errorBuzz" src="/sounds/error.mp3"></audio>
</template>
<script>
import FingerprintJS from '@fingerprintjs/fingerprintjs';
import * as faceapi from 'face-api.js';

// Same colors as the DOST pillar theme used elsewhere: Wealth Creation, Wealth Protection, Human Well Being, Sustainability
const PILLAR_PALETTES = [
    ['#F4A623', '#E8940C', '#FFC857', '#D98C0F'],
    ['#2E5C8A', '#1F4568', '#4A80B5', '#274F79'],
    ['#D14D72', '#B83A5D', '#EA8CA6', '#C4577B'],
    ['#4C9A5B', '#3D8149', '#78C187', '#469153']
];

function randBetween(min, max) {
    return Math.random() * (max - min) + min;
}

// Randomly scattered, slow-drifting low-opacity pillar icons filling the full-page background,
// covering whatever screen size renders them (preserveAspectRatio="slice" always covers the viewport).
function generateFloatingShapes() {
    const perQuadrant = 20;
    const iconCounts = [5, 5, 6, 6];
    const quadrants = [
        { quadrant: 1, signX: -1, signY: -1 },
        { quadrant: 2, signX: 1, signY: -1 },
        { quadrant: 3, signX: 1, signY: 1 },
        { quadrant: 4, signX: -1, signY: 1 }
    ];

    const shapes = [];
    quadrants.forEach(({ quadrant, signX, signY }) => {
        const iconCount = iconCounts[quadrant - 1];
        for (let i = 0; i < perQuadrant; i++) {
            const palette = PILLAR_PALETTES[quadrant - 1];
            shapes.push({
                quadrant,
                iconIndex: i % iconCount,
                color: palette[Math.floor(randBetween(0, palette.length))],
                x: signX * randBetween(60, 820),
                y: signY * randBetween(60, 820),
                rotate: randBetween(0, 360),
                scale: randBetween(0.7, 1.6),
                opacity: randBetween(0.14, 0.26),
                duration: randBetween(6, 12),
                delay: -randBetween(0, 10),
                drift: (Math.random() < 0.5 ? -1 : 1) * randBetween(12, 28)
            });
        }
    });

    return shapes;
}

export default {
    layout: null,
    props: ['session'],
    data() {
        return {
            selected: this.session.data,
            employee: null,
            status: '',
            captureFeedback: null,
            isScanning: false,
            flashActive: false,
            statusTimeout: null,
            captureFeedbackTimeout: null,
            cameraStream: null,
            deviceId: null,
            attendees: this.session.data.attendees ?? [],
            floatingShapes: generateFloatingShapes(),
            pageHeight: null,
            cameraSize: null,
            faceDetected: false,
            detectionRunning: false,
            detectionTimeout: null,
            missedFrames: 0
        };
    },
    computed: {
        ringState() {
            if (this.isScanning) return 'running';
            if (this.captureFeedback === 'Success') return 'success';
            if (this.captureFeedback === 'Duplicate') return 'duplicate';
            if (this.captureFeedback === 'Error') return 'error';
            return 'idle';
        },
        ringColor() {
            switch (this.ringState) {
                case 'running': return '#2E5C8A';
                case 'success': return '#34d399';
                case 'duplicate': return '#f0ad4e';
                case 'error': return '#dc3545';
                default: return '#adb5bd';
            }
        }
    },
    mounted() {
        this.pageHeight = window.innerHeight;
        this.$nextTick(async () => {
            this.lockCameraSize();
            this.initDeviceId();
            try {
                await this.initCamera();
                await this.loadModels();
                this.startDetectionLoop();
            } catch (e) {
                console.error('Failed to start camera/face detection:', e);
            }
        });
        this.keepAliveInterval = setInterval(() => {
            axios.get('/keep-alive');
        }, 1000 * 60 * 30);
    },
    beforeUnmount() {
        clearInterval(this.keepAliveInterval);
        if (this.statusTimeout) {
            clearTimeout(this.statusTimeout);
        }
        if (this.captureFeedbackTimeout) {
            clearTimeout(this.captureFeedbackTimeout);
        }
        this.detectionRunning = false;
        if (this.detectionTimeout) {
            clearTimeout(this.detectionTimeout);
        }
        if (this.cameraStream) {
            this.cameraStream.getTracks().forEach(track => track.stop());
        }
    },
    methods: {
        lockCameraSize() {
            const infoCol = this.$refs.infoCol;
            if (!infoCol) return;
            const heightBudget = infoCol.offsetHeight * 0.7;
            const widthBudget = window.innerWidth * 0.38;
            this.cameraSize = Math.max(140, Math.min(heightBudget, widthBudget));
        },
        async initDeviceId() {
            try {
                const fp = await FingerprintJS.load();
                const result = await fp.get();
                this.deviceId = result.visitorId;
            } catch (e) {
                console.error('Failed to load FingerprintJS:', e);
            }
        },
        resetStatusTimer(duration = 8000) {
            if (this.statusTimeout) {
                clearTimeout(this.statusTimeout);
            }
            this.statusTimeout = setTimeout(() => {
                this.status = null;
                this.employee = null;
                this.statusTimeout = null;
            }, duration);
        },
        resetCaptureFeedback(duration = 2000) {
            if (this.captureFeedbackTimeout) {
                clearTimeout(this.captureFeedbackTimeout);
            }
            this.captureFeedbackTimeout = setTimeout(() => {
                this.captureFeedback = null;
                this.captureFeedbackTimeout = null;
            }, duration);
        },
        async initCamera() {
            this.cameraStream = await navigator.mediaDevices.getUserMedia({ video: true });
            this.$refs.video.srcObject = this.cameraStream;
        },
        async loadModels() {
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
        },
        startDetectionLoop() {
            const detectorOptions = new faceapi.TinyFaceDetectorOptions({ scoreThreshold: 0.3 });
            const missesBeforeClose = 8;
            this.detectionRunning = true;

            const runDetection = async () => {
                if (!this.detectionRunning) return;

                try {
                    const video = this.$refs.video;
                    if (video && video.readyState === 4) {
                        const result = await faceapi.detectSingleFace(video, detectorOptions);

                        if (result) {
                            this.missedFrames = 0;
                            this.faceDetected = true;
                        } else {
                            this.missedFrames++;
                            if (this.missedFrames >= missesBeforeClose) {
                                this.faceDetected = false;
                            }
                        }
                    }
                } catch (e) {
                    console.error('Face detection tick failed:', e);
                } finally {
                    if (this.detectionRunning) {
                        this.detectionTimeout = setTimeout(runDetection, 150);
                    }
                }
            };

            runDetection();
        },
        async captureFrame() {
            if (this.isScanning) return;

            const video = this.$refs.video;
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Camera-style flash right at the instant the frame is grabbed
            this.flashActive = true;
            setTimeout(() => {
                this.flashActive = false;
            }, 250);

            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
            const formData = new FormData();
            formData.append('image', blob, 'capture.jpg');
            formData.append('device', this.deviceId);
            formData.append('session_id', this.selected.id);
            formData.append('option', 'dtr');

            try {
                this.isScanning = true;
                const res = await axios.post('/recognize', formData);
                const data = res.data;

                this.status = data.info;
                this.captureFeedback = data.info;
                this.resetCaptureFeedback();

                if (data.info === 'Success' || data.info === 'Duplicate') {
                    this.employee = data.data ? { ...data.data } : null;

                    if (data.info === 'Duplicate') {
                        this.$refs.errorBuzz.play();
                        setTimeout(() => {
                            this.speak('Duplicate attendance detected');
                        }, 600);
                    }

                    if (data.info === 'Success') {
                        this.attendees.unshift(data.data);
                        this.$refs.successBeep.play();
                        setTimeout(() => {
                            this.speak('Your attendance has been recorded successfully.');
                        }, 600);
                    }
                }
                this.resetStatusTimer();
            } catch (e) {
                this.$refs.errorBuzz.play();
                setTimeout(() => {
                    this.speak('Participant not found.');
                }, 600);
                this.status = 'Error';
                this.captureFeedback = 'Error';
                this.resetCaptureFeedback();
                this.resetStatusTimer(2000);
            } finally {
                this.isScanning = false;
            }
        },
        speak(text) {
            const message = new SpeechSynthesisUtterance(text);
            message.lang = 'en-US';
            speechSynthesis.speak(message);
        }
    }
}
</script>

<style scoped>
@property --fill-percent {
    syntax: '<percentage>';
    inherits: false;
    initial-value: 0%;
}

.pillars-page-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
}

.session-page {
    padding: 1in 1.5rem;
    box-sizing: border-box;
}

@media (min-width: 1920px) {
    .session-page {
        padding: 1.5in 1.5rem;
    }
}

.session-layout {
    z-index: 1;
    width: 100%;
    max-width: 1600px;
    padding: 0 1.5rem;
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.session-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #274F79;
}

.session-event {
    font-size: 0.95rem;
    font-weight: 600;
    color: #E8940C;
}

.session-venue {
    font-size: 0.8rem;
    color: #3D8149;
}

.session-body {
    display: flex;
    flex: 1;
    min-height: 0;
    flex-wrap: wrap;
    align-items: stretch;
    justify-content: center;
    gap: 5rem;
}

@media (min-width: 900px) {
    .session-body {
        flex-wrap: nowrap;
    }
}

.session-camera-col {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.camera-hint {
    max-width: 260px;
    font-size: 0.8rem;
    color: #6c757d;
}

.camera-stage {
    height: 70%;
    aspect-ratio: 1 / 1;
    min-height: 140px;
    min-width: 140px;
    max-height: 38vw;
}

.camera-ring {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: conic-gradient(var(--ring-color, #adb5bd) 0% 100%);
    transition: background 0.4s ease;
}

.camera-ring.ring-running {
    background: conic-gradient(var(--ring-color, #2E5C8A) var(--fill-percent, 0%), #e9ecef var(--fill-percent, 0%) 100%);
    animation: ring-fill 1.4s ease-in-out infinite;
}

@keyframes ring-fill {
    0% {
        --fill-percent: 0%;
    }
    100% {
        --fill-percent: 100%;
    }
}

.camera-circle {
    position: relative;
    box-sizing: border-box;
    width: 92%;
    height: 92%;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #ffffff;
    box-shadow: 0 0 14px rgba(0, 0, 0, 0.2);
}

.camera-circle-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scaleX(-1);
}

.camera-shutter {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f1f3f5;
    color: #adb5bd;
    transition: transform 0.6s ease, opacity 0.6s ease;
}

.brand-spinner {
    width: 40%;
    height: 40%;
    object-fit: contain;
    animation: brand-spin 3s linear infinite;
}

@keyframes brand-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.shutter-open {
    transform: scale(1.2);
    opacity: 0;
    pointer-events: none;
}

.capture-flash {
    position: absolute;
    inset: 0;
    z-index: 3;
    background-color: #ffffff;
    animation: capture-flash-fade 0.25s ease-out forwards;
    pointer-events: none;
}

@keyframes capture-flash-fade {
    from { opacity: 0.9; }
    to { opacity: 0; }
}

.capture-result {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: capture-result-pop 0.3s ease;
}

.capture-result i {
    color: #ffffff;
    font-size: 4rem;
}

.capture-result-success {
    background-color: rgba(52, 211, 153, 0.82);
}

.capture-result-warning {
    background-color: rgba(240, 173, 78, 0.85);
}

.capture-result-error {
    background-color: rgba(220, 53, 69, 0.82);
}

@keyframes capture-result-pop {
    from { opacity: 0; transform: scale(0.6); }
    to { opacity: 1; transform: scale(1); }
}

.btn-scan-trigger {
    position: absolute;
    bottom: 10%;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.65);
    background-color: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    color: #ffffff;
    font-size: 1.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 4;
    cursor: pointer;
    transition: opacity 0.2s ease, transform 0.15s ease;
}

.btn-scan-trigger:not(:disabled):active {
    transform: translateX(-50%) scale(0.92);
}

.btn-scan-trigger:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.session-info-col {
    width: 100%;
    max-width: 860px;
    min-width: 0;
    min-height: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-card {
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
    background-color: #f1f3f5;
    border: 1px solid #e9ecef;
    min-height: 84px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

.status-card > * {
    width: 100%;
}

.status-card-Success {
    background-color: rgba(52, 211, 153, 0.15);
    border-color: rgba(52, 211, 153, 0.4);
    color: #14663f;
}

.status-card-Duplicate {
    background-color: rgba(240, 173, 78, 0.18);
    border-color: rgba(240, 173, 78, 0.4);
    color: #8a5a00;
}

.status-card-Error {
    background-color: rgba(220, 53, 69, 0.12);
    border-color: rgba(220, 53, 69, 0.4);
    color: #8b1a24;
}

.status-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    object-fit: cover;
}

.attendees-card {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 0.75rem;
    overflow: hidden;
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}

.attendees-card-header {
    padding: 0.9rem 1.1rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    flex-shrink: 0;
}

.attendees-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    background-color: rgba(39, 79, 121, 0.12);
    color: #274F79;
    font-size: 1.1rem;
}

.attendees-card-body {
    padding: 0;
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}

.attendees-table-wrap {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    overflow-x: hidden;
}
</style>
