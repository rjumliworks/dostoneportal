<template>
    <Head title="Registration Successful — RSTW 2026" />

    <div class="rstw rstw-regpage">

        <main class="rstw-regpage__main">
            <section id="success" class="rstw-section">
                <div class="rstw-container rstw-reg">
                    <div class="rstw-heading" data-aos="fade-up">

                        <!-- Header: identical branding bar used on the registration form -->
                        <div class="rstw-card">
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

                            <!-- Body: success message -->
                            <div class="rstw-card__body rstw-success__body">
                                <Link href="/" class="rstw-session__brand rstw-session__brand--mobile">
                                    <img src="/images/2026/2026RSTW.png" alt="2026 RSTW" class="rstw-session__brand-img">
                                </Link>

                                <div class="rstw-success__icon">
                                    <i class="ri-checkbox-circle-fill"></i>
                                </div>

                                <h2 class="rstw-success__title">Registration Successful!</h2>

                                <p class="rstw-success__text" v-if="session && reserved">
                                    Thank you, <strong>{{ participantName }}</strong>! <strong>{{ session.title || 'This session' }}</strong>
                                    has already reached its maximum capacity, so you've been placed on the
                                    waitlist — you'll be offered a seat in the order you registered if one
                                    opens up.
                                </p>
                                <p class="rstw-success__text" v-else-if="session">
                                    Thank you, <strong>{{ participantName }}</strong>! Your registration
                                    for <strong>{{ session.title || 'this session' }}</strong><span v-if="sessionDate"> on {{ sessionDate }}</span><span v-if="session?.venue?.establishment"> at {{ session.venue.establishment }}</span>
                                    has been received.
                                </p>
                                <p class="rstw-success__text" v-else>
                                    Thank you, <strong>{{ participantName }}</strong>! Your registration
                                    has been received. You'll be able to register for specific sessions
                                    and exhibits anytime through the mobile app.
                                </p>

                                <template v-if="session">
                                    <div class="rstw-success__note" role="status">
                                        <span class="rstw-success__note-icon">
                                            <i class="ri-mail-check-line"></i>
                                        </span>
                                        <div class="rstw-success__note-body">
                                            <strong class="rstw-success__note-title">What happens next</strong>
                                            <span class="rstw-success__note-text" v-if="reserved">
                                                Please check your email for waitlist updates. You can check
                                                your status, or register for other sessions open to the
                                                public, anytime through the mobile app.
                                            </span>
                                            <span class="rstw-success__note-text" v-else>
                                                Please check your email for updates on the confirmation of
                                                your registration. You can also register for other sessions
                                                open to the public — each session shows its own registration
                                                status (open or closed) on the mobile app.
                                            </span>
                                        </div>
                                    </div>

                                    <div class="rstw-success__note rstw-success__note--info" role="status">
                                        <span class="rstw-success__note-icon">
                                            <i class="ri-smartphone-line"></i>
                                        </span>
                                        <div class="rstw-success__note-body">
                                            <strong class="rstw-success__note-title">Take the event with you</strong>
                                            <span class="rstw-success__note-text">
                                                Open <a href="https://mobile.dost9.ph" target="_blank" rel="noopener" class="rstw-success__link">mobile.dost9.ph</a>
                                                on your phone's browser to browse the full session schedule,
                                                explore the exhibitors, and catch live updates throughout the
                                                event — it works right in your browser, no download needed.
                                            </span>
                                        </div>
                                    </div>
                                </template>

                                <div class="rstw-success__note rstw-success__note--info" role="status" v-else>
                                    <span class="rstw-success__note-icon">
                                        <i class="ri-compass-3-line"></i>
                                    </span>
                                    <div class="rstw-success__note-body">
                                        <strong class="rstw-success__note-title">What happens next</strong>
                                        <span class="rstw-success__note-text">
                                            Open <a href="https://mobile.dost9.ph" target="_blank" rel="noopener" class="rstw-success__link">mobile.dost9.ph</a>
                                            on your phone's browser to view the different sessions,
                                            exhibits, and updates about the event — it works right in
                                            your browser, no download needed. Search for sessions that
                                            fit your interests — as long as they're open to the public,
                                            you can check if registration is already open and sign up.
                                        </span>
                                    </div>
                                </div>

                                
                                <div class="rstw-success__stores">
                                    <span class="rstw-success__store">
                                        <i class="ri-apple-fill"></i>
                                        <span>iOS App<em>Coming Soon</em></span>
                                    </span>
                                    <span class="rstw-success__store">
                                        <i class="ri-android-fill"></i>
                                        <span>Android App<em>Coming Soon</em></span>
                                    </span>
                                </div>

                                <Link href="/" class="rstw-success__home">
                                    <i class="ri-home-4-line"></i> Back to Home
                                </Link>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </main>

    </div>
</template>

<script>
import { Head, Link, usePage } from '@inertiajs/vue3';

export default {
    layout: null,
    components: { Head, Link },
    props: ['session'],
    computed: {
        participantName() {
            return usePage().props.flash?.data?.name || 'Registrant';
        },
        reserved() {
            return !!usePage().props.flash?.reserved;
        },
        sessionDate() {
            const raw = this.session?.schedules?.[0]?.date;
            if (!raw) return null;
            const date = new Date(raw);
            if (isNaN(date)) return raw;
            return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        },
    },
};
</script>

<style scoped>
/* Brand tokens are normally declared on .rstw in Rstw.vue; this page stands on
   its own, so it declares the ones the header and card need. */
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

.rstw-regpage__main { flex: 1; }

.rstw-section {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 100vh;
}
.rstw-container { max-width: 1320px; margin: 0 auto; width: 100%; }
.rstw-heading {
    max-width: 620px;
    margin: 0 auto;
    width: 100%;
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

/* ---------- Header bar (mirrored from Form.vue) ---------- */
.rstw-card{
    display:flex;
    flex-direction:column;
    width:100%;
    margin:10px 0 0;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 22px rgba(20,76,141,.16);
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

/* ---------- Success body ---------- */
.rstw-card__body{
    display:flex;
    flex-direction:column;
    gap:12px;
    padding:14px 18px;
    background:#f8fafc;
    text-align:left;
}
.rstw-success__body{
    align-items: center;
    text-align: center;
    padding: 36px 28px 32px;
    gap: 14px;
}
.rstw-success__icon{
    display: grid;
    place-items: center;
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(52, 168, 83, .16), rgba(52, 168, 83, .08));
    color: #2e8b4f;
    font-size: 42px;
}
.rstw-success__title{
    margin: 0;
    font-size: clamp(1.15rem, 2vw, 1rem);
    font-weight: 800;
    color: green;
}
.rstw-success__text{
    margin: 0;
    max-width: 480px;
    font-size: 14px;
    line-height: 1.6;
    color: #4b5563;
}
.rstw-success__note{
    width: 100%;
    max-width: 480px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 4px;
    padding: 12px 14px;
    border-radius: 14px;
    text-align: left;
    background: linear-gradient(135deg, rgba(255, 207, 46, .16), rgba(246, 179, 10, .08));
    border: 1px solid rgba(246, 179, 10, .4);
}
.rstw-success__note--info{
    background: linear-gradient(135deg, rgba(20, 76, 141, .10), rgba(70, 160, 193, .08));
    border-color: rgba(20, 76, 141, .25);
}
.rstw-success__note-icon{
    flex-shrink: 0;
    width: 26px;
    height: 26px;
    display: grid;
    place-items: center;
    border-radius: 8px;
    background: var(--brand);
    color: #fff;
    font-size: 14px;
}
.rstw-success__note--info .rstw-success__note-icon{ background: var(--c-blue); }
.rstw-success__note-body{ display: flex; flex-direction: column; gap: 2px; }
.rstw-success__note-title{ font-size: 12.5px; font-weight: 700; color: var(--ink); line-height: 1.3; }
.rstw-success__note-text{ font-size: 12.5px; font-weight: 500; color: #6b5b4d; line-height: 1.5; }
.rstw-success__note--info .rstw-success__note-text{ color: #375374; }
.rstw-success__link{ color: var(--c-blue); font-weight: 700; text-decoration: underline; }
.rstw-success__stores{
    display: flex;
    width: 100%;
    max-width: 480px;
    gap: 8px;
    margin-top: 4px;
}
.rstw-success__store{
    display: inline-flex;
    flex: 1;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 10px;
    background: rgba(107, 114, 128, .08);
    border: 1px solid rgba(107, 114, 128, .18);
    color: #6b7280;
}
.rstw-success__store i{ font-size: 18px; }
.rstw-success__store span{
    display: flex;
    flex-direction: column;
    line-height: 1.25;
    font-size: 11.5px;
    font-weight: 700;
    color: #4b5563;
}
.rstw-success__store em{
    font-style: normal;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .02em;
    color: #9aa3af;
}
.rstw-success__home{ margin-top: 10px; }

@media (max-width:640px){
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
    .rstw-success__body{ padding: 28px 18px 26px; }
}
</style>
