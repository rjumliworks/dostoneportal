<template>
<section id="register" class="rstw-section">
    <div class="rstw-container rstw-reg">
        <div class="rstw-heading" data-aos="fade-up">
            <span class="rstw-kicker">Pre-Registration</span>
            <h1>Reserve your spot at RSTW 2026</h1>
            <div class="rstw-reg__note" role="note">
                <span class="rstw-reg__note-icon"><i class="ri-information-line"></i></span>
                <div class="rstw-reg__note-body">
                    <strong class="rstw-reg__note-title">This is a pre-registration form</strong>
                    <span class="rstw-reg__note-text">Secure your slot early — final confirmation will be done on-site at the event.</span>
                </div>
            </div>
            <p class="rstw-reg__sub">Fill out the form below. Make sure all details are accurate — we'll send a verification to your email.</p>
        </div>

        <form class="rstw-form" @submit.prevent="submit" data-aos="fade-up" data-aos-delay="100">
          <div class="rstw-form__grid">
            <!-- Left side: Photo capture -->
            <aside class="rstw-form__aside">
                <div class="rstw-field">
                    <label>Photo</label>
                    <div class="rstw-photo" :class="{ 'is-captured': !!photoPreview, 'is-live': cameraOn }">
                        <div class="rstw-photo__stage">
                            <video
                                v-show="cameraOn && !form.photo"
                                ref="video"
                                class="rstw-photo__video"
                                autoplay
                                playsinline
                                muted
                            ></video>
                            <template v-if="photoPreview">
                                <img :src="photoPreview" alt="Captured photo" class="rstw-photo__img">
                                <span class="rstw-photo__badge"><i class="ri-check-line"></i> Photo captured</span>
                            </template>
                            <span v-if="cameraOn && !photoPreview" class="rstw-photo__live"><i class="ri-record-circle-fill"></i> Live</span>
                            <div v-if="cameraOn && !photoPreview" class="rstw-photo__guide" aria-hidden="true"></div>
                            <div v-if="!cameraOn && !photoPreview" class="rstw-photo__placeholder">
                                <i class="ri-user-3-line"></i>
                                <span>Take a clear photo of your face for check-in</span>
                            </div>
                        </div>
                        <div class="rstw-photo__actions">
                            <button
                                v-if="!cameraOn && !photoPreview"
                                type="button"
                                class="rstw-photo__btn"
                                @click="startCamera"
                            >
                                <i class="ri-camera-line"></i> Open Camera
                            </button>
                            <button
                                v-if="cameraOn && !photoPreview"
                                type="button"
                                class="rstw-photo__btn rstw-photo__btn--capture"
                                @click="capturePhoto"
                            >
                                <i class="ri-camera-fill"></i> Capture
                            </button>
                            <button
                                v-if="photoPreview"
                                type="button"
                                class="rstw-photo__btn"
                                @click="retakePhoto"
                            >
                                <i class="ri-refresh-line"></i> Retake
                            </button>
                        </div>
                    </div>
                    <canvas ref="photoCanvas" class="rstw-photo__canvas"></canvas>
                    <span v-if="cameraError" class="rstw-form__error">{{ cameraError }}</span>
                    <span v-if="form.errors.photo" class="rstw-form__error">{{ form.errors.photo }}</span>
                </div>
            </aside>

            <!-- Right side: Details -->
            <div class="rstw-form__main">
            <h3 class="rstw-form__legend"><i class="ri-user-3-line"></i> Personal Information</h3>
            <div class="rstw-form__row">
                <div class="rstw-field">
                    <label>First Name</label>
                    <input type="text" v-model="form.firstname" placeholder="First name" @input="clearError('firstname')">
                </div>
                <div class="rstw-field">
                    <label>Middle Name</label>
                    <input type="text" v-model="form.middlename" placeholder="Middle name" @input="clearError('middlename')">
                </div>
                <div class="rstw-field">
                    <label>Last Name</label>
                    <input type="text" v-model="form.lastname" placeholder="Last name" @input="clearError('lastname')">
                </div>
                <div class="rstw-field rstw-field--sm">
                    <label>Suffix</label>
                    <input type="text" v-model="form.suffix" placeholder="Jr., Sr.">
                </div>
            </div>
            <p v-if="form.errors.fullname" class="rstw-form__error">{{ form.errors.fullname }}</p>

            <h3 class="rstw-form__legend"><i class="ri-mail-line"></i> Contact Details</h3>
            <div class="rstw-form__row">
                <div class="rstw-field">
                    <label>Email Address</label>
                    <input type="email" v-model="form.email" placeholder="you@email.com" @input="clearError('email')">
                    <span v-if="form.errors.email" class="rstw-form__error">{{ form.errors.email }}</span>
                </div>
                <div class="rstw-field">
                    <label>Contact No.</label>
                    <input type="text" inputmode="numeric" v-model="form.contact_no" placeholder="09XXXXXXXXX" maxlength="11" @keypress="onlyNumber" @input="onContactInput">
                    <span v-if="form.errors.contact_no" class="rstw-form__error">{{ form.errors.contact_no }}</span>
                </div>
            </div>

            <h3 class="rstw-form__legend"><i class="ri-profile-line"></i> Profile Details</h3>
            <div class="rstw-form__row">
                <div class="rstw-field">
                    <label>Birth Date</label>
                    <input type="date" v-model="form.birthdate" @input="clearError('birthdate')">
                    <span v-if="form.errors.birthdate" class="rstw-form__error">{{ form.errors.birthdate }}</span>
                </div>
                <div class="rstw-field">
                    <label>Sex</label>
                    <Multiselect :options="dropdowns.sexs" label="name" v-model="form.sex_id" placeholder="Select sex" @change="clearError('sex_id')" />
                    <span v-if="form.errors.sex_id" class="rstw-form__error">{{ form.errors.sex_id }}</span>
                </div>
            </div>

            <div class="rstw-form__row">
                <div class="rstw-field">
                    <label>Designation</label>
                    <input type="text" v-model="form.designation" placeholder="e.g. Researcher" @input="clearError('designation')">
                    <span v-if="form.errors.designation" class="rstw-form__error">{{ form.errors.designation }}</span>
                </div>
                <div class="rstw-field">
                    <label>Affiliation</label>
                    <input type="text" v-model="form.affiliation" placeholder="e.g. DOST-IX" @input="clearError('affiliation')">
                    <span v-if="form.errors.affiliation" class="rstw-form__error">{{ form.errors.affiliation }}</span>
                </div>
            </div>

            <h3 class="rstw-form__legend"><i class="ri-shield-check-line"></i> Verification</h3>
            <div class="rstw-form__row">
                <div class="rstw-field">
                    <label>Signature</label>
                    <div class="rstw-sign">
                        <SignaturePad ref="signaturePad" class="rstw-sign__pad" />
                        <button type="button" class="rstw-sign__clear" @click="clearSignature">
                            <i class="ri-eraser-line"></i> Clear
                        </button>
                    </div>
                    <span v-if="form.errors.signature" class="rstw-form__error">{{ form.errors.signature }}</span>
                </div>
                <div class="rstw-field">
                    <label>CAPTCHA</label>
                    <div class="rstw-captcha">
                        <img :src="captchaUrl" @click="refreshCaptcha" alt="captcha" title="Click to refresh">
                        <button type="button" class="rstw-captcha__refresh" @click="refreshCaptcha" aria-label="Refresh captcha">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                    <input type="text" v-model="form.captcha" placeholder="Enter the text above" @input="clearError('captcha')" autocomplete="off">
                    <span v-if="form.errors.captcha" class="rstw-form__error">{{ form.errors.captcha }}</span>
                </div>
            </div>

            <label class="rstw-check">
                <input type="checkbox" v-model="form.check">
                <span>I agree to the <a href="/privacy-policy" target="_blank">Terms of Service and Privacy Policy</a>.</span>
            </label>

            <button type="submit" class="rstw-btn rstw-btn--primary rstw-form__submit" :disabled="!form.check || form.processing">
                <i :class="form.processing ? 'ri-loader-4-line rstw-spin' : 'ri-checkbox-circle-line'"></i>
                {{ form.processing ? 'Submitting…' : 'Submit Registration' }}
            </button>
            </div><!-- /.rstw-form__main -->
          </div><!-- /.rstw-form__grid -->
        </form>
    </div>
</section>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from '@vueform/multiselect';
import SignaturePad from 'vue3-signature-pad';

export default {
    name: 'PreRegistrationForm',
    components: { Multiselect, SignaturePad },
    props: {
        dropdowns: { type: Object, required: true },
    },
    data() {
        return {
            captchaUrl: '/captcha/flat?' + Date.now(),
            cameraStream: null,
            cameraOn: false,
            cameraError: null,
            photoPreview: null,
            form: useForm({
                firstname: null,
                middlename: null,
                lastname: null,
                suffix: null,
                email: null,
                contact_no: null,
                birthdate: null,
                sex_id: null,
                designation: null,
                affiliation: null,
                signature: null,
                photo: null,
                captcha: null,
                type_id: 16,
                check: false,
            }),
        };
    },
    methods: {
        clearError(field) {
            this.form.errors[field] = false;
            this.form.errors.fullname = false;
        },
        onlyNumber(e) {
            const char = String.fromCharCode(e.which ?? e.keyCode);
            if (!/[0-9]/.test(char)) e.preventDefault();
        },
        onContactInput() {
            this.form.contact_no = (this.form.contact_no || '').replace(/\D/g, '').slice(0, 11);
            this.clearError('contact_no');
        },
        validate() {
            const errs = {};
            const f = this.form;
            if (!f.firstname || !f.middlename || !f.lastname) {
                errs.fullname = 'First, middle, and last name are required.';
            }
            if (!f.email) errs.email = 'Email address is required.';
            if (!f.contact_no) errs.contact_no = 'Contact number is required.';
            else if (f.contact_no.length !== 11) errs.contact_no = 'Contact number must be 11 digits.';
            if (!f.birthdate) errs.birthdate = 'Birth date is required.';
            if (!f.sex_id) errs.sex_id = 'Please select your sex.';
            if (!f.designation) errs.designation = 'Designation is required.';
            if (!f.affiliation) errs.affiliation = 'Affiliation is required.';
            if (!f.photo) errs.photo = 'Please capture your photo.';
            if (!f.captcha) errs.captcha = 'Please enter the CAPTCHA.';
            return errs;
        },
        clearSignature() {
            this.$refs.signaturePad?.clearSignature();
            this.form.signature = null;
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
        capturePhoto() {
            const video = this.$refs.video;
            const canvas = this.$refs.photoCanvas;
            if (!video || !canvas || !video.videoWidth) return;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            this.photoPreview = canvas.toDataURL('image/jpeg', 0.9);
            canvas.toBlob(
                (blob) => {
                    this.form.photo = blob;
                    this.clearError('photo');
                },
                'image/jpeg',
                0.9
            );
            this.stopCamera();
        },
        retakePhoto() {
            this.photoPreview = null;
            this.form.photo = null;
            this.startCamera();
        },
        refreshCaptcha() {
            this.captchaUrl = '/captcha/flat?' + Date.now();
            this.form.captcha = null;
        },
        async submit() {
            this.form.clearErrors();
            const errs = this.validate();

            const pad = this.$refs.signaturePad;
            if (!pad || pad.isEmpty()) errs.signature = 'Please provide your signature.';

            if (Object.keys(errs).length) {
                this.form.setError(errs);
                return;
            }

            const dataUrl = pad.toDataURL('image/png');
            this.form.signature = await fetch(dataUrl).then((res) => res.blob());

            this.form.post('/', {
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.form.clearErrors();
                    this.clearSignature();
                    this.stopCamera();
                    this.photoPreview = null;
                    this.form.photo = null;
                    this.refreshCaptcha();
                },
                onError: () => {
                    this.refreshCaptcha();
                },
            });
        },
    },
    beforeUnmount() {
        this.stopCamera();
    },
};
</script>

<style scoped>
/* ---------- Shared layout tokens (mirrored from Rstw.vue) ---------- */
.rstw-section {
    min-height: 100vh;
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.rstw-container { max-width: 1160px; margin: 0 auto; }
.rstw-heading { text-align: center; max-width: 620px; margin: 0 auto 12px; }
.rstw-heading h1 { font-size: clamp(1.25rem, 2.4vw, 1.7rem); font-weight: 800; margin: 0 0 8px; letter-spacing: -.4px; }
.rstw-kicker {
    display: inline-block;
    color: var(--c-blue);
    font-weight: 700;
    font-size: 12.5px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 8px;
    padding: 5px 13px;
    border-radius: 999px;
    background: linear-gradient(135deg, rgba(20, 76, 141, .10), rgba(70, 160, 193, .12));
    border: 1px solid rgba(20, 76, 141, .16);
}
.rstw-btn {
    display: inline-block;
    padding: 10px 26px;
    border-radius: 999px;
    font-weight: 600;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s, opacity .2s;
}
.rstw-btn:hover { transform: translateY(-2px); }
.rstw-btn--primary {
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    color: #fff;
    box-shadow: 0 12px 30px rgba(226, 32, 50, .28);
}
@keyframes rstwSpin { to { transform: rotate(360deg); } }

/* ---------- Registration form ---------- */
.rstw-reg__sub { color: #4b5563; margin-top: 6px; font-size: 12.5px; line-height: 1.45; }
.rstw-reg__note {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    max-width: 620px;
    margin: 8px auto 0;
    padding: 8px 12px;
    border-radius: 14px;
    text-align: left;
    background: linear-gradient(135deg, rgba(255, 207, 46, .16), rgba(246, 179, 10, .08));
    border: 1px solid rgba(246, 179, 10, .4);
    box-shadow: 0 6px 18px rgba(246, 179, 10, .12);
}
.rstw-reg__note-icon {
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border-radius: 10px;
    background: var(--brand);
    color: #fff;
    font-size: 19px;
}
.rstw-reg__note-body { display: flex; flex-direction: column; gap: 2px; }
.rstw-reg__note-title { font-size: 14.5px; font-weight: 700; color: var(--ink); line-height: 1.35; }
.rstw-reg__note-text { font-size: 13.5px; font-weight: 500; color: #6b5b4d; line-height: 1.5; }
.rstw-form {
    position: relative;
    max-width: 1000px;
    margin: 0 auto;
    /* Soft warm-to-cool wash instead of flat white, so the card reads as a
       surface rather than a box. */
    background:
        radial-gradient(120% 90% at 0% 0%, rgba(20, 76, 141, .05), transparent 55%),
        radial-gradient(110% 80% at 100% 0%, rgba(236, 134, 76, .06), transparent 55%),
        #fff;
    border: 1px solid rgba(11, 17, 32, .07);
    border-radius: 28px;
    padding: 18px 22px 16px;
    /* Layered shadow: tight contact shadow + wide ambient lift */
    box-shadow:
        0 2px 4px rgba(11, 17, 32, .04),
        0 12px 24px rgba(11, 17, 32, .06),
        0 40px 80px rgba(11, 17, 32, .10);
    overflow: hidden;
}
/* Festive gradient accent along the top edge of the card */
.rstw-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--c-blue), var(--c-blue-2), var(--c-gold), var(--c-orange), var(--c-red));
}
/* Oversized watermark petal in the corner — echoes the bg.png motif */
.rstw-form::after {
    content: '';
    position: absolute;
    top: -90px;
    right: -90px;
    width: 280px;
    height: 280px;
    border-radius: 50% 0 50% 50%;
    background: linear-gradient(135deg, rgba(20, 76, 141, .05), rgba(236, 134, 76, .05));
    pointer-events: none;
}
/* Grouped section legends */
.rstw-form__legend {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 10px 0 8px;
    padding-bottom: 6px;
    font-size: 12.5px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--brand);
    border-bottom: 1px solid rgba(11, 17, 32, .08);
}
/* Short gradient underline riding on top of the hairline rule */
.rstw-form__legend::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -1px;
    width: 56px;
    height: 2px;
    border-radius: 2px;
    background: linear-gradient(90deg, var(--c-orange), var(--c-red-2));
}
.rstw-form__legend:first-child { margin-top: 0; }
/* Icon sits in a tinted chip rather than floating loose */
.rstw-form__legend i {
    display: grid;
    place-items: center;
    width: 30px;
    height: 30px;
    border-radius: 9px;
    font-size: 16px;
    color: #fff;
    background: linear-gradient(135deg, var(--c-blue), var(--c-blue-3));
    box-shadow: 0 4px 10px rgba(20, 76, 141, .25);
}
.rstw-form__row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 8px; }
.rstw-form__row:first-of-type { grid-template-columns: 1fr 1fr 1fr .7fr; }
.rstw-field { display: flex; flex-direction: column; }
.rstw-field label {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 3px;
    letter-spacing: .02em;
}
.rstw-field input {
    height: 36px;
    border: 1.5px solid #e4e8ef;
    border-radius: 14px;
    padding: 0 16px;
    font-size: 15px;
    background: #f8fafc;
    transition: border-color .2s, box-shadow .2s, background .2s, transform .2s;
    width: 100%;
}
.rstw-field input::placeholder { color: #a8b0bd; }
.rstw-field input:hover { border-color: #c7ccd6; background: #fbfcfe; }
.rstw-field input:focus {
    outline: none;
    border-color: var(--c-blue);
    background: #fff;
    /* brand-blue focus ring + a slight lift so the active field stands out */
    box-shadow: 0 0 0 4px rgba(20, 76, 141, .13), 0 6px 16px rgba(20, 76, 141, .10);
    transform: translateY(-1px);
}
.rstw-form__error { color: #dc2626; font-size: 12px; margin-top: 6px; display: block; }

/* Submit button icon + processing spinner */
.rstw-form__submit i { font-size: 18px; }
.rstw-spin { display: inline-block; animation: rstwSpin 1s linear infinite; }

/* Signature pad */
.rstw-sign { position: relative; }
.rstw-sign__pad {
    width: 100%;
    height: 78px;
    border: 1px dashed #d7b9a0;
    border-radius: 12px;
    background: #fffdf8;
    overflow: hidden;
    display: block;
}
.rstw-sign__pad :deep(canvas) { width: 100% !important; height: 100% !important; border-radius: 12px; }
.rstw-sign__clear {
    position: absolute;
    top: 8px;
    right: 8px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: var(--brand);
    background: rgba(255, 255, 255, .9);
    border: 1px solid #eadfce;
    border-radius: 8px;
    padding: 4px 10px;
    cursor: pointer;
}
.rstw-sign__clear:hover { background: #fff; }

/* Two-column form layout: photo aside + details */
.rstw-form__grid {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 20px;
    align-items: start;
}
.rstw-form__aside { position: sticky; top: 88px; }
/* Photo label sits above a centred circle, so centre it to match */
.rstw-form__aside .rstw-field label { text-align: center; }
.rstw-form__main { min-width: 0; }

/* Photo capture */
.rstw-form__row--single { grid-template-columns: 1fr; }
.rstw-photo { display: flex; flex-direction: column; align-items: center; gap: 8px; }
.rstw-photo__stage {
    position: relative;
    width: 100%;
    max-width: 138px;
    aspect-ratio: 1;
    border: 2px dashed #d7b9a0;
    border-radius: 50%;
    background: #fffdf8;
    overflow: hidden;
    display: grid;
    place-items: center;
    margin: 0 auto;
}
.rstw-photo.is-captured .rstw-photo__stage {
    border-style: solid;
    border-color: #34a853;
    box-shadow: 0 0 0 3px rgba(52, 168, 83, .15);
}
.rstw-photo.is-live .rstw-photo__stage { border-style: solid; border-color: var(--brand); }
.rstw-photo__video,
.rstw-photo__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scaleX(-1);
}
.rstw-photo__img { animation: rstw-photo-in .35s ease; }
@keyframes rstw-photo-in {
    from { opacity: 0; transform: scaleX(-1) scale(1.04); }
    to { opacity: 1; transform: scaleX(-1) scale(1); }
}
.rstw-photo__badge {
    position: absolute;
    left: 10px;
    bottom: 10px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    background: #34a853;
    padding: 5px 11px;
    border-radius: 999px;
    box-shadow: 0 6px 16px rgba(52, 168, 83, .35);
}
.rstw-photo__live {
    position: absolute;
    left: 10px;
    top: 10px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #fff;
    background: rgba(33, 18, 21, .6);
    padding: 4px 10px;
    border-radius: 999px;
}
.rstw-photo__live i { color: var(--brand); animation: rstw-blink 1.2s steps(2, start) infinite; }
@keyframes rstw-blink { 50% { opacity: .3; } }
.rstw-photo__guide {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 62%;
    height: 62%;
    transform: translate(-50%, -50%);
    border: 2px dashed rgba(255, 255, 255, .8);
    border-radius: 50%;
    pointer-events: none;
}
.rstw-photo__placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 16px;
    text-align: center;
    color: #9a8574;
}
.rstw-photo__placeholder i { font-size: 52px; color: var(--brand); }
.rstw-photo__placeholder span { font-size: 13px; max-width: 220px; }
.rstw-photo__actions { display: flex; justify-content: center; gap: 10px; }
.rstw-photo__btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 600;
    color: var(--brand);
    background: #fff;
    border: 1px solid #eadfce;
    border-radius: 10px;
    padding: 9px 16px;
    cursor: pointer;
    transition: background .2s, box-shadow .2s;
}
.rstw-photo__btn:hover { box-shadow: 0 6px 16px rgba(33, 18, 21, .12); }
.rstw-photo__btn--capture {
    color: #fff;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    border-color: transparent;
}
.rstw-photo__canvas { display: none; }
@media (prefers-reduced-motion: reduce) {
    .rstw-photo__img { animation: none; }
    .rstw-photo__live i { animation: none; }
    .rstw-shape--sun { animation: none; }
    .rstw-hero__lockup { animation: none; }
    .rstw-float { animation: none; }
    .rstw-trivia__card { transform: none; }
}

/* Keep the hero clean on small screens */
@media (max-width: 640px) {
    .rstw-hero__floaters { display: none; }
}
/* Side icons live in the gutters — hide once content fills the width */
@media (max-width: 900px) {
    .rstw-banner { display: none; }
}

/* Captcha */
.rstw-captcha {
    position: relative;
    height: 48px;
    border: 1px solid #dfe3ea;
    border-radius: 12px;
    background: #f8fafc;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}
.rstw-captcha img { max-height: 40px; max-width: 100%; cursor: pointer; }
.rstw-captcha__refresh {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 28px;
    height: 28px;
    display: grid;
    place-items: center;
    border-radius: 8px;
    border: 1px solid #eadfce;
    background: rgba(255, 255, 255, .9);
    color: var(--brand);
    cursor: pointer;
}
.rstw-captcha__refresh:hover { background: #fff; }
.rstw-check { display: flex; gap: 8px; align-items: flex-start; margin: 2px 0 10px; font-size: 12.5px; color: #4b5563; cursor: pointer; }
.rstw-check input { margin-top: 3px; width: 16px; height: 16px; accent-color: var(--brand); }
.rstw-check a { color: var(--brand); font-weight: 600; }
.rstw-form__submit { display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; border: 0; cursor: pointer; font-size: 16px; }
.rstw-form__submit:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }

/* Multiselect sizing to match inputs */
.rstw-field :deep(.multiselect) {
    min-height: 36px;
    border: 1px solid #dfe3ea;
    border-radius: 12px;
    background: #f8fafc;
}
.rstw-field :deep(.multiselect.is-active) {
    border-color: var(--brand);
    box-shadow: 0 0 0 4px rgba(225, 27, 34, .14);
}

@media (max-width: 900px) {
    .rstw-form { padding: 28px 22px; }
    .rstw-form__grid { grid-template-columns: 1fr; gap: 24px; }
    .rstw-form__aside { position: static; top: auto; }
    .rstw-form__row,
    .rstw-form__row:first-of-type { grid-template-columns: 1fr; gap: 16px; }
}

@media (max-width: 640px) {
    .rstw-section { padding: 60px 18px; }
}
</style>
