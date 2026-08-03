<template>
    <Head title="VIP Registration — RSTW 2026" />

    <div class="rstw">
        <section class="vip-section">
            <div class="vip-container">
                <div class="vip-card">
                    <div class="vip-card__header">
                        <img src="/images/2026/logo.png" alt="OneDOST4U" class="vip-card__logo">
                        <div class="vip-card__text">
                            <span class="vip-card__label">Department of Science and Technology</span>
                            <h3 class="vip-card__title">VIP Registration</h3>
                        </div>
                        <img src="/images/2026/2026RSTW.png" alt="2026 RSTW" class="vip-card__brand">
                    </div>

                    <div v-if="submitted" class="vip-success">
                        <div class="vip-success__icon"><i class="ri-checkbox-circle-fill"></i></div>
                        <h3 class="vip-success__title">You're registered!</h3>
                        <p class="vip-success__text">Thank you, {{ lastName }}. The VIP registration has been received.</p>
                        <button type="button" class="vip-btn vip-btn--ghost" @click="resetForm">Register another VIP</button>
                    </div>

                    <form v-else class="vip-form" @submit.prevent="submit" enctype="multipart/form-data">
                        <ul v-if="errorMessages.length" class="vip-error-summary">
                            <li v-for="(msg, key) in form.errors" :key="key" v-show="msg">{{ msg }}</li>
                        </ul>

                        <div class="vip-field">
                            <label>Avatar</label>
                            <div
                                class="vip-photo"
                                :class="{ 'is-captured': !!avatarPreview && !form.errors.avatar, 'is-invalid': !!avatarPreview && !!form.errors.avatar, 'is-live': cameraOn }">
                                <div class="vip-photo__stage">
                                    <video
                                        v-show="cameraOn && !avatarPreview"
                                        ref="video"
                                        class="vip-photo__video"
                                        autoplay
                                        playsinline
                                        muted
                                    ></video>
                                    <img v-if="avatarPreview" :src="avatarPreview" alt="Avatar" class="vip-photo__img" :class="{ 'vip-photo__img--mirrored': avatarSource === 'camera' }">
                                    <span v-if="cameraOn && !avatarPreview" class="vip-photo__live"><i class="ri-record-circle-fill"></i> Live</span>
                                    <div v-if="!cameraOn && !avatarPreview" class="vip-photo__placeholder">
                                        <i class="ri-user-3-line"></i>
                                    </div>
                                </div>

                                <p v-if="avatarPreview && form.errors.avatar" class="vip-photo__caption vip-photo__caption--error">
                                    <i class="ri-close-circle-fill"></i> Avatar failed
                                </p>
                                <p v-else-if="avatarPreview" class="vip-photo__caption vip-photo__caption--ok">
                                    <i class="ri-checkbox-circle-fill"></i> {{ avatarSource === 'upload' ? 'Photo uploaded' : 'Avatar captured' }}
                                </p>

                                <div class="vip-photo__actions">
                                    <template v-if="!cameraOn && !avatarPreview">
                                        <button type="button" class="vip-photo__btn" @click="startCamera">
                                            <i class="ri-camera-line"></i> Open Camera
                                        </button>
                                        <button type="button" class="vip-photo__btn" @click="$refs.avatarFile.click()">
                                            <i class="ri-upload-2-line"></i> Upload
                                        </button>
                                    </template>
                                    <button
                                        v-if="cameraOn && !avatarPreview"
                                        type="button"
                                        class="vip-photo__btn vip-photo__btn--capture"
                                        @click="captureAvatar"
                                    >
                                        <i class="ri-camera-fill"></i> Capture
                                    </button>
                                    <template v-if="avatarPreview">
                                        <button v-if="avatarSource !== 'upload'" type="button" class="vip-photo__btn" @click="retakeAvatar">
                                            <i class="ri-camera-line"></i> Retake
                                        </button>
                                        <button type="button" class="vip-photo__btn" @click="$refs.avatarFile.click()">
                                            <i class="ri-upload-2-line"></i> {{ avatarSource === 'upload' ? 'Re-upload' : 'Upload' }}
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <input ref="avatarFile" type="file" accept="image/*" class="vip-photo__file" @change="onAvatarFile">
                            <canvas ref="avatarCanvas" class="vip-photo__canvas"></canvas>
                            <span v-if="cameraError" class="vip-form__error">{{ cameraError }}</span>
                        </div>

                        <div class="vip-field" :class="{ 'has-error': form.errors.name }">
                            <label>Full Name</label>
                            <input type="text" v-model="form.name" placeholder="Juan Dela Cruz" @input="clearError('name')">
                        </div>

                        <div class="vip-field" :class="{ 'has-error': form.errors.designation }">
                            <label>Designation</label>
                            <input type="text" v-model="form.designation" placeholder="e.g. Regional Director" @input="clearError('designation')">
                        </div>

                        <div class="vip-field" :class="{ 'has-error': form.errors.affiliation }">
                            <label>Affiliation</label>
                            <input type="text" v-model="form.affiliation" placeholder="e.g. DOST Region IX" @input="clearError('affiliation')">
                        </div>

                        <button type="submit" class="vip-btn vip-btn--primary" :disabled="form.processing">
                            <span v-if="form.processing"><i class="ri-loader-4-line vip-spin"></i> Submitting…</span>
                            <span v-else>Submit Registration</span>
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import { Head, useForm } from '@inertiajs/vue3';

export default {
    layout: null,
    components: { Head },
    data() {
        return {
            cameraStream: null,
            cameraOn: false,
            cameraError: null,
            avatarPreview: null,
            avatarSource: null,   // 'camera' | 'upload' — drives the success caption
            submitted: false,
            lastName: '',
            form: useForm({
                name: null,
                designation: null,
                affiliation: null,
                avatar: null,
            }),
        };
    },
    computed: {
        errorMessages() {
            return Object.values(this.form.errors).filter(Boolean);
        },
    },
    methods: {
        clearError(field) {
            if (this.form.errors[field]) this.form.clearErrors(field);
        },
        async startCamera() {
            this.cameraError = null;
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
                this.cameraStream.getTracks().forEach((t) => t.stop());
                this.cameraStream = null;
            }
            const video = this.$refs.video;
            if (video) video.srcObject = null;
            this.cameraOn = false;
        },
        captureAvatar() {
            const video = this.$refs.video;
            const canvas = this.$refs.avatarCanvas;
            if (!video || !canvas || !video.videoWidth) return;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            this.avatarPreview = canvas.toDataURL('image/jpeg', 0.9);
            this.avatarSource = 'camera';
            canvas.toBlob(
                (blob) => {
                    this.form.avatar = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
                    this.clearError('avatar');
                },
                'image/jpeg',
                0.9
            );
            this.stopCamera();
        },
        retakeAvatar() {
            this.avatarPreview = null;
            this.avatarSource = null;
            this.form.avatar = null;
            this.clearError('avatar');
            this.startCamera();
        },
        onAvatarFile(e) {
            const file = e.target.files?.[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                this.cameraError = 'Please choose an image file.';
                e.target.value = '';
                return;
            }
            if (file.size > 8 * 1024 * 1024) {
                this.cameraError = 'That image is too large — please pick a file under 8 MB.';
                e.target.value = '';
                return;
            }

            this.cameraError = null;
            this.stopCamera();

            const reader = new FileReader();
            reader.onload = () => { this.avatarPreview = reader.result; };
            reader.readAsDataURL(file);

            this.avatarSource = 'upload';
            this.form.avatar = file;
            this.clearError('avatar');

            e.target.value = '';
        },
        submit() {
            this.form.clearErrors();

            const errs = {};
            if (!this.form.name?.trim()) errs.name = "Please enter the VIP's full name.";
            if (!this.form.designation?.trim()) errs.designation = 'Please enter a designation.';
            if (!this.form.affiliation?.trim()) errs.affiliation = 'Please enter an affiliation.';
            if (!this.form.avatar) errs.avatar = 'Please capture or upload a photo.';

            if (Object.keys(errs).length) {
                this.form.setError(errs);
                return;
            }

            this.lastName = this.form.name;
            this.form.post('/register-vip', {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.submitted = true;
                },
            });
        },
        resetForm() {
            this.submitted = false;
            this.form.reset();
            this.form.clearErrors();
            this.avatarPreview = null;
            this.avatarSource = null;
        },
    },
    beforeUnmount() {
        this.stopCamera();
    },
};
</script>

<style scoped>
/* Brand tokens are normally declared on .rstw in Rstw.vue; this page stands on
   its own, so it declares the ones the form and chrome need. */
.rstw {
    --ink: #211215;
    --brand: #E22032;
    --brand-2: #EC864C;
    --accent: #F1A442;
    --c-red: #9C2424;
    --c-red-2: #E22032;
    --c-blue: #144C8D;
    --c-blue-2: #46A0C1;
    --c-blue-3: #5386BF;
    --c-orange: #EC864C;
    --c-gold: #F1A442;
    --cream: #FAF6EC;
    font-family: 'Agrandir', 'Roboto', system-ui, -apple-system, Segoe UI, sans-serif;
    color: var(--ink);
    background: #ffffff url('/images/2026/bg.png') center / cover no-repeat fixed;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.vip-section {
    flex: 1;
    padding: 32px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.vip-container { width: 100%; max-width: 460px; }

.vip-card {
    position: relative;
    background: #fff;
    border: 1px solid rgba(11, 17, 32, .08);
    border-radius: 24px;
    padding: 28px 26px 30px;
    box-shadow:
        0 1px 3px rgba(11, 17, 32, .04),
        0 10px 24px rgba(11, 17, 32, .06),
        0 34px 60px rgba(11, 17, 32, .08);
}
.vip-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    border-radius: 24px 24px 0 0;
    background: linear-gradient(90deg, var(--c-blue), var(--c-blue-2), var(--c-gold), var(--c-orange), var(--c-red));
}

.vip-card__header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(11, 17, 32, .08);
}
.vip-card__logo { height: 36px; width: auto; flex-shrink: 0; }
.vip-card__text { flex: 1; min-width: 0; }
.vip-card__label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--c-blue);
}
.vip-card__title { margin: 2px 0 0; font-size: 16px; font-weight: 800; color: var(--ink); }
.vip-card__brand { height: 30px; width: auto; flex-shrink: 0; }

.vip-error-summary {
    list-style: none;
    margin: 0 0 16px;
    padding: 12px 14px;
    border-radius: 12px;
    background: rgba(220, 38, 38, .06);
    border: 1px solid rgba(220, 38, 38, .3);
    color: #b91c1c;
    font-size: 12.5px;
    font-weight: 600;
    line-height: 1.5;
}

.vip-form { display: flex; flex-direction: column; gap: 16px; }

.vip-field { display: flex; flex-direction: column; min-width: 0; }
.vip-field label {
    font-size: 11px;
    font-weight: 600;
    color: var(--ink);
    margin-bottom: 5px;
    letter-spacing: .02em;
}
.vip-field input[type="text"] {
    padding: 11px 14px;
    border-radius: 12px;
    border: 1px solid #e4e8ef;
    font-size: 14px;
    color: var(--ink);
    background: #fbfbfd;
    transition: border-color .2s, box-shadow .2s;
}
.vip-field input[type="text"]:focus {
    outline: none;
    border-color: var(--c-blue-2);
    box-shadow: 0 0 0 3px rgba(70, 160, 193, .15);
    background: #fff;
}
.vip-field.has-error label { color: #dc2626; }
.vip-field.has-error input[type="text"] { border-color: #dc2626; }
.vip-form__error { display: block; margin-top: 6px; font-size: 11.5px; font-weight: 600; color: #dc2626; text-align: center; }

.vip-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 26px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 14px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s, opacity .2s;
}
.vip-btn:hover { transform: translateY(-2px); }
.vip-btn:disabled { opacity: .65; cursor: not-allowed; transform: none; }
.vip-btn--primary {
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    color: #fff;
    box-shadow: 0 12px 30px rgba(226, 32, 50, .28);
}
.vip-btn--ghost {
    background: #fff;
    color: var(--ink);
    border: 1.5px solid #e4e8ef;
}
.vip-btn--ghost:hover { border-color: #c7ccd6; }

.vip-spin { display: inline-block; animation: vip-spin 1s linear infinite; }
@keyframes vip-spin { to { transform: rotate(360deg); } }

/* ---------- Avatar capture ---------- */
.vip-photo { display: flex; flex-direction: column; align-items: center; gap: 8px; }
.vip-photo__stage {
    position: relative;
    width: 100%;
    max-width: 160px;
    aspect-ratio: 1;
    border: 2px dashed #d7b9a0;
    border-radius: 50%;
    background: #fffdf8;
    overflow: hidden;
    clip-path: circle(50%);
    display: grid;
    place-items: center;
    margin: 0 auto;
}
.vip-photo.is-captured .vip-photo__stage {
    border-style: solid;
    border-color: #34a853;
    box-shadow: 0 0 0 3px rgba(52, 168, 83, .15);
}
.vip-photo.is-invalid .vip-photo__stage {
    border-style: solid;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, .15);
}
.vip-photo.is-live .vip-photo__stage {
    border-style: solid;
    border-color: #f1a442;
    box-shadow: 0 0 0 3px rgba(241, 164, 66, .18);
}
.vip-photo__video,
.vip-photo__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 28%;
}
.vip-photo__video { transform: scaleX(-1); }
.vip-photo__img--mirrored { transform: scaleX(-1); }
.vip-photo__img { animation: vip-photo-in .35s ease; }
@keyframes vip-photo-in {
    from { opacity: 0; transform: scale(1.04); }
    to   { opacity: 1; transform: scale(1); }
}
.vip-photo__img--mirrored.vip-photo__img { animation: vip-photo-in-mirror .35s ease; }
@keyframes vip-photo-in-mirror {
    from { opacity: 0; transform: scaleX(-1) scale(1.04); }
    to   { opacity: 1; transform: scaleX(-1) scale(1); }
}
.vip-photo__caption {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin: 0;
    text-align: center;
    font-size: 12px;
    line-height: 1.5;
    color: #9a8574;
}
.vip-photo__caption--ok { color: #2e8b4f; font-weight: 700; }
.vip-photo__caption--error { color: #dc2626; font-weight: 700; }
.vip-photo__live {
    position: absolute;
    left: 8px;
    top: 8px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #fff;
    background: rgba(33, 18, 21, .6);
    padding: 3px 9px;
    border-radius: 999px;
}
.vip-photo__live i { color: var(--brand); animation: vip-blink 1.2s steps(2, start) infinite; }
@keyframes vip-blink { 50% { opacity: .3; } }
.vip-photo__placeholder { display: flex; align-items: center; justify-content: center; color: #9a8574; }
.vip-photo__placeholder i { font-size: 44px; color: var(--brand); }
.vip-photo__actions { display: flex; justify-content: center; gap: 8px; width: 100%; margin-top: 4px; flex-wrap: wrap; }
.vip-photo__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    white-space: nowrap;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--brand);
    background: #fff;
    border: 1px solid #eadfce;
    border-radius: 10px;
    padding: 8px 12px;
    cursor: pointer;
    transition: background .2s, box-shadow .2s;
}
.vip-photo__btn:hover { box-shadow: 0 6px 16px rgba(33, 18, 21, .12); }
.vip-photo__btn--capture {
    color: #fff;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    border-color: transparent;
}
.vip-photo__canvas { display: none; }
.vip-photo__file { display: none; }

/* ---------- Success state ---------- */
.vip-success { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 10px; padding: 16px 4px; }
.vip-success__icon { font-size: 52px; color: #34a853; }
.vip-success__title { margin: 0; font-size: 18px; font-weight: 800; color: var(--ink); }
.vip-success__text { margin: 0 0 10px; font-size: 13.5px; color: #6b5b4d; line-height: 1.5; }

@media (prefers-reduced-motion: reduce) {
    .vip-photo__img { animation: none; }
    .vip-photo__live i { animation: none; }
    .vip-spin { animation: none; }
}
</style>
