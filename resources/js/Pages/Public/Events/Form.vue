<template>
<section id="register" class="rstw-section">
    
    <div class="rstw-container rstw-reg">
        <div class="rstw-heading" data-aos="fade-up">


    <!-- Session card: header (branding) / body (session info) / footer (warning) -->
    <div class="rstw-card">
        <!-- Header -->
        <div class="rstw-session">
            <div class="rstw-session__left">
                <img src="/images/2026/logo.png" alt="OneDOST4U" class="rstw-session__logo">

                <div class="rstw-session__text">
                    <span class="rstw-session__label">Department of Science and Technology</span>
                    <h3 class="rstw-session__title">
                        <span class="rstw-session__title-accent">One</span><span class="rstw-session__title-brand">DOST4U</span>
                        <span class="rstw-session__title-tag">: Solutions and Opportunities for All</span>
                    </h3>
                </div>
            </div>

            <Link href="/" class="rstw-session__brand rstw-session__brand--desktop">
                <img src="/images/2026/2026RSTW.png" alt="2026 RSTW" class="rstw-session__brand-img">
            </Link>
        </div>

        <!-- Body -->
        <div class="rstw-card__body">
            <Link href="/" class="rstw-session__brand rstw-session__brand--mobile">
                <img src="/images/2026/2026RSTW.png" alt="2026 RSTW" class="rstw-session__brand-img">
            </Link>

            <div class="rstw-info__cols" v-if="session">
                <div class="rstw-info__col">
                    <div class="rstw-info__item">
                        <span class="rstw-info__label">Session title:</span>
                        <span class="rstw-info__value">
                            {{ session.title || '—' }}
                            <span v-if="sessionFull" class="rstw-info__badge">FULL</span>
                        </span>
                    </div>
                    <div class="rstw-info__item">
                        <span class="rstw-info__label">Date and Time:</span>
                        <span class="rstw-info__value">{{ sessionDate }}</span>
                    </div>
                </div>
                <div class="rstw-info__col">
                    <div class="rstw-info__item">
                        <span class="rstw-info__label">Venue:</span>
                        <span class="rstw-info__value">{{ session.venue?.establishment || '—' }}</span>
                    </div>
                    <div class="rstw-info__item">
                        <span class="rstw-info__label">Address:</span>
                        <span class="rstw-info__value">{{ session.venue?.address || '—' }}</span>
                    </div>
                </div>
            </div>
            <div class="rstw-reg__note" role="alert" :class="{ 'rstw-reg__note--danger': isPreregClosed || sessionFull }">
                <span class="rstw-reg__note-icon">
                    <i class="ri-alert-line"></i>
                </span>

                <div class="rstw-reg__note-body">
                    <strong class="rstw-reg__note-title">
                        {{ isPreregClosed ? 'Registration Not Yet Open' : (sessionFull ? 'Session Full' : 'Important Reminder') }}
                    </strong>

                    <span class="rstw-reg__note-text" v-if="isPreregClosed">
                        Pre-registration for this session hasn't opened yet. Please
                        check back later, or follow updates through the mobile app.
                    </span>
                    <span class="rstw-reg__note-text" v-else-if="sessionFull">
                        {{ sessionFullMessage }}
                    </span>
                    <span class="rstw-reg__note-text" v-else-if="session">
                        This pre-registration is valid only for the selected session.
                        Please register only if you intend to attend. Registration is
                        subject to review and confirmation through the system.
                    </span>
                    <span class="rstw-reg__note-text" v-else>
                        This registers you into the system without booking a specific
                        session. Once your registration is approved, you'll be able to
                        register for individual sessions and exhibits anytime through
                        the mobile app.
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>

        <div v-if="isPreregClosed" class="rstw-closed" data-aos="fade-up">
            <div class="rstw-closed__icon"><i class="ri-time-line"></i></div>
            <h3 class="rstw-closed__title">Pre-Registration Not Yet Open</h3>
            <p class="rstw-closed__text">
                Pre-registration for this session hasn't opened yet. Please check
                back later, or follow updates through the mobile app once
                registration begins. In the meantime, you can register as a
                general participant and pick a specific session later.
            </p>
            <div class="rstw-closed__actions">
                <Link href="/registration" class="rstw-btn rstw-btn--primary">
                    <i class="ri-user-add-line"></i> Register Without a Session
                </Link>
                <Link href="/" class="rstw-btn rstw-btn--ghost">
                    <i class="ri-home-4-line"></i> Back to Home
                </Link>
            </div>
        </div>

        <template v-else>
        <ul v-if="errorMessages.length" class="rstw-error-summary">
            <li v-for="(msg, key) in form.errors" :key="key" v-show="msg">{{ msg }}</li>
        </ul>

        <form class="rstw-form" @submit.prevent="submit" enctype="multipart/form-data" data-aos="fade-up" data-aos-delay="100">
          <div class="rstw-form__grid">
            <!-- Left column flows photo → verification with no forced row
                 alignment, so short blocks don't leave gaps on big screens. -->
            <div class="rstw-col rstw-col--left">
            <aside class="rstw-form__aside">
                <div class="rstw-field">
                    <label>Avatar</label>
                    <div class="rstw-photo" :class="{ 'is-captured': !!avatarPreview && !form.errors.avatar, 'is-invalid': !!avatarPreview && !!form.errors.avatar, 'is-live': cameraOn }">
                        <div class="rstw-photo__stage">
                            <video
                                v-show="cameraOn && !form.avatar"
                                ref="video"
                                class="rstw-photo__video"
                                autoplay
                                playsinline
                                muted
                            ></video>
                            <img v-if="avatarPreview" :src="avatarPreview" alt="Captured avatar" class="rstw-photo__img">
                            <span v-if="cameraOn && !avatarPreview" class="rstw-photo__live"><i class="ri-record-circle-fill"></i> Live</span>
                            <div v-if="cameraOn && !avatarPreview" class="rstw-photo__guide" aria-hidden="true"></div>
                            <div v-if="!cameraOn && !avatarPreview" class="rstw-photo__placeholder">
                                <i class="ri-user-3-line"></i>
                            </div>
                        </div>

                        <!-- Helper text / success message live outside the circle,
                             where they have room to read cleanly. -->
                        <p v-if="avatarPreview && form.errors.avatar" class="rstw-photo__caption rstw-photo__caption--error">
                            <i class="ri-close-circle-fill"></i> Avatar failed
                        </p>
                        <p v-else-if="avatarPreview" class="rstw-photo__caption rstw-photo__caption--ok">
                            <i class="ri-checkbox-circle-fill"></i> Avatar captured
                        </p>
                        <div v-if="form.errors.avatar" class="rstw-photo__warning rstw-photo__warning--danger">
                            <i class="ri-error-warning-line"></i>
                            <span>{{ form.errors.avatar }}</span>
                        </div>
                        <div v-else class="rstw-photo__warning">
                            <i class="ri-alert-line"></i>
                            <span>
                                This avatar will be the basis for facial recognition and
                                will be compared with your face at the venue's attendance
                                page, so make sure it clearly shows your face.
                            </span>
                        </div>
                        <div class="rstw-photo__actions">
                            <button
                                v-if="!cameraOn && !avatarPreview"
                                type="button"
                                class="rstw-photo__btn"
                                @click="startCamera"
                            >
                                <i class="ri-camera-line"></i> Open Camera
                            </button>
                            <button
                                v-if="cameraOn && !avatarPreview"
                                type="button"
                                class="rstw-photo__btn rstw-photo__btn--capture"
                                @click="captureAvatar"
                            >
                                <i class="ri-camera-fill"></i> Capture
                            </button>
                            <button
                                v-if="avatarPreview"
                                type="button"
                                class="rstw-photo__btn"
                                @click="retakeAvatar"
                            >
                                <i class="ri-refresh-line"></i> Retake
                            </button>
                        </div>
                    </div>
                    <canvas ref="avatarCanvas" class="rstw-photo__canvas"></canvas>
                    <span v-if="cameraError" class="rstw-form__error">{{ cameraError }}</span>
                </div>
            </aside>

            
            </div><!-- /.rstw-col--left -->

            <!-- Right column: detail fields + agreement/submit -->
            <div class="rstw-col rstw-col--right">
            <div class="rstw-form__main" style="margin-top: -10px;">
            <h4 class="rstw-form__legend">Personal Information</h4>
            <div class="rstw-form__row rstw-form__row--quad">
                <div class="rstw-field" :class="{ 'has-error': form.errors.fullname }">
                    <label>First Name</label>
                    <input type="text" v-model="form.firstname" placeholder="First name" @input="clearError('firstname')">
                </div>
                <div class="rstw-field" :class="{ 'has-error': form.errors.fullname }">
                    <label>Middle Name</label>
                    <input type="text" v-model="form.middlename" placeholder="Middle name" @input="clearError('middlename')">
                </div>
                <div class="rstw-field" :class="{ 'has-error': form.errors.fullname }">
                    <label>Last Name</label>
                    <input type="text" v-model="form.lastname" placeholder="Last name" @input="clearError('lastname')">
                </div>
                <div class="rstw-field" :class="{ 'has-error': form.errors.suffix_id }">
                    <label>Suffix</label>
                    <select v-model="form.suffix_id" @change="clearError('suffix_id')">
                        <option :value="null">None</option>
                        <option v-for="opt in (dropdowns.suffixes || [])" :key="opt.value" :value="opt.value">{{ opt.name }}</option>
                    </select>
                </div>
            </div>
            <div class="rstw-form__row rstw-form__row--quad" style="margin-top: -8px;">
                <div class="rstw-field" :class="{ 'has-error': form.errors.birthdate }">
                    <label>Birth Date</label>
                    <input type="date" v-model="form.birthdate" @input="clearError('birthdate')">
                </div>
                <div class="rstw-field" :class="{ 'has-error': form.errors.sex_id }">
                    <label>Sex</label>
                    <select v-model="form.sex_id" @change="clearError('sex_id')">
                        <option :value="null" disabled>Select sex</option>
                        <option v-for="opt in dropdowns.sexs" :key="opt.value" :value="opt.value">{{ opt.name }}</option>
                    </select>
                </div>
                <div class="rstw-field" :class="{ 'has-error': form.errors.email }">
            <label>Email Address</label>
            <input
                type="email"
                v-model="form.email"
                placeholder="you@email.com"
                @input="clearError('email')"
            >
        </div>

        <div class="rstw-field" :class="{ 'has-error': form.errors.mobile }">
            <label>Mobile Number</label>
            <input
                type="text"
                inputmode="numeric"
                v-model="form.mobile"
                placeholder="09XXXXXXXXX"
                maxlength="11"
                @keypress="onlyNumber"
                @input="onMobileInput"
            >
        </div>
            </div>

            

            
<div class="rstw-form__bottom">

    <!-- LEFT -->
    <div class="rstw-form__bottom-col">
        <h3 class="rstw-form__legend">Professional Details</h3>

        <div class="rstw-form__details" ref="detailsColumn">

            <div class="rstw-field" :class="{ 'has-error': form.errors.designation }">
                <label>Designation <span class="rstw-field__hint">(please write in full)</span></label>
                <input
                    type="text"
                    v-model="form.designation"
                    placeholder="e.g. Researcher"
                    @input="clearError('designation')"
                >
            </div>

            <div class="rstw-field" :class="{ 'has-error': form.errors.affiliation_id }" style="margin-top: 3px;">
                <label>Affiliation <span class="rstw-field__hint">(Search affiliation on the list)</span></label>
                <Multiselect
                    v-model="form.affiliation_id"
                    :options="affiliationOptions"
                    value-prop="value"
                    track-by="name"
                    label="name"
                    :searchable="true"
                    :filter-results="false"
                    placeholder="Type to search affiliation"
                    @search-change="onAffiliationSearch"
                    @change="onAffiliationChange"
                />
            </div>

            <div class="rstw-field" :class="{ 'has-error': form.errors.others }">
                <label>Others <span class="rstw-field__hint">(If not on the list)</span></label>
                <input
                    type="text"
                    v-model="form.others"
                    placeholder="Specify affiliation"
                    :disabled="!isAffiliationOthers"
                    @input="clearError('others')"
                >
            </div>

        </div>
    </div>

    <!-- RIGHT -->
    <div class="rstw-form__bottom-col">
        <h3 class="rstw-form__legend">Attestation</h3>

        <div class="rstw-form__verify">

        <div class="rstw-field" :class="{ 'has-error': form.errors.signature }">
            <label>Signature <span class="rstw-field__hint">(please draw in the center)</span></label>

            <div class="rstw-sign" ref="signWrap">
                <SignaturePad
                    ref="signaturePad"
                    class="rstw-sign__pad"
                    :custom-style="{ height: signPadHeight + 'px' }"
                />

                <button
                    type="button"
                    class="rstw-sign__clear"
                    @click="clearSignature"
                >
                    <i class="ri-eraser-line"></i>
                    Clear
                </button>
            </div>
        </div>

    </div>
    </div>

</div>


            </div><!-- /.rstw-form__main -->
            </div><!-- /.rstw-col--right -->
          </div><!-- /.rstw-form__grid -->

          <!-- Agreement + submit: full-width footer, checkboxes under the photo, button at the far right -->
          <div class="rstw-form__foot">
              <div class="rstw-form__consent">
                  <label class="rstw-check" @click.prevent="openPrivacyModal">
                      <input type="checkbox" :checked="form.check" @keydown.space.prevent="openPrivacyModal">
                      <span>I agree to the <span class="rstw-check__link">Terms of Service and Privacy Policy</span>.</span>
                  </label>
                  <label class="rstw-check" :class="{ 'is-disabled': !form.check }">
                      <input type="checkbox" v-model="form.consent" :disabled="!form.check">
                      <span>I consent to the collection, processing, and use of my personal data for this event in accordance with the Data Privacy Act of 2012.</span>
                  </label>
              </div>

              <button type="button" @click="submit" class="rstw-btn rstw-btn--primary rstw-form__submit" :disabled="!form.check || !form.consent || form.processing">
                  <i :class="form.processing ? 'ri-loader-4-line rstw-spin' : 'ri-checkbox-circle-line'"></i>
                  {{ form.processing ? 'Submitting…' : 'Submit Registration' }}
              </button>
          </div>
        </form>
        </template>
    </div>

    <!-- Privacy Notice modal -->
    <div v-if="showPrivacyModal" class="rstw-modal" role="dialog" aria-modal="true" @click.self="showPrivacyModal = false">
        <div class="rstw-modal__card">
            <div class="rstw-modal__head">
                <h4 class="rstw-modal__title">Privacy Notice</h4>
                <button type="button" class="rstw-modal__close" @click="showPrivacyModal = false" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            <div class="rstw-modal__body">
                <p>
                    The Department of Science and Technology - Zamboanga Peninsula (DOST-IX)
                    is committed to protecting your personal information. The information
                    collected in this registration form will be used for registering your
                    participation in the 2026 Regional Science, Technology, and Innovation
                    Week.
                </p>

                <p>
                    Your information may be processed for registration validation, event
                    coordination, confirmation email delivery, e-ticket issuance, attendance
                    checking through facial recognition at the venue, reporting, safety and
                    security operations, and post-event evaluation.
                </p>

                <p>
                    DOST-IX may collect your full name, birth date, sex, contact details
                    (email address and mobile number), designation, affiliation, avatar,
                    and signature, and other information needed for your selected
                    registration category.
                </p>

                <p>
                    By proceeding, you acknowledge that you have read, understood, and agree
                    to the collection, use, disclosure, and processing of your personal
                    information as described in this Privacy Notice.
                </p>
            </div>

            <div class="rstw-modal__foot">
                <button type="button" class="rstw-modal__btn rstw-modal__btn--ghost" @click="showPrivacyModal = false">
                    Close
                </button>
                <button type="button" class="rstw-modal__btn rstw-modal__btn--primary" @click="acceptPrivacy">
                    I Agree
                </button>
            </div>
        </div>
    </div>

    <!-- Missing requirement warning modal -->
    <div v-if="requirementModalMessage" class="rstw-modal" role="alertdialog" aria-modal="true" @click.self="requirementModalMessage = null">
        <div class="rstw-modal__card rstw-modal__card--sm">
            <div class="rstw-modal__head rstw-modal__head--warning">
                <h4 class="rstw-modal__title">
                    <i class="ri-alert-line"></i>
                    Almost there
                </h4>
                <button type="button" class="rstw-modal__close" @click="requirementModalMessage = null" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            <div class="rstw-modal__body rstw-modal__body--center">
                <div class="rstw-modal__icon">
                    <i class="ri-error-warning-fill"></i>
                </div>
                <p>{{ requirementModalMessage }}</p>
            </div>

            <div class="rstw-modal__foot rstw-modal__foot--center">
                <button type="button" class="rstw-modal__btn rstw-modal__btn--primary" @click="requirementModalMessage = null">
                    Got it
                </button>
            </div>
        </div>
    </div>

    <!-- Session full confirmation modal -->
    <div v-if="showCapacityModal" class="rstw-modal" role="alertdialog" aria-modal="true" @click.self="cancelCapacityModal">
        <div class="rstw-modal__card rstw-modal__card--sm">
            <div class="rstw-modal__head rstw-modal__head--warning">
                <h4 class="rstw-modal__title">
                    <i class="ri-alert-line"></i>
                    Session Full
                </h4>
                <button type="button" class="rstw-modal__close" @click="cancelCapacityModal" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            </div>

            <div class="rstw-modal__body rstw-modal__body--center">
                <div class="rstw-modal__icon">
                    <i class="ri-error-warning-fill"></i>
                </div>
                <p>{{ sessionFullMessage }}</p>
            </div>

            <div class="rstw-modal__foot">
                <button type="button" class="rstw-modal__btn rstw-modal__btn--ghost" @click="cancelCapacityModal">
                    Cancel
                </button>
                <button type="button" class="rstw-modal__btn rstw-modal__btn--primary" @click="confirmCapacityAndSubmit">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</section>
</template>

<script>
import { useForm, Link, router } from '@inertiajs/vue3';
import SignaturePad from 'vue3-signature-pad';
import Multiselect from '@vueform/multiselect';
import axios from 'axios';

export default {
    name: 'PreRegistrationForm',
    components: { SignaturePad, Link, Multiselect },
    props: ['dropdowns','session'],
    data() {
        return {
            cameraStream: null,
            cameraOn: false,
            cameraError: null,
            avatarPreview: null,
            showPrivacyModal: false,
            signPadHeight: 126,
            detailsResizeObserver: null,
            affiliations: [],
            affiliationKeyword: '',
            requirementModalMessage: null,
            sessionFull: false,
            showCapacityModal: false,
            capacityConfirmed: false,
            form: useForm({
                firstname: null,
                middlename: null,
                lastname: null,
                suffix_id: null,
                email: null,
                mobile: null,
                birthdate: null,
                sex_id: null,
                designation: null,
                affiliation_id: null,
                others: null,
                signature: null,
                avatar: null,
                type_id: 196,
                check: false,
                consent: false,
                session_id: this.session?.id ?? null
            }),
        };
    },
    computed: {
        isPreregClosed() {
            return !!this.session && !this.session.is_prereg;
        },
        isAffiliationOthers() {
            return this.form.affiliation_id === 'others';
        },
        affiliationOptions() {
            const keyword = this.affiliationKeyword.trim().toLowerCase();
            const results = this.affiliations
                .filter((opt) => opt.name !== 'Others')
                .filter((opt) => !keyword || opt.name.toLowerCase().includes(keyword));
            // Others stays available regardless of what's typed — filtered
            // client-side now that the full list loads once up front, so
            // there's no server round-trip left to skip it on.
            return [...results, { value: 'others', name: 'Others (if not on the list)' }];
        },
        sessionDate() {
            const schedule = this.session?.schedules?.[0];
            const raw = schedule?.date;
            if (!raw) return '—';
            const date = new Date(raw);
            let formatted = isNaN(date)
                ? raw
                : date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

            const start = this.formatTime(schedule?.start_time);
            const end = this.formatTime(schedule?.end_time);
            if (start && end) {
                formatted += ` - (${start} - ${end})`;
            }
            return formatted;
        },
        errorMessages() {
            return Object.values(this.form.errors).filter(Boolean);
        },
        sessionFullMessage() {
            return "This session has already reached its maximum capacity. You may still continue below to register — you'll be placed on the waitlist and offered a seat in the order you registered if a slot opens up. You can also browse and register for other open sessions anytime through the mobile app.";
        },
    },
    methods: {
        syncSignatureHeight() {
            if (window.innerWidth <= 900) {
                this.signPadHeight = 140;
                return;
            }

            const details = this.$refs.detailsColumn;
            const signWrap = this.$refs.signWrap;
            if (!details || !signWrap) return;

            const detailsBottom = details.getBoundingClientRect().bottom;
            const signTop = signWrap.getBoundingClientRect().top;
            const height = detailsBottom - signTop;

            if (height > 0) this.signPadHeight = Math.round(height);
        },
        clearError(field) {
            this.form.errors[field] = false;
            this.form.errors.fullname = false;
        },
        onAffiliationChange() {
            this.clearError('affiliation_id');
            if (!this.isAffiliationOthers) {
                this.form.others = null;
                this.clearError('others');
            }
        },
        onAffiliationSearch(keyword) {
            // Purely client-side now — the full list is already loaded, so
            // typing just narrows affiliationOptions, no request involved.
            this.affiliationKeyword = keyword;
        },
        fetchAffiliation(){
            axios.get('/search',{
                params: {
                    option: 'search',
                    type: 'Affiliation',
                    keyword: ''
                }
            })
            .then(response => {
                this.affiliations = response.data;
            })
            .catch(err => console.log(err));
        },
        openPrivacyModal() {
            if (!this.form.avatar) {
                this.requirementModalMessage = 'Please capture your avatar before you can agree to the Terms of Service and Privacy Policy.';
                return;
            }
            const pad = this.$refs.signaturePad;
            if (!pad || pad.isEmpty()) {
                this.requirementModalMessage = 'Please provide your signature before you can agree to the Terms of Service and Privacy Policy.';
                return;
            }
            this.showPrivacyModal = true;
        },
        acceptPrivacy() {
            this.form.check = true;
            this.showPrivacyModal = false;
        },
        onlyNumber(e) {
            const char = String.fromCharCode(e.which ?? e.keyCode);
            if (!/[0-9]/.test(char)) e.preventDefault();
        },
        formatTime(time) {
            if (!time) return null;
            const [hours, minutes] = time.split(':');
            const date = new Date();
            date.setHours(Number(hours), Number(minutes), 0, 0);
            return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        },
        onMobileInput() {
            this.form.mobile = (this.form.mobile || '').replace(/\D/g, '').slice(0, 11);
            this.clearError('mobile');
        },
        validate() {
            const errs = {};
            const f = this.form;
            if (!f.firstname || !f.middlename || !f.lastname) {
                errs.fullname = 'First, middle, and last name are required.';
            }
            if (!f.email) errs.email = 'Email address is required.';
            if (!f.mobile) errs.mobile = 'Mobile number is required.';
            else if (f.mobile.length !== 11) errs.mobile = 'Mobile number must be 11 digits.';
            if (!f.birthdate) errs.birthdate = 'Birth date is required.';
            if (!f.sex_id) errs.sex_id = 'Please select your sex.';
            if (!f.designation) errs.designation = 'Designation is required.';
            if (!f.affiliation_id) errs.affiliation_id = 'Affiliation is required.';
            if (this.isAffiliationOthers && !f.others) {
                errs.others = 'Please specify your affiliation.';
            }
            if (!f.avatar) errs.avatar = 'Please capture your avatar.';
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
        captureAvatar() {
            const video = this.$refs.video;
            const canvas = this.$refs.avatarCanvas;
            if (!video || !canvas || !video.videoWidth) return;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            this.avatarPreview = canvas.toDataURL('image/jpeg', 0.9);
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
            this.form.avatar = null;
            this.clearError('avatar');
            this.startCamera();
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

            if (this.sessionFull && !this.capacityConfirmed) {
                this.showCapacityModal = true;
                return;
            }

            await this.finalizeSubmit();
        },
        async finalizeSubmit() {
            const pad = this.$refs.signaturePad;
            const dataUrl = pad.toDataURL('image/png');
            const blob = await fetch(dataUrl).then(res => res.blob());

            this.form.signature = new File(
                [blob],
                'signature.png',
                { type: 'image/png' }
            );

            this.form.post('/register', {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.form.reset();
                    this.form.clearErrors();
                    this.clearSignature();
                    this.stopCamera();
                    this.avatarPreview = null;
                    this.form.avatar = null;
                    this.capacityConfirmed = false;
                },
            });
        },
        confirmCapacityAndSubmit() {
            this.showCapacityModal = false;
            this.capacityConfirmed = true;
            this.finalizeSubmit();
        },
        cancelCapacityModal() {
            this.showCapacityModal = false;
            router.visit('/');
        },
        setupEchoListener() {
            if (!this.session) return;

            window.Echo.channel('capacity')
            .listen('CapacityEvent', (event) => {
                // CapacityEvent broadcasts { count, session } where `session`
                // is the session id (see App\Events\CapacityEvent), not
                // `session_id` — matching the wrong key here would silently
                // never fire.
                if (this.session.id == event.session) {
                    this.checkSessionCapacity(event.count);
                }
            });
        },
        checkSessionCapacity(count) {
            const capacity = this.session?.detail?.capacity;
            if (!capacity || this.sessionFull) return;

            // Purely informational — the real session_id always gets
            // submitted; the backend decides Pending vs Reserved (waitlist)
            // once it re-checks capacity, and broadcasts the result back.
            const registered = count ?? this.session.participants_count ?? 0;
            if (registered >= capacity) {
                this.sessionFull = true;
            }
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.syncSignatureHeight();
            if (window.ResizeObserver && this.$refs.detailsColumn) {
                this.detailsResizeObserver = new ResizeObserver(() => this.syncSignatureHeight());
                this.detailsResizeObserver.observe(this.$refs.detailsColumn);
            }
        });
        window.addEventListener('resize', this.syncSignatureHeight);
        this.checkSessionCapacity();
        this.setupEchoListener();
        this.fetchAffiliation();
    },
    beforeUnmount() {
        this.stopCamera();
        window.removeEventListener('resize', this.syncSignatureHeight);
        this.detailsResizeObserver?.disconnect();
    },
};
</script>

<style scoped>
/* ---------- Shared layout tokens (mirrored from Rstw.vue) ---------- */
.rstw-section {
    
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.rstw-container { max-width: 1320px; margin: 0 auto; }
.rstw-heading { text-align: center; max-width: 620px; margin: 0 auto 12px; }
.rstw-heading h1 { font-size: clamp(1.25rem, 2.4vw, 1.7rem); font-weight: 800; margin: 0 0 8px; letter-spacing: -.4px; }
.rstw-kicker {
    display: inline-block;
    color: var(--c-blue);
    font-weight: 700;
    font-size: 11px;
    letter-spacing: 1px;
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
.rstw-btn--ghost {
    background: #fff;
    color: var(--ink);
    border: 1.5px solid #e4e8ef;
}
.rstw-btn--ghost:hover { border-color: #c7ccd6; }
@keyframes rstwSpin { to { transform: rotate(360deg); } }

/* ---------- Registration form ---------- */
.rstw-reg__sub { color: #4b5563; margin-top: 6px; font-size: 12.5px; line-height: 1.45; }
.rstw-reg__note {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    max-width: 620px;
    margin: 5px auto 0;
    padding: 5px 5px;
    border-radius: 14px;
    text-align: left;
    background: linear-gradient(135deg, rgba(255, 207, 46, .16), rgba(246, 179, 10, .08));
    border: 1px solid rgba(246, 179, 10, .4);
    box-shadow: 0 6px 18px rgba(246, 179, 10, .12);
}
.rstw-reg__note-icon {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    display: grid;
    place-items: center;
    border-radius: 8px;
    background: var(--brand);
    color: #fff;
    font-size: 13px;
}
.rstw-reg__note-body { display: flex; flex-direction: column; gap: 2px; }
.rstw-reg__note-title { font-size: 12px; font-weight: 700; color: var(--ink); line-height: 1.3; }
.rstw-reg__note-text { font-size: 12px; font-weight: 500; color: #6b5b4d; line-height: 1.3; }
.rstw-form {
    position: relative;
    max-width: 1160px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid rgba(11, 17, 32, .08);
    border-radius: 24px;
    padding: 34px 34px 30px;
    /* Layered shadow: tight contact shadow + wide ambient lift */
    box-shadow:
        0 1px 3px rgba(11, 17, 32, .04),
        0 10px 24px rgba(11, 17, 32, .06),
        0 34px 60px rgba(11, 17, 32, .08);
}
/* Slim brand accent along the top edge of the card. Rounded on its own
   corners (rather than relying on overflow:hidden on .rstw-form) so it
   doesn't clip the Affiliation dropdown when it opens near the card edge. */
.rstw-form::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    border-radius: 24px 24px 0 0;
    background: linear-gradient(90deg, var(--c-blue), var(--c-blue-2), var(--c-gold), var(--c-orange), var(--c-red));
}
/* Grouped section legends */
.rstw-form__legend {
    position: relative;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: -4px 0 7px;
    padding-bottom: 9px;
    font-size: 11px;
    font-weight: 700;
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
.rstw-form__row { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 16px; margin-bottom: 16px; }
.rstw-form__row--quad { grid-template-columns: repeat(4, minmax(0, 1fr)); }
.rstw-field { display: flex; flex-direction: column; min-width: 0; }
.rstw-field label {
    font-size: 11px;
    font-weight: 600;
    color: var(--ink);
    margin-bottom: 2px;
    letter-spacing: .02em;
}
.rstw-field__hint {
    font-weight: 400;
    color: #6b7280;
    letter-spacing: normal;
    text-transform: none;
}
.rstw-field.has-error label { color: #dc2626; }
.rstw-field.has-error input,
.rstw-field.has-error select {
    border-color: #dc2626;
}
.rstw-field.has-error :deep(.multiselect) {
    border-color: #dc2626;
}
.rstw-field.has-error .rstw-sign__pad {
    border-color: #dc2626;
}
.rstw-field input,
.rstw-field select {
    height: 40px;
    border: 1.5px solid #e4e8ef;
    border-radius: 12px;
    padding: 0 16px;
    font-size: 16px;
    background: #f8fafc;
    transition: border-color .2s, box-shadow .2s, background .2s, transform .2s;
    width: 100%;
    /* .rstw-field is a flex column, and flex items default to min-width:auto —
       iOS Safari's native input[type=date] has a wide intrinsic content size
       (mm/dd/yyyy segments + icon) that ignores width:100% without this,
       forcing the row/grid wider than the viewport on mobile. */
    min-width: 0;
}
.rstw-field select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23a8b0bd'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; padding-right: 34px; }
.rstw-field input::placeholder { color: #a8b0bd; }
.rstw-field input:disabled { background-color: #eef0f3; color: #9aa3af; cursor: not-allowed; }
.rstw-field input:hover, .rstw-field select:hover { border-color: #c7ccd6; background-color: #fbfcfe; }
.rstw-field input:focus,
.rstw-field select:focus {
    outline: none;
    border-color: var(--c-blue);
    background-color: #fff;
    /* brand-blue focus ring + a slight lift so the active field stands out */
    box-shadow: 0 0 0 4px rgba(20, 76, 141, .13), 0 6px 16px rgba(20, 76, 141, .10);
    transform: translateY(-1px);
}
.rstw-form__error { color: #dc2626; font-size: 12px; margin-top: 6px; display: block; }
.rstw-error-summary {
    display: none;
}

/* Pre-registration not yet open */
.rstw-closed {
    max-width: 1160px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 12px;
    padding: 48px 32px;
    background: #fff;
    border: 1px solid rgba(11, 17, 32, .08);
    border-radius: 24px;
    box-shadow:
        0 1px 3px rgba(11, 17, 32, .04),
        0 10px 24px rgba(11, 17, 32, .06),
        0 34px 60px rgba(11, 17, 32, .08);
}
.rstw-closed__icon {
    display: grid;
    place-items: center;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(220, 38, 38, .14), rgba(220, 38, 38, .06));
    color: #dc2626;
    font-size: 32px;
}
.rstw-closed__title { margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--ink); }
.rstw-closed__text { margin: 0; max-width: 420px; font-size: 13.5px; line-height: 1.6; color: #4b5563; }
.rstw-closed__actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 6px; }
@media (max-width: 480px) {
    .rstw-closed__actions { flex-direction: column; width: 100%; max-width: 280px; }
    .rstw-closed__actions .rstw-btn { text-align: center; }
}

/* Multiselect themed to match the native inputs/selects */
.rstw-field :deep(.multiselect) {
    --ms-font-size: 16px;
    --ms-line-height: 1.4;
    --ms-py: 0;
    --ms-px: 16px;
    --ms-radius: 12px;
    --ms-border-width: 1.5px;
    --ms-border-color: #e4e8ef;
    --ms-placeholder-color: #a8b0bd;
    --ms-caret-color: #a8b0bd;
    --ms-clear-color: #a8b0bd;
    --ms-clear-color-hover: #6b7280;
    --ms-dropdown-bg: #fff;
    --ms-dropdown-border-color: #e4e8ef;
    --ms-dropdown-radius: 12px;
    --ms-max-height: 12.5rem;
    --ms-option-font-size: 16px;
    --ms-option-py: .55rem;
    --ms-option-bg-pointed: #eef2f7;
    --ms-option-color-pointed: var(--ink);
    --ms-option-bg-selected: var(--c-blue);
    --ms-option-color-selected: #fff;
    --ms-option-bg-selected-pointed: var(--c-blue-2);
    --ms-option-color-selected-pointed: #fff;
    /* min-height alone isn't enough — the vendor's own calc()-based
       min-height (from font-size/line-height/py) still wins at ~37.5px.
       Pin an explicit height so it matches the 40px native inputs exactly. */
    min-height: 40px;
    height: 40px;
    border-color: var(--ms-border-color);
    background: #f8fafc;
    transition: border-color .2s, box-shadow .2s, background .2s, transform .2s;
}
/* Set directly (not just via --ms-font-size) so the actual search/label
   elements can't fall back to the vendor's 1rem default under any
   specificity or inheritance edge case — iOS Safari zooms on focus for
   anything computing under 16px. */
.rstw-field :deep(.multiselect),
.rstw-field :deep(.multiselect-search),
.rstw-field :deep(.multiselect-single-label),
.rstw-field :deep(.multiselect-placeholder) {
    font-size: 13px;
}
.rstw-field :deep(.multiselect:hover) {
    border-color: #c7ccd6;
    background: #fbfcfe;
}
.rstw-field :deep(.multiselect.is-active) {
    border-color: var(--c-blue);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(20, 76, 141, .13), 0 6px 16px rgba(20, 76, 141, .10);
    transform: translateY(-1px);
}
.rstw-field :deep(.multiselect-dropdown) {
    box-shadow: 0 10px 24px rgba(11, 17, 32, .10);
    /* iOS Safari sometimes ignores z-index for -webkit-overflow-scrolling
       layers; forcing a fresh stacking context here keeps the dropdown
       above sibling content (e.g. the signature canvas) instead of under it. */
    z-index: 60;
}
/* "Others" is always the last option appended in affiliationOptions — pin it
   to the bottom of the scrollable list so it stays reachable without having
   to scroll all the way through the search results first. */
.rstw-field :deep(.multiselect-options .multiselect-option:last-child) {
    position: sticky;
    bottom: 0;
    z-index: 1;
    border-top: 1px solid #e4e8ef;
    box-shadow: 0 -4px 8px rgba(11, 17, 32, .04);
}
.rstw-field :deep(.multiselect-options .multiselect-option:last-child:not(.is-pointed):not(.is-selected)) {
    background: #fff;
}
.rstw-field :deep(.multiselect.is-open) {
    position: relative;
    z-index: 60;
}
/* Lift the whole column above its sibling (e.g. Attestation) while the
   Affiliation dropdown is open, so it can't be painted over on iOS. */
.rstw-form__bottom-col:has(.multiselect.is-open) {
    position: relative;
    z-index: 5;
}

/* Submit button icon + processing spinner */
.rstw-form__submit i { font-size: 18px; }
.rstw-spin { display: inline-block; animation: rstwSpin 1s linear infinite; }

/* Signature pad */
/* z-index:0 forces its own stacking context — iOS Safari can otherwise
   composite the <canvas> above other absolutely-positioned siblings
   (like the Affiliation dropdown) regardless of source order/z-index. */
.rstw-sign { position: relative; z-index: 0; }
.rstw-sign__pad {
    width: 100% !important;
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
    bottom: 8px;
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

/* Form layout — two independent columns. Each column flows its own blocks
   top-to-bottom with no shared row alignment, so a short block (photo, submit)
   never leaves a gap waiting for the taller column. */
.rstw-form__grid {
    display: grid;
    grid-template-columns: 250px minmax(0, 1fr);
    gap: 24px;
    align-items: start;
}
.rstw-col { display: flex; flex-direction: column; min-width: 0; }
.rstw-form__verify { margin-top: 20px; }
.rstw-form__verify .rstw-form__legend:first-child { margin-top: 0; }
.rstw-form__foot {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: end;
    gap: 24px;
    margin-top: 22px;
    padding-top: 20px;
    border-top: 1px solid rgba(11, 17, 32, .08);
}
.rstw-form__consent {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
/* Photo label sits above a centred circle, so centre it to match */
.rstw-form__aside .rstw-field label { text-align: center; }

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
.rstw-photo.is-invalid .rstw-photo__stage {
    border-style: solid;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, .15);
}
.rstw-photo.is-live .rstw-photo__stage {
    border-style: solid;
    border-color: #f1a442;
    box-shadow: 0 0 0 3px rgba(241, 164, 66, .18);
}
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
/* Caption under the photo circle — helper text, or the success message once a
   photo is captured. Kept outside the circle so it never crowds the image. */
.rstw-photo__caption {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin: 0;
    max-width: 230px;
    text-align: center;
    font-size: 12.5px;
    line-height: 1.5;
    color: #9a8574;
}
.rstw-photo__caption--ok { color: #2e8b4f; font-weight: 700; }
.rstw-photo__caption--ok i { font-size: 15px; }
.rstw-photo__caption--error { color: #dc2626; font-weight: 700; }
.rstw-photo__caption--error i { font-size: 15px; }
.rstw-photo__warning {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    max-width: 230px;
    padding: 10px 12px;
    border-radius: 12px;
    text-align: left;
    background: linear-gradient(135deg, rgba(255, 207, 46, .16), rgba(246, 179, 10, .08));
    border: 1px solid rgba(246, 179, 10, .4);
}
.rstw-photo__warning i {
    flex-shrink: 0;
    font-size: 15px;
    color: #b8860b;
    margin-top: 1px;
}
.rstw-photo__warning span {
    font-size: 11px;
    line-height: 1.5;
    color: #6b5b4d;
}
.rstw-photo__warning--danger {
    background: linear-gradient(135deg, rgba(220, 38, 38, .12), rgba(220, 38, 38, .06));
    border-color: rgba(220, 38, 38, .35);
}
.rstw-photo__warning--danger i { color: #dc2626; }
.rstw-photo__warning--danger span { color: #b91c1c; font-weight: 600; }
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
.rstw-photo__actions { display: flex; justify-content: center; gap: 10px; width: 100%; max-width: 230px; margin: 0 auto; }
.rstw-photo__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
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

.rstw-check { display: flex; gap: 9px; align-items: center; margin: 0; font-size: 12px; color: #4b5563; cursor: pointer; }
.rstw-check input { width: 14px; height: 14px; accent-color: var(--brand); flex-shrink: 0; }
.rstw-check.is-disabled { opacity: .5; cursor: not-allowed; }
.rstw-check.is-disabled input { cursor: not-allowed; }
.rstw-check span { white-space: nowrap; }
.rstw-check a { color: var(--brand); font-weight: 600; }
.rstw-check__link {
    background: none;
    border: 0;
    padding: 0;
    margin: 0;
    font: inherit;
    color: var(--brand);
    font-weight: 600;
    text-decoration: underline;
    cursor: pointer;
}
.rstw-form__submit {
    display: inline-flex; align-items: center; justify-content: center; gap: 9px;
    justify-self: end;
    width: auto; border: 0; cursor: pointer;
    padding: 10px 28px;
    font-size: 14px; font-weight: 700;
    border-radius: 14px;
    transition: transform .2s, box-shadow .2s, opacity .2s;
}
.rstw-form__submit:not(:disabled):hover { transform: translateY(-2px); box-shadow: 0 16px 34px rgba(226, 32, 50, .32); }
.rstw-form__submit:disabled { opacity: .45; cursor: not-allowed; transform: none; box-shadow: none; }

@media (max-width: 900px) {
    .rstw-form { padding: 28px 22px; }
    /* Single column. Dissolve the column wrappers so both blocks become
       flex items, then order them: photo → details.
       Explicit width:100% guarantees each block fills the row regardless of
       flex-item sizing quirks under display:contents. */
    .rstw-form__grid { display: flex; flex-direction: column; gap: 24px; }
    .rstw-col { display: contents; }
    .rstw-form__aside,
    .rstw-form__main { width: 100%; }
    .rstw-form__aside { margin-bottom: 20px; }
    .rstw-form__aside  { order: 1; }
    .rstw-form__main   { order: 2; }
    .rstw-form__row,
    .rstw-form__row--quad { grid-template-columns: 1fr; gap: 10px; }
    /* Footer stacks: consent checkboxes above, full-width submit below */
    .rstw-form__foot { grid-template-columns: 1fr; }
    .rstw-form__submit { justify-self: stretch; width: 100%; }
    .rstw-check { align-items: flex-start; }
    .rstw-check span { white-space: normal; }
    /* Safety net: force a sane pad height on mobile regardless of any
       stale desktop-computed inline height, and give it room to breathe
       before the footer checkboxes below it. */
    .rstw-sign__pad { height: 140px !important; }
    .rstw-form__bottom-col { margin-bottom: 16px; }
    .rstw-form__bottom-col:last-child { margin-bottom: 0; }
    /* The 230px cap on these keeps them nicely narrow next to the aside's
       photo circle on desktop, but on mobile the aside is full-width — let
       them stretch to match the Personal Information fields' margins
       instead of sitting stranded in a narrow centered column. */
    .rstw-photo__warning,
    .rstw-photo__actions {
        max-width: none;
    }
    .rstw-error-summary {
        display: block;
        margin: 0 0 14px;
        padding: 12px 16px;
        border-radius: 12px;
        background: #fef2f2;
        border: 1px solid rgba(220, 38, 38, .3);
        color: #b91c1c;
        font-size: 13px;
        line-height: 1.6;
    }
}

@media (max-width: 640px) {
    .rstw-section { padding: 12px 16px; }
}
.rstw-heading{
    max-width:1160px;   /* same width as form */
    margin:0 auto 18px;
}

.rstw-session{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    padding:12px 18px;
    text-align:left;
    background:linear-gradient(
        90deg,
        var(--c-red),
        var(--c-orange),
        var(--c-gold),
        var(--c-blue-2),
        var(--c-blue)
    );
    color:#fff;
}
.rstw-session__left{ display:flex; align-items:center; gap:12px; min-width:0; }
.rstw-session__logo{ flex-shrink:0; height:38px; width:auto; display:block; }
.rstw-session__text{ min-width:0; }
.rstw-session__brand{
    flex-shrink:0;
    display:flex;
    align-items:center;
    height:100%;
}
.rstw-session__brand-img{ height:34px; width:auto; display:block; }
.rstw-session__brand--mobile{ display:none; }

.rstw-session__label{
    display:block;
    font-size:.85rem;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:.03em;
    line-height:1.3;
    color:#fff;
    text-shadow:0 1px 4px rgba(0,0,0,.4);
}

.rstw-session__title{
    margin:2px 0 0;
    font-size:clamp(.7rem,1vw,.55rem);
    font-weight:700;
    line-height:1.3;
    color:#fff;
    text-shadow:0 1px 4px rgba(0,0,0,.4);
}
.rstw-session__title-accent{ color:#fff; }
.rstw-session__title-brand{
    color:#fff;
    text-shadow:
        0 1px 4px rgba(0,0,0,.4),
        0 0 6px rgba(255,255,255,.9),
        0 0 14px rgba(255,255,255,.6);
}
.rstw-session__title-tag{ font-weight:500; color:#fff; margin-left:2px; }

.rstw-reg__note{
    width:100%;
    max-width:none;      /* remove 620px limit */
    margin:0;
    display:flex;
    gap:10px;
    padding:10px 12px;
    border-left:4px solid #f6b30a;
    border-radius:10px;
    background:#fff8e6;
    border:1px solid rgba(246,179,10,.35);
    border-left-width:4px;
    text-align:left;
}
.rstw-reg__note--danger{
    border-left-color:#dc2626;
    background:#fef2f2;
    border-color:rgba(220,38,38,.35);
}
.rstw-reg__note--danger .rstw-reg__note-icon{ background:#dc2626; }
.rstw-card{
    display:flex;
    flex-direction:column;
    width:100%;
    margin:10px 0 0;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 22px rgba(20,76,141,.16);
}
.rstw-card__body{
    display:flex;
    flex-direction:column;
    gap:12px;
    padding:14px 18px;
    background:#f8fafc;
    text-align:left;
}
.rstw-info__cols{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px 24px;
}
.rstw-info__col{
    display:flex;
    flex-direction:column;
    gap:8px;
}
.rstw-info__item{
    display:flex;
    flex-direction:column;
    gap:1px;
}
.rstw-info__label{
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.04em;
    color:var(--c-blue);
}
.rstw-info__value{
    font-size:13.5px;
    font-weight:600;
    color:var(--ink);
}
.rstw-info__badge{
    display:inline-block;
    margin-left:6px;
    padding:2px 8px;
    border-radius:999px;
    font-size:10px;
    font-weight:800;
    letter-spacing:.04em;
    color:#fff;
    background:#dc2626;
    vertical-align:middle;
}
@media (max-width:640px){
    .rstw-info__cols{ grid-template-columns:1fr; }
    .rstw-session{ padding:10px 12px; gap:10px; }
    .rstw-session__left{ gap:8px; }
    .rstw-session__logo{ height:26px; }
    .rstw-session__brand-img{ height:24px; }
    .rstw-session__label{ font-size:.65rem; }
    .rstw-session__title{ font-size:.6rem; }
    .rstw-session__brand--desktop{ display:none; }
    .rstw-session__brand--mobile{
        display:flex;
        justify-content:center;
        margin-bottom:12px;
    }
    .rstw-session__brand--mobile .rstw-session__brand-img{ height:36px; }
}
.rstw-form__bottom{
    display:grid;
    grid-template-columns:repeat(4, minmax(0, 1fr));
    gap:16px;
    align-items:stretch;
}
.rstw-form__bottom > div{
    min-width:0;
    grid-column:span 2;
}
.rstw-form__bottom-col .rstw-form__legend{
    margin-top:0;
}
.rstw-form__details{
    display:flex;
    flex-direction:column;
    gap:4px;
}

@media (max-width:900px){
    .rstw-form__bottom { grid-template-columns:1fr; }
    .rstw-form__bottom > div { grid-column:span 1; }
}

.rstw-form__row--single{
    display:grid;
    grid-template-columns:1fr;
    gap:16px;
}

.rstw-form__verify{
    margin-top:0;
}

.rstw-sign__pad{
    height:126px;
}

/* Privacy Notice modal */
.rstw-modal{
    position:fixed;
    inset:0;
    z-index:1000;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:20px;
    background:rgba(11,17,32,.55);
}
.rstw-modal__card{
    width:100%;
    max-width:560px;
    max-height:min(80vh, 640px);
    display:flex;
    flex-direction:column;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 24px 60px rgba(11,17,32,.3);
}
.rstw-modal__head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:16px 20px;
    background:linear-gradient(90deg, var(--c-blue), var(--c-blue-2));
    color:#fff;
}
.rstw-modal__title{
    margin:0;
    font-size:16px;
    font-weight:800;
    color: white;
}
.rstw-modal__close{
    flex-shrink:0;
    display:grid;
    place-items:center;
    width:28px;
    height:28px;
    border:0;
    border-radius:8px;
    background:rgba(255,255,255,.15);
    color:#fff;
    font-size:16px;
    cursor:pointer;
    transition:background .2s;
}
.rstw-modal__close:hover{ background:rgba(255,255,255,.28); }
.rstw-modal__body{
    flex:1;
    overflow-y:auto;
    padding:18px 20px;
    text-align:left;
}
.rstw-modal__body p{
    margin:0 0 14px;
    font-size:13.5px;
    line-height:1.6;
    color:#4b5563;
}
.rstw-modal__body p:last-child{ margin-bottom:0; }
.rstw-modal__foot{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    padding:14px 20px;
    border-top:1px solid rgba(11,17,32,.08);
    background:#f8fafc;
}
.rstw-modal__btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border:0;
    border-radius:10px;
    padding:9px 20px;
    font-size:13px;
    font-weight:700;
    cursor:pointer;
    transition:transform .2s, box-shadow .2s, opacity .2s;
}
.rstw-modal__btn--ghost{
    background:#fff;
    color:#4b5563;
    border:1px solid rgba(11,17,32,.12);
}
.rstw-modal__btn--ghost:hover{ background:#f1f3f6; }
.rstw-modal__btn--primary{
    background:linear-gradient(135deg, var(--brand), var(--brand-2));
    color:#fff;
    box-shadow:0 10px 22px rgba(226,32,50,.28);
}
.rstw-modal__btn--primary:hover{ transform:translateY(-1px); }

/* Compact warning variant, e.g. "missing requirement" prompts */
.rstw-modal__card--sm{ max-width:400px; max-height:none; }
.rstw-modal__head--warning{
    background:linear-gradient(90deg, #f6b30a, #e2762a);
}
.rstw-modal__head--warning .rstw-modal__title{
    display:flex;
    align-items:center;
    gap:8px;
}
.rstw-modal__head--warning .rstw-modal__title i{
    font-size:18px;
}
.rstw-modal__body--center{
    display:flex;
    flex-direction:column;
    align-items:center;
    text-align:center;
    gap:12px;
    padding:24px 20px;
}
.rstw-modal__body--center p{
    margin:0;
    font-size:14px;
    font-weight:500;
    color:var(--ink);
}
.rstw-modal__icon{
    display:grid;
    place-items:center;
    width:52px;
    height:52px;
    border-radius:50%;
    background:linear-gradient(135deg, rgba(246,179,10,.18), rgba(226,118,42,.14));
    color:#e2762a;
    font-size:28px;
}
.rstw-modal__foot--center{
    justify-content:center;
}

@media (max-width:640px){
    .rstw-modal__card{ max-height:85vh; }
    .rstw-modal__head{ padding:14px 16px; }
    .rstw-modal__body{ padding:14px 16px; }
    .rstw-modal__foot{ padding:12px 16px; flex-direction:column-reverse; }
    .rstw-modal__btn{ width:100%; }
}
.multiselect-wrapper {
    min-height: 40px !important;
}
</style>
