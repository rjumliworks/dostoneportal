<template>
    <Head title="RSTW 2026 — Regional Science, Technology, and Innovation Week" />

    <div class="rstw">
        <!-- Navbar -->
        <nav class="rstw-nav" :class="{ 'is-sticky': scrolled }" id="rstw-navbar">
            <div class="rstw-nav__inner">
                <!-- <a href="/" class="rstw-nav__brand">
                    <img src="/images/2026/2026RSTW.png" alt="2026 RSTW" class="rstw-nav__brand-img">
                </a> -->
                <button class="rstw-nav__toggle" type="button" @click="menuOpen = !menuOpen" aria-label="Menu">
                    <i class="mdi mdi-menu"></i>
                </button>
                <ul class="rstw-nav__menu" :class="{ 'is-open': menuOpen }">
                    <li><a href="#home" @click.prevent="scrollTo('home')">Home</a></li>
                    <li><a href="#about" @click.prevent="scrollTo('about')">About</a></li>
                    <li><a href="#pillars" @click.prevent="scrollTo('pillars')">Pillars</a></li>
                    <li><a href="#mobile-app" @click.prevent="scrollTo('mobile-app')">Mobile App</a></li>
                    <li><Link href="/highlights" @click="menuOpen = false">Highlights</Link></li>
                    <li><Link href="/accommodations" @click="menuOpen = false">Hotels</Link></li>
                    <li><a href="#contact" @click.prevent="scrollTo('contact')">Contact</a></li>
                    <li>
                        <Link href="/registration" class="rstw-nav__cta" @click="menuOpen = false">Register</Link>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Hero -->
        <header id="home" class="rstw-hero">

            <!-- Cultural PNG side banners (home section only) -->
            <div class="rstw-banner rstw-banner--left" aria-hidden="true"></div>
            <div class="rstw-banner rstw-banner--right-top" aria-hidden="true"></div>
            <div class="rstw-banner rstw-banner--right-mid" aria-hidden="true"></div>
            <div class="rstw-banner rstw-banner--right" aria-hidden="true"></div>

            <!-- Animated floating 2026 cultural icons (left side, clear of text) -->
            <div class="rstw-hero__floaters" aria-hidden="true">
                <span class="rstw-float rstw-float--1"></span>
                <span class="rstw-float rstw-float--2"></span>
                <span class="rstw-float rstw-float--3"></span>
                <span class="rstw-float rstw-float--4"></span>
                <span class="rstw-float rstw-float--5"></span>
                <span class="rstw-float rstw-float--6"></span>
            </div>

            <div class="rstw-hero__content">
                <div class="rstw-hero__orgs" data-aos="fade-down">
                    <img src="/images/2026/header_icons.png" alt="DOST Zamboanga Peninsula — OneDOST4U · Bagong Pilipinas · ASEAN Philippines 2026" class="rstw-org-logos" onerror="this.style.display='none'">
                </div>

                
                <span style="margin-bottom: 35px;" class="rstw-hero__eyebrow" data-aos="fade-up" data-aos-delay="50">Regional Science, Technology, and Innovation Week</span>

                <h1 class="rstw-hero__title" data-aos="fade-up" data-aos-delay="100">
                    <img
                        src="/images/2026/2026RSTW.png"
                        alt="2026 RSTW — Zamboanga Peninsula"
                        class="rstw-hero__wordmark-img"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                    >
                    <span class="rstw-hero__wordmark-fallback" style="display:none">
                        <span class="rstw-hero__lockup">
                            <span class="rstw-hero__year"><span>20</span><span>26</span></span>
                            <span class="rstw-hero__word">
                                <span class="rstw-hero__letters" style="display:inline-flex">
                                    <span class="rstw-hero__letter rstw-hero__letter--r">R</span><span class="rstw-hero__letter rstw-hero__letter--s">S</span><span class="rstw-hero__letter rstw-hero__letter--t">T</span><span class="rstw-hero__letter rstw-hero__letter--w">W</span>
                                </span>
                            </span>
                        </span>
                        <span class="rstw-hero__region">Zamboanga Peninsula</span>
                    </span>
                </h1>

                <!-- <p class="rstw-hero__theme" data-aos="fade-up" data-aos-delay="200">
                    <span class="rstw-hero__theme-lead"><b class="rstw-hero__theme-a">Science,</b> <b class="rstw-hero__theme-b">Technology,</b> &amp; <b class="rstw-hero__theme-a">Digital Innovation</b></span>
                    <span class="rstw-hero__theme-rest">Driving Food Security, Sustainable Energy, &amp; National Resilience</span>
                </p> -->

                <ul class="rstw-hero__meta" data-aos="fade-up" data-aos-delay="300" style="margin-bottom: 35px; margin-top: 35px;">
                    <li><i class="ri-map-pin-fill"></i> Zamboanga del Norte Convention and Sports Center</li>
                    <li><i class="ri-calendar-fill"></i> August 12–14, 2026</li>
                </ul>

                <!-- Countdown -->
                <div class="rstw-count" data-aos="fade-up" data-aos-delay="400" v-if="timeLeft.total > 0">
                    <div class="rstw-count__box"><span>{{ pad(timeLeft.days) }}</span><small>Days</small></div>
                    <div class="rstw-count__box"><span>{{ pad(timeLeft.hours) }}</span><small>Hours</small></div>
                    <div class="rstw-count__box"><span>{{ pad(timeLeft.minutes) }}</span><small>Minutes</small></div>
                    <div class="rstw-count__box"><span>{{ pad(timeLeft.seconds) }}</span><small>Seconds</small></div>
                </div>
                <div class="rstw-count rstw-count--live" data-aos="fade-up" v-else>
                    <span class="rstw-count__live">● The event has started!</span>
                </div>

                <!-- Partner / collaboration credits -->
                <!-- <img
                    src="/images/2026/collaboration.png"
                    class="rstw-hero__collab"
                    alt="In collaboration with Province of Zamboanga del Norte and DOST-TAPI. National Science, Technology, and Innovation Week — nstw.dost.gov.ph"
                    loading="lazy"
                    decoding="async"
                    width="2812"
                    height="334"
                    data-aos="fade-up"
                    data-aos-delay="450"
                /> -->

                <div class="rstw-hero__actions" data-aos="fade-up" data-aos-delay="500">
                    <Link href="/registration" class="rstw-btn rstw-btn--primary">Register Now</Link>
                    <a href="#about" class="rstw-btn rstw-btn--ghost" @click.prevent="scrollTo('about')">Learn More</a>
                </div>
            </div>
        </header>

        <!-- Cultural motif divider band -->
        <!-- About -->
        <section id="about" class="rstw-section">
            <div class="rstw-container rstw-about">
                <div class="rstw-about__media" data-aos="fade-right">
                    <div class="rstw-frame">
                        <iframe
                            src="https://www.youtube.com/embed/EfpxhrwzLTU?si=Xz4izzG6hOC34Hun"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="rstw-about__text" data-aos="fade-left">
                    <span class="rstw-kicker">About the Event</span>
                    <h2>Science, Technology &amp; Digital Innovation for a resilient future</h2>
                    <p>
                        The <strong>Regional Science, Technology, and Innovation Week (RSTW) 2026</strong> gathers
                        researchers, innovators, local governments, and communities across the
                        <strong>Zamboanga Peninsula</strong> around one mission — harnessing science, technology, and
                        digital innovation to build a stronger, more self-reliant region.
                    </p>
                    <p>Guided by this year&rsquo;s theme, RSTW 2026 puts the spotlight on three fronts where innovation matters most:</p>
                    <ul class="rstw-about__focus">
                        <li>
                            <i class="ri-plant-line"></i>
                            <div><strong>Food Security</strong><span>Smarter agriculture and food systems for every community.</span></div>
                        </li>
                        <li>
                            <i class="ri-flashlight-line"></i>
                            <div><strong>Sustainable Energy</strong><span>Cleaner, reliable energy that powers inclusive growth.</span></div>
                        </li>
                        <li>
                            <i class="ri-shield-check-line"></i>
                            <div><strong>National Resilience</strong><span>Science-based readiness against disasters and change.</span></div>
                        </li>
                    </ul>
                    <p>
                        Hosted by the <strong>Department of Science and Technology – Zamboanga Peninsula (DOST-IX)</strong>
                        under the <em>OneDOST4U</em> banner — <em>&ldquo;Solutions and Opportunities for All.&rdquo;</em>
                    </p>
                </div>
            </div>
        </section>

        <!-- Pillars -->
        <section id="pillars" class="rstw-section rstw-section--muted">
            <div class="rstw-container">
                <div class="rstw-heading" data-aos="fade-up">
                    <span class="rstw-kicker">DOST Pillars</span>
                    <h2>Four Strategic Pillars</h2>
                </div>
                <div class="rstw-pillars">
                    <article v-for="(p, i) in pillarItems" :key="i" class="rstw-card" data-aos="fade-up" :data-aos-delay="i * 100">
                        <div class="rstw-card__icon"><i :class="p.icon"></i></div>
                        <h3>{{ p.title }}</h3>
                        <p>{{ p.text }}</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- RSTW Trivia -->
        <section class="rstw-section rstw-section--muted rstw-trivia">
            <div class="rstw-cf rstw-cf--trivia" data-aos="zoom-in" @mouseenter="pauseTrivia" @mouseleave="resumeTrivia">
                <div class="rstw-cf__stage" @pointerdown="onCfDown($event, 'trivia')" @pointermove="onCfMove" @pointerup="onCfUp" @pointercancel="onCfUp">
                    <div class="rstw-cf__track">
                        <div v-for="(t, i) in triviaItems" :key="i" class="rstw-cf__card" :style="triviaCardStyle(i)" @click="setTrivia(i)">
                            <div class="rstw-trivia__card" :class="{ 'is-active': i === triviaActive }">
                                <div class="rstw-trivia__header">
                                    <span class="rstw-trivia__pill"><i class="ri-brain-line"></i> RSTW Trivia!</span>
                                    <span class="rstw-trivia__bulb"><i class="ri-lightbulb-flash-fill"></i></span>
                                </div>
                                <h2 class="rstw-trivia__title">
                                    <span class="t-blue">Did</span> <span class="t-orange">you</span> <span class="t-blue">know</span><span class="t-red">?</span>
                                </h2>
                                <p class="rstw-trivia__text" v-html="t"></p>
                                <span class="rstw-trivia__tail" aria-hidden="true"></span>
                            </div>
                        </div>
                    </div>
                    <button class="rstw-cf__arrow rstw-cf__arrow--prev" type="button" @click.stop="stepTrivia(-1)" aria-label="Previous"><i class="ri-arrow-left-s-line"></i></button>
                    <button class="rstw-cf__arrow rstw-cf__arrow--next" type="button" @click.stop="stepTrivia(1)" aria-label="Next"><i class="ri-arrow-right-s-line"></i></button>
                </div>
                <div class="rstw-cf__counter">{{ pad(triviaActive + 1) }} <em>/ {{ pad(triviaItems.length) }}</em></div>
                <div class="rstw-cf__dots" role="tablist">
                    <button v-for="(t, i) in triviaItems" :key="i" type="button" class="rstw-cf__dot" :class="{ 'is-active': i === triviaActive }" @click="setTrivia(i)" :aria-label="`Trivia ${i + 1}`"></button>
                </div>
            </div>
        </section>

        <!-- Download App -->
        <section id="mobile-app" class="rstw-section">
            <div class="rstw-container rstw-app">
                <div class="rstw-app__text" data-aos="fade-right">
                    <span class="rstw-kicker">Mobile App</span>
                    <h2>Take RSTW 2026 with you</h2>
                    <p>Everything you need for RSTW 2026 in one app — register in minutes, check in hands-free,
                       scan into sessions, share your feedback, and collect points as you go.
                       Available now on <strong>Android</strong> and <strong>iOS</strong>.</p>

                    <ul class="rstw-app__features">
                        <li><i class="ri-user-add-line"></i> Quick &amp; easy registration</li>
                        <li><i class="ri-user-received-line"></i> Your face is your pass</li>
                        <li><i class="ri-calendar-check-line"></i> Save your seat</li>
                        <li><i class="ri-survey-line"></i> Share feedback with the CSF survey</li>
                        <li><i class="ri-medal-line"></i> Earn points &amp; rewards</li>
                    </ul>

                    <div class="rstw-app__stores">
                        <a
                            :href="playStoreUrl || null"
                            :target="playStoreUrl ? '_blank' : null"
                            rel="noopener"
                            class="rstw-store"
                            @click="!playStoreUrl && $event.preventDefault()"
                        >
                            <i class="ri-google-play-fill"></i>
                            <span><small>GET IT ON</small>Google Play</span>
                        </a>
                        <a
                            :href="appStoreUrl || null"
                            :target="appStoreUrl ? '_blank' : null"
                            rel="noopener"
                            class="rstw-store"
                            @click="!appStoreUrl && $event.preventDefault()"
                        >
                            <i class="ri-apple-fill"></i>
                            <span><small>Download on the</small>App Store</span>
                        </a>
                    </div>
                </div>

                <div class="rstw-app__visual" data-aos="fade-left">
                    <div class="rstw-phone">
                        <div class="rstw-phone__notch"></div>
                        <div class="rstw-phone__screen">
                            <span class="rstw-phone__badge">RSTW</span>
                            <span class="rstw-phone__year">2026</span>
                            <span class="rstw-phone__tag">Official Event App</span>
                        </div>
                    </div>

                    <a
                        :href="playStoreUrl || null"
                        :target="playStoreUrl ? '_blank' : null"
                        rel="noopener"
                        class="rstw-btn rstw-btn--primary rstw-app__download"
                        @click="!playStoreUrl && $event.preventDefault()"
                    >
                        <i class="ri-download-2-line"></i> Download App
                    </a>
                </div>
            </div>

            <!-- ── User manual: pick a task, follow the steps ── -->
            <div class="rstw-container rstw-guide" data-aos="fade-up">
                <div class="rstw-guide__head">
                    <span class="rstw-kicker">User Manual</span>
                    <h3>How to use the app</h3>
                    <p>Choose a task to see the steps.</p>

                    <button class="rstw-guide__book" type="button" @click="manualOpen = true">
                        <i class="ri-book-open-line"></i>
                        <span>Read the full manual</span>
                        <em>PDF</em>
                    </button>
                </div>

                <div class="rstw-guide__body">
                    <!-- Task picker -->
                    <div class="rstw-guide__tabs" role="tablist" aria-label="App tasks">
                        <button
                            v-for="(g, i) in guides"
                            :key="g.id"
                            :id="`guide-tab-${g.id}`"
                            class="rstw-guide__tab"
                            :class="{ 'is-active': guideActive === i }"
                            role="tab"
                            type="button"
                            :aria-selected="guideActive === i"
                            :aria-controls="`guide-panel-${g.id}`"
                            @click="guideActive = i"
                            @keydown="onGuideKey($event, i)"
                        >
                            <span class="rstw-guide__tab-icon"><i :class="g.icon"></i></span>
                            <span class="rstw-guide__tab-text">
                                <strong>{{ g.title }}</strong>
                                <small>{{ g.steps.length }} steps · {{ g.time }}</small>
                            </span>
                            <i class="ri-arrow-right-s-line rstw-guide__tab-chev"></i>
                        </button>
                    </div>

                    <!-- Steps for the active task -->
                    <div
                        v-for="(g, i) in guides"
                        v-show="guideActive === i"
                        :key="g.id"
                        :id="`guide-panel-${g.id}`"
                        class="rstw-guide__panel"
                        role="tabpanel"
                        :aria-labelledby="`guide-tab-${g.id}`"
                        tabindex="0"
                    >
                        <ol class="rstw-guide__steps">
                            <li v-for="(s, n) in g.steps" :key="n" class="rstw-guide__step">
                                <span class="rstw-guide__num">{{ n + 1 }}</span>
                                <div class="rstw-guide__step-body">
                                    <strong>{{ s.title }}</strong>
                                    <span>{{ s.text }}</span>
                                </div>
                            </li>
                        </ol>

                        <p v-if="g.tip" class="rstw-guide__tip">
                            <i class="ri-lightbulb-flash-line"></i>
                            <span><strong>Tip:</strong> {{ g.tip }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Register -->
        <!-- Register (extracted to Partials/PreRegistrationForm.vue) -->
        <!-- <PreRegistrationForm :dropdowns="dropdowns" /> -->

        <!-- CTA -->
        <section class="rstw-cta">
            <div class="rstw-container rstw-cta__inner" data-aos="zoom-in">
                <div class="rstw-cta__glow rstw-cta__glow--1"></div>
                <div class="rstw-cta__glow rstw-cta__glow--2"></div>
                <div class="rstw-cta__pattern"></div>

                <div class="rstw-cta__text">
                    <span class="rstw-cta__badge"><i class="ri-sparkling-2-fill"></i> Registration is open</span>
                    <h2>Be one of our participants</h2>
                    <p>Join researchers, innovators, and changemakers shaping the future at RSTW 2026.</p>
                    <ul class="rstw-cta__meta">
                        <li><i class="ri-calendar-fill"></i> August 12–14, 2026</li>
                        <li><i class="ri-user-star-line"></i> Free admission</li>
                    </ul>
                </div>

                <div class="rstw-cta__action">
                    <Link href="/registration" class="rstw-btn rstw-btn--light rstw-cta__btn">
                        Register Now <i class="ri-arrow-right-line"></i>
                    </Link>
                    <span class="rstw-cta__note">Takes less than 2 minutes</span>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="rstw-footer">
            <div class="rstw-container rstw-footer__grid">
                <div class="rstw-footer__brand">
                    <div class="rstw-footer__logo">
                        <img src="/images/2026/2026RSTW.png" alt="2026 RSTW" class="rstw-footer__logo-img">
                    </div>
                    <p class="rstw-footer__region">Zamboanga Peninsula</p>
                    <p>Department of Science and Technology — Zamboanga Peninsula (DOST-IX)</p>
                    <p class="rstw-footer__tag">
                        &ldquo;Science, Technology, &amp; Digital Innovation Driving Food Security,
                        Sustainable Energy, and National Resilience.&rdquo;
                    </p>
                </div>
                <div class="rstw-footer__contact">
                    <h4>Contact Details</h4>
                    <ul>
                        <li>DOST Regional Office No. IX</li>
                        <li>Pettit Barracks, Zamboanga City, Philippines</li>
                        <li>Tel.: (062) 991-1024</li>
                        <li>Email: ord@ro9.dost.gov.ph</li>
                    </ul>
                </div>
            </div>

            <!-- Partnership credits -->
            <div class="rstw-footer__collab">
                <img
                    src="/images/2026/colab_footer.png"
                    class="rstw-footer__collab-img"
                    alt="In partnership with the Provincial Government of Zamboanga del Norte. region9.dost.gov.ph — DOST Regional Office No. IX"
                    loading="lazy"
                    decoding="async"
                    width="3131"
                    height="100"
                />
            </div>

            <div class="rstw-footer__bar">
                <span>{{ new Date().getFullYear() }} © DOST-IX ICT</span>
                <ul class="rstw-footer__social">
                    <li><a href="javascript:void(0)"><i class="ri-facebook-fill"></i></a></li>
                    <li><a href="javascript:void(0)"><i class="ri-global-line"></i></a></li>
                    <li><a href="javascript:void(0)"><i class="ri-mail-line"></i></a></li>
                </ul>
            </div>
        </footer>

        <button class="rstw-top" :class="{ 'is-visible': scrolled }" @click="topFunction" aria-label="Back to top">
            <i class="ri-arrow-up-line"></i>
        </button>

        <!-- Downloadable app manual (teleports itself to <body>) -->
        <!-- <AppManualModal :open="manualOpen" @close="manualOpen = false" /> -->

        <!-- Floating countdown — always visible to visitors as they browse -->
        <Link href="/registration" class="rstw-countbar" :class="{ 'is-visible': scrolled && timeLeft.total > 0 }"
           aria-label="Countdown to RSTW 2026 — register now">
            <span class="rstw-countbar__label"><i class="ri-timer-flash-line"></i> RSTW 2026 in</span>
            <span class="rstw-countbar__unit">{{ pad(timeLeft.days) }}<small>d</small></span>
            <span class="rstw-countbar__unit">{{ pad(timeLeft.hours) }}<small>h</small></span>
            <span class="rstw-countbar__unit">{{ pad(timeLeft.minutes) }}<small>m</small></span>
            <span class="rstw-countbar__unit">{{ pad(timeLeft.seconds) }}<small>s</small></span>
        </Link>
    </div>
</template>

<script>
import AOS from 'aos';
import 'aos/dist/aos.css';
import PreRegistrationForm from './Form.vue';
// import AppManualModal from './Partials/AppManualModal.vue';
// import { GUIDES } from '../data/appManual.js';

export default {
    layout: null,
    components: { PreRegistrationForm },
    props: ['dropdowns'],
    data() {
        return {
            targetDate: new Date('2026-08-12T08:00:00'),
            timeLeft: { total: 0, days: 0, hours: 0, minutes: 0, seconds: 0 },
            countdownInterval: null,
            triviaActive: 0,
            triviaTimer: null,
            triviaItems: [
                "Regional Science, Technology, and Innovation Week is more than an exhibit&mdash;it's where <b>innovators</b>, <b>entrepreneurs</b>, <b>students</b>, <b>researchers</b>, and <b>communities</b> come together to discover how science and technology improve everyday life.",
                "Many products you'll see at RSTW were <b>developed or improved</b> with the help of <b>DOST</b> through <b>science, technology, and innovation</b>. Behind every product is a story of <b>local ingenuity</b> and <b>community development</b>.",
                "Innovation doesn't always mean inventing something new. It can also mean <b>improving existing products, services, or processes</b> to make life <b>easier</b> and <b>businesses more competitive</b>.",
            ],
            pillarActive: 0,
            pillarTimer: null,
            // The four strategic pillars of DOST
            pillarItems: [
                { icon: 'ri-heart-pulse-line', title: 'Human Well-being', text: 'Improving the quality of life of Filipinos through STI — health, education, food security, disaster resilience, and social development.' },
                { icon: 'ri-line-chart-line', title: 'Wealth Creation', text: 'Driving economic growth through innovation, entrepreneurship, and technology commercialization for industries, MSMEs, and startups.' },
                { icon: 'ri-shield-check-line', title: 'Wealth Protection', text: 'Safeguarding people, communities, and national assets — disaster risk reduction, climate resilience, food safety, and cybersecurity.' },
                { icon: 'ri-leaf-line', title: 'Sustainability', text: 'Ensuring STI delivers long-term value — clean energy, sustainable agriculture, circular economy, and responsible innovation.' },
            ],
            // ----- App user manual -----
            manualOpen: false,
            guideActive: 0,
            guides: null,

            cfDrag: { active: false, startX: 0, movedX: 0, which: '' },
            scrolled: false,
            menuOpen: false,
            // Store links — set these to the live listings once published.
            playStoreUrl: '',
            appStoreUrl: '',
        };
    },
    methods: {
        pad(n) {
            return String(n).padStart(2, '0');
        },
        // ----- Shared 3D coverflow geometry -----
        cfOffset(active, len, i) {
            let o = i - active;
            if (o > len / 2) o -= len;
            if (o < -len / 2) o += len;
            return o;
        },
        cfStyle(active, len, i) {
            const o = this.cfOffset(active, len, i);
            const abs = Math.abs(o);
            const sign = Math.sign(o);
            const hidden = abs > 2;
            return {
                transform: `translateX(${o * 56}%) translateZ(${-abs * 150}px) rotateY(${sign * -32}deg) scale(${1 - abs * 0.08})`,
                zIndex: 100 - abs,
                opacity: hidden ? 0 : 1,
                pointerEvents: hidden ? 'none' : 'auto',
                filter: abs === 0 ? 'none' : `brightness(${1 - abs * 0.12})`,
            };
        },
        // ----- Trivia carousel -----
        triviaCardStyle(i) {
            return this.cfStyle(this.triviaActive, this.triviaItems.length, i);
        },
        setTrivia(i) {
            this.triviaActive = (i + this.triviaItems.length) % this.triviaItems.length;
            this.restartTrivia();
        },
        stepTrivia(dir) {
            this.setTrivia(this.triviaActive + dir);
        },
        nextTrivia() {
            this.triviaActive = (this.triviaActive + 1) % this.triviaItems.length;
        },
        pauseTrivia() { clearInterval(this.triviaTimer); },
        resumeTrivia() { this.restartTrivia(); },
        // ----- Pillars carousel -----
        pillarCardStyle(i) {
            return this.cfStyle(this.pillarActive, this.pillarItems.length, i);
        },
        setPillar(i) {
            this.pillarActive = (i + this.pillarItems.length) % this.pillarItems.length;
            this.restartPillar();
        },
        stepPillar(dir) {
            this.setPillar(this.pillarActive + dir);
        },
        nextPillar() {
            this.pillarActive = (this.pillarActive + 1) % this.pillarItems.length;
        },
        restartPillar() {
            clearInterval(this.pillarTimer);
            this.pillarTimer = setInterval(this.nextPillar, 6500);
        },
        pausePillar() { clearInterval(this.pillarTimer); },
        resumePillar() { this.restartPillar(); },
        // ----- Drag / swipe (shared) -----
        onCfDown(e, which) {
            this.cfDrag = { active: true, startX: e.clientX, movedX: 0, which };
            if (which === 'trivia') this.pauseTrivia(); else this.pausePillar();
        },
        onCfMove(e) {
            if (this.cfDrag.active) this.cfDrag.movedX = e.clientX - this.cfDrag.startX;
        },
        onCfUp() {
            if (!this.cfDrag.active) return;
            const { movedX, which } = this.cfDrag;
            this.cfDrag.active = false;
            const dir = movedX <= -60 ? 1 : (movedX >= 60 ? -1 : 0);
            if (which === 'trivia') {
                if (dir) this.stepTrivia(dir); else this.resumeTrivia();
            } else {
                if (dir) this.stepPillar(dir); else this.resumePillar();
            }
        },
        restartTrivia() {
            clearInterval(this.triviaTimer);
            this.triviaTimer = setInterval(this.nextTrivia, 6000);
        },
        /**
         * Arrow-key navigation between manual tabs.
         * ARIA tab lists are expected to move focus with the arrow keys; without
         * this, keyboard users have to Tab through every task to reach the last.
         */
        onGuideKey(e, i) {
            const n = this.guides.length;
            let next = null;
            if (e.key === 'ArrowDown' || e.key === 'ArrowRight') next = (i + 1) % n;
            if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') next = (i - 1 + n) % n;
            if (e.key === 'Home') next = 0;
            if (e.key === 'End') next = n - 1;
            if (next === null) return;
            e.preventDefault();
            this.guideActive = next;
            this.$nextTick(() => {
                document.getElementById(`guide-tab-${this.guides[next].id}`)?.focus();
            });
        },

        scrollTo(id) {
            this.menuOpen = false;
            const el = document.getElementById(id);
            if (!el) return;
            const top = el.getBoundingClientRect().top + window.pageYOffset - 72;
            window.scrollTo({ top: Math.max(top, 0), behavior: 'smooth' });
            if (history.replaceState) history.replaceState(null, '', '#' + id);
        },
        updateCountdown() {
            const diff = this.targetDate - new Date();
            if (diff > 0) {
                this.timeLeft.total = diff;
                this.timeLeft.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                this.timeLeft.hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                this.timeLeft.minutes = Math.floor((diff / (1000 * 60)) % 60);
                this.timeLeft.seconds = Math.floor((diff / 1000) % 60);
            } else {
                this.timeLeft.total = 0;
                clearInterval(this.countdownInterval);
            }
        },
        onScroll() {
            const top = document.body.scrollTop || document.documentElement.scrollTop;
            this.scrolled = top > 80;
        },
        topFunction() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
    },
    mounted() {
        AOS.init({ once: true, duration: 800 });
        this.updateCountdown();
        this.countdownInterval = setInterval(this.updateCountdown, 1000);
        this.restartTrivia();
        window.addEventListener('scroll', this.onScroll);
        this.onScroll();
    },
    beforeUnmount() {
        clearInterval(this.countdownInterval);
        clearInterval(this.triviaTimer);
        clearInterval(this.pillarTimer);
        window.removeEventListener('scroll', this.onScroll);
    },
};
</script>

<style scoped>
/* ---------- Brand typefaces ----------
   Primary: Agrandir (licensed — drop the .woff2 files at these exact paths).
   Supplementary: Roboto (loaded from Google Fonts in app.blade.php).
   @font-face is a global at-rule, so it is not affected by `scoped`. */
@font-face {
    font-family: 'Agrandir';
    src: url('/fonts/agrandir-regular.woff2') format('woff2');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}
@font-face {
    font-family: 'Agrandir';
    src: url('/fonts/agrandir-italic.woff2') format('woff2');
    font-weight: 400;
    font-style: italic;
    font-display: swap;
}
@font-face {
    font-family: 'Agrandir';
    src: url('/fonts/agrandir-bold.woff2') format('woff2');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}
@font-face {
    font-family: 'Agrandir';
    src: url('/fonts/agrandir-bolditalic.woff2') format('woff2');
    font-weight: 700;
    font-style: italic;
    font-display: swap;
}

.rstw {
    --ink: #211215;
    --brand: #E22032;
    --brand-2: #EC864C;
    --accent: #F1A442;
    /* Official RSTW brand palette */
    --c-red: #9C2424;
    --c-red-2: #E22032;
    --c-red-3: #7A141B;
    --c-red-4: #671113;
    --c-blue: #144C8D;
    --c-blue-2: #46A0C1;
    --c-blue-3: #5386BF;
    --c-orange: #EC864C;
    --c-peach: #E6CD9C;
    --c-gold: #F1A442;
    --c-gold-2: #EDA529;
    --cream: #FAF6EC;
    /* Primary = display/headings, Supplementary = body copy */
    --font-primary: 'Agrandir', 'Roboto', system-ui, -apple-system, Segoe UI, sans-serif;
    --font-supplementary: 'Roboto', system-ui, -apple-system, Segoe UI, sans-serif;
    font-family: var(--font-supplementary);
    color: var(--ink);
    /* Page-wide petal texture; `fixed` keeps it viewport-scaled instead of
       stretching the wide image over the full document height. */
    background: #ffffff url('/images/2026/bg.png') center / cover no-repeat fixed;
    overflow-x: hidden;
    position: relative;
}

/* Display type takes the primary face; body copy stays on the supplementary. */
.rstw h1, .rstw h2, .rstw h3, .rstw h4,
.rstw .rstw-hero__title,
.rstw .rstw-hero__eyebrow,
.rstw .rstw-hero__tagline,
.rstw .rstw-hero__theme,
.rstw .rstw-trivia__title,
.rstw .rstw-nav__brand-text,
.rstw .rstw-btn {
    font-family: var(--font-primary);
}

/* ---------- Navbar ---------- */
.rstw-nav {
    position: fixed;
    inset: 0 0 auto 0;
    z-index: 1000;
    transition: background .3s, box-shadow .3s, padding .3s;
    padding: 26px 0;
}
.rstw-nav.is-sticky {
    background: #102340;
    box-shadow: 0 6px 24px rgba(11, 17, 32, .18);
    padding: 22px 0;
}
.rstw-nav__inner {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
/* Desktop: centre the menu in the full nav width by lifting the brand out of
   the flex flow. Scoped to >900px so the mobile toggle layout is untouched. */
@media (min-width: 901px) {
    .rstw-nav__inner { justify-content: center; position: relative; }
    .rstw-nav__brand {
        position: absolute;
        left: 24px;
        top: 50%;
        transform: translateY(-50%);
    }
}
.rstw-nav__brand {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-weight: 800;
    letter-spacing: .5px;
}
.rstw-nav__brand-img { height: 34px; width: auto; display: block; }
.rstw-nav__badge {
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    color: #fff;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 18px;
}
.rstw-nav__brand-text { color: var(--ink); font-size: 20px; transition: color .3s; }
.rstw-nav.is-sticky .rstw-nav__brand-text { color: #fff; }

.rstw-nav__menu {
    display: flex;
    align-items: center;
    gap: 40px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.rstw-nav__menu a {
    text-decoration: none;
    color: rgba(33, 18, 21, .82);
    font-size: 15px;
    font-weight: 500;
    transition: color .25s;
}
.rstw-nav__menu a:hover { color: var(--brand); }
/* On the coloured sticky bar the links must go white to stay legible */
.rstw-nav.is-sticky .rstw-nav__menu a { color: #fff; }
.rstw-nav.is-sticky .rstw-nav__menu a:hover { color: rgba(255, 255, 255, .75); }

.rstw-nav__cta {
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    color: #fff !important;
    padding: 9px 20px;
    border-radius: 999px;
    font-weight: 600 !important;
}
.rstw-nav__cta:hover { color: #fff !important; opacity: .92; }
.rstw-nav__toggle {
    display: none;
    background: none;
    border: 0;
    color: var(--ink);
    font-size: 28px;
    cursor: pointer;
}
.rstw-nav.is-sticky .rstw-nav__toggle { color: #fff; }

/* ---------- Hero ---------- */
.rstw-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: transparent;   /* inherits the page-wide bg.png texture */
    color: var(--ink);
    padding: 74px 24px 28px;
    text-align: center;
    overflow: hidden;
}

/* ---- Animated floating cultural-icon chips ---- */
.rstw-hero__floaters {
    position: absolute;
    inset: 0;
    z-index: 1;              /* behind section content */
    overflow: hidden;
    pointer-events: none;
}
/* Host section for the relocated floaters */
.rstw-section--floaters { position: relative; overflow: hidden; }
.rstw-section--floaters > .rstw-container { position: relative; z-index: 2; }
.rstw-float {
    position: absolute;
    width: clamp(30px, 3.6vw, 50px);
    aspect-ratio: 1;
    border-radius: 50%;
    background-size: cover;
    background-position: center;
    border: 2px solid rgba(255, 255, 255, .85);
    box-shadow: 0 10px 22px rgba(33, 18, 21, .18);
    opacity: .95;
    animation: rstwFloatChip 7s ease-in-out infinite;
    will-change: transform;
}
@keyframes rstwFloatChip {
    0%, 100% { transform: translateY(0) rotate(-4deg); }
    50%      { transform: translateY(-18px) rotate(4deg); }
}

/* Floaters combined into a compact cluster in the hero's left gutter, clear of content */
.rstw-float--1 { top: 30%; left: 5%;  background-image: url('/images/2026/icons/calamansi%20red.png'); animation-duration: 7.5s; animation-delay: -.4s; }
.rstw-float--2 { top: 39%; left: 11%; background-image: url('/images/2026/icons/vinta.png'); animation-duration: 8.2s; animation-delay: -1.6s; }
.rstw-float--3 { top: 49%; left: 4%;  background-image: url('/images/2026/icons/tamban.png'); animation-duration: 6.6s; animation-delay: -2.4s; }
.rstw-float--4 { top: 50%; left: 12%; background-image: url('/images/2026/icons/pagsalabuk%20fountain%20yellow.png'); animation-duration: 7.9s; animation-delay: -.9s; }
.rstw-float--5 { top: 60%; left: 7%;  width: clamp(24px, 2.8vw, 38px); background-image: url('/images/2026/icons/yakan%20pattern.png'); animation-duration: 6.9s; animation-delay: -3.1s; }
.rstw-float--6 { top: 41%; left: 17%; width: clamp(24px, 2.8vw, 38px); background-image: url('/images/2026/icons/unity%20park%20multicolor.png'); animation-duration: 8.6s; animation-delay: -1.1s; }
/* Hide once the gutter is too narrow to keep them off the content */
@media (max-width: 1200px) { .rstw-hero__floaters { display: none; } }

/* ---- Cultural PNG side banners (home section only) ---- */
.rstw-banner {
    position: absolute;       /* bounded to the hero (position: relative, overflow: hidden) */
    top: 0;
    bottom: 0;
    margin: 0;                /* flush to the hero edges, no side margin */
    z-index: 1;               /* behind the hero content (z-index 2) */
    pointer-events: none;
    background-repeat: no-repeat;
}
.rstw-banner--left {
    left: 0;
    width: clamp(18px, 3vw, 46px);
    background-image: url('/images/2026/banner_left.png');
    background-position: center;
    background-size: auto 100%;   /* full hero height */
    /* soften the cut top and bottom ends */
    -webkit-mask-image: linear-gradient(to bottom, transparent 0, #000 10%, #000 88%, transparent 100%);
            mask-image: linear-gradient(to bottom, transparent 0, #000 10%, #000 88%, transparent 100%);
}
/* side.png (2403x2437, ~0.99:1) as three accents down the right edge: top,
   centre and bottom. `contain` keeps the artwork whole in its square box. */
.rstw-banner--right-top,
.rstw-banner--right-mid,
.rstw-banner--right {
    right: 0;
    width: clamp(160px, 24vw, 340px);
    height: clamp(160px, 24vw, 340px);
    background-image: url('/images/2026/side.png');
    background-size: contain;
}
.rstw-banner--right-top {
    top: 0;
    bottom: auto;
    background-position: right bottom;
    transform: scaleY(-1);   /* mirror it so the pair reads symmetrically */
}
.rstw-banner--right-mid {
    top: 50%;
    bottom: auto;
    background-position: right center;
    transform: translateY(-50%);   /* centre the box on the 50% mark */
}
.rstw-banner--right {
    top: auto;
    bottom: 0;
    background-position: right bottom;
}
/* Hide the hero side designs once the viewport is too narrow to keep them
   clear of the centred content (same idea as the floaters above). */
@media (max-width: 900px) {
    .rstw-banner { display: none; }
}

/* ---- RSTW Trivia card ---- */
.rstw-trivia__wrap { position: relative; max-width: 620px; margin: 0 auto; perspective: 1400px; }
/* ---- Shared 3D coverflow ---- */
.rstw-cf { max-width: 940px; margin: 0 auto; }
.rstw-cf__stage {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    perspective: 1800px;
    touch-action: pan-y;
    cursor: grab;
    user-select: none;
}
.rstw-cf__stage:active { cursor: grabbing; }
.rstw-cf__track { position: relative; transform-style: preserve-3d; }
.rstw-cf--trivia .rstw-cf__track { width: min(560px, 88vw); height: clamp(340px, 46vh, 430px); }
.rstw-cf--pillars .rstw-cf__track { width: min(400px, 84vw); height: clamp(300px, 40vh, 360px); }
.rstw-cf__card {
    position: absolute;
    inset: 0;
    cursor: pointer;
    transition: transform .6s cubic-bezier(.22, .61, .36, 1), opacity .5s ease, filter .5s ease;
    transform-style: preserve-3d;
    will-change: transform;
}
.rstw-cf__arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 200;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 0;
    cursor: pointer;
    display: grid;
    place-items: center;
    font-size: 28px;
    color: #fff;
    background: linear-gradient(135deg, var(--c-blue) 0%, var(--c-blue-3) 100%);
    box-shadow: 0 8px 22px rgba(20, 76, 141, .32);
    transition: transform .2s, background .2s;
}
.rstw-cf__arrow:hover { transform: translateY(-50%) scale(1.08); background: linear-gradient(135deg, var(--c-orange), var(--c-red-2)); }
.rstw-cf__arrow--prev { left: max(-6px, -3vw); }
.rstw-cf__arrow--next { right: max(-6px, -3vw); }
.rstw-cf__counter { text-align: center; margin-top: 22px; font-weight: 800; letter-spacing: 3px; color: var(--c-blue); }
.rstw-cf__counter em { font-style: normal; color: rgba(20, 76, 141, .4); }
.rstw-cf__dots { display: flex; justify-content: center; gap: 9px; margin-top: 12px; }
.rstw-cf__dot {
    width: 10px; height: 10px; padding: 0; border: 0; border-radius: 50%; cursor: pointer;
    background: rgba(20, 76, 141, .25); transition: background .25s, transform .25s;
}
.rstw-cf__dot:hover { background: rgba(20, 76, 141, .5); }
.rstw-cf__dot.is-active { background: var(--c-blue); transform: scale(1.25); }
@media (max-width: 560px) {
    .rstw-cf__arrow { width: 40px; height: 40px; font-size: 22px; }
    .rstw-cf__arrow--prev { left: 2px; }
    .rstw-cf__arrow--next { right: 2px; }
}

.rstw-trivia__card {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    border: 3px solid transparent;
    border-radius: 26px;
    padding: 26px 30px 34px;
    background:
        linear-gradient(#fff, #fff) padding-box,
        linear-gradient(130deg, var(--c-blue) 0%, var(--c-blue-2) 45%, var(--c-orange) 100%) border-box;
    box-shadow:
        0 2px 0 rgba(20, 76, 141, .12),
        0 22px 44px rgba(11, 17, 32, .16),
        0 44px 80px rgba(20, 76, 141, .18);
}
.rstw-trivia__header { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 14px; }
.rstw-trivia__pill {
    display: inline-flex; align-items: center; gap: 10px;
    background: linear-gradient(135deg, #0b2a5e, #163f7e);
    color: #fff; font-weight: 800; font-size: clamp(18px, 2.4vw, 24px);
    padding: 11px 20px; border-radius: 14px;
    box-shadow: 0 10px 24px rgba(11, 42, 94, .28);
}
.rstw-trivia__pill i { font-size: 1.15em; }
.rstw-trivia__bulb { flex-shrink: 0; font-size: clamp(40px, 6vw, 58px); line-height: 1; }
.rstw-trivia__bulb i {
    background: conic-gradient(from 90deg, var(--c-blue), var(--c-blue-2), var(--c-gold), var(--c-orange), var(--c-red), var(--c-blue));
    -webkit-background-clip: text; background-clip: text; color: transparent;
    filter: drop-shadow(0 4px 8px rgba(236, 134, 76, .35));
}
.rstw-trivia__title { font-size: clamp(30px, 5vw, 44px); font-weight: 800; line-height: 1.05; margin: 4px 0 14px; }
.rstw-trivia__title .t-blue { color: #12336e; }
.rstw-trivia__title .t-orange { color: var(--c-orange); }
.rstw-trivia__title .t-red { color: var(--c-red-2); }
.rstw-trivia__text { color: #3f4b5b; font-size: clamp(15px, 1.9vw, 17.5px); line-height: 1.6; flex: 1; margin: 0; overflow: auto; }
.rstw-trivia__text b { color: #12336e; font-weight: 800; }
/* Rotating trivia — arrows + dots + fade */
.rstw-trivia__arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 0;
    cursor: pointer;
    display: grid;
    place-items: center;
    font-size: 28px;
    color: #fff;
    background: linear-gradient(135deg, var(--c-blue) 0%, var(--c-blue-3) 100%);
    box-shadow: 0 8px 22px rgba(20, 76, 141, .32);
    transition: transform .2s, background .2s;
}
.rstw-trivia__arrow:hover { transform: translateY(-50%) scale(1.08); background: linear-gradient(135deg, var(--c-orange), var(--c-red-2)); }
.rstw-trivia__arrow--prev { left: -66px; }
.rstw-trivia__arrow--next { right: -66px; }
@media (max-width: 820px) {
    .rstw-trivia__arrow { width: 42px; height: 42px; font-size: 24px; }
    .rstw-trivia__arrow--prev { left: -8px; }
    .rstw-trivia__arrow--next { right: -8px; }
}
@media (max-width: 480px) {
    .rstw-trivia__arrow { display: none; }
}
.rstw-trivia__dots { display: flex; justify-content: center; gap: 9px; margin-top: 20px; }
.rstw-trivia__dot {
    width: 10px; height: 10px; padding: 0; border: 0; border-radius: 50%; cursor: pointer;
    background: rgba(20, 76, 141, .25); transition: background .25s, transform .25s;
}
.rstw-trivia__dot:hover { background: rgba(20, 76, 141, .5); }
.rstw-trivia__dot.is-active { background: var(--c-blue); transform: scale(1.25); }
.rstw-trivia__text { transform-origin: center; backface-visibility: hidden; }
.rstw-trivia-fade-enter-active, .rstw-trivia-fade-leave-active { transition: opacity .45s ease, transform .45s cubic-bezier(.2, .7, .2, 1); }
.rstw-trivia-fade-enter-from { opacity: 0; transform: rotateY(40deg) translateZ(-60px); }
.rstw-trivia-fade-leave-to { opacity: 0; transform: rotateY(-40deg) translateZ(-60px); }
.rstw-trivia__tail {
    position: absolute; left: 64px; bottom: -15px; width: 28px; height: 28px;
    background: #fff; transform: rotate(45deg);
    border-right: 3px solid var(--c-orange); border-bottom: 3px solid var(--c-orange);
    border-bottom-right-radius: 6px;
}
/* Corner leaf/petal decorations */
.rstw-trivia__deco { position: absolute; z-index: 3; width: 0; height: 0; }
.rstw-trivia__deco--tl { top: -12px; left: -10px; }
.rstw-trivia__deco--br { bottom: -14px; right: -6px; }
.rstw-trivia__deco--bl { bottom: -16px; left: 26px; }
.leaf {
    position: absolute; width: 15px; height: 32px; border-radius: 0 100% 0 100%;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .3);
}
.leaf--blue { background: linear-gradient(var(--c-blue), var(--c-blue-2)); }
.leaf--orange { background: linear-gradient(var(--c-gold), var(--c-orange)); }
.leaf--red { background: linear-gradient(var(--c-red-2), var(--c-red)); }
.rstw-trivia__deco--tl .leaf:nth-child(1) { transform: rotate(15deg); top: 0; left: 0; }
.rstw-trivia__deco--tl .leaf:nth-child(2) { transform: rotate(-35deg); top: 4px; left: 12px; }
.rstw-trivia__deco--tl .leaf:nth-child(3) { transform: rotate(60deg); top: 16px; left: -8px; }
.rstw-trivia__deco--br .leaf:nth-child(1) { transform: rotate(200deg); bottom: 0; right: 0; }
.rstw-trivia__deco--br .leaf:nth-child(2) { transform: rotate(150deg); bottom: 4px; right: 12px; }
.rstw-trivia__deco--br .leaf:nth-child(3) { transform: rotate(240deg); bottom: 14px; right: -6px; }
.rstw-trivia__pie {
    position: absolute; width: 22px; height: 22px; border-radius: 50%;
    background: conic-gradient(var(--c-blue) 0 33%, var(--c-orange) 33% 66%, var(--c-red-2) 66% 100%);
    box-shadow: 0 0 0 3px #fff;
}
.rstw-trivia__deco--br .rstw-trivia__pie { bottom: -6px; right: 20px; }
.rstw-trivia__deco--bl .rstw-trivia__pie { bottom: 0; left: 0; }

/* ---- Motif clusters ---- */
.rstw-motifs { position: absolute; z-index: 1; pointer-events: none; }
.rstw-motifs--right { top: 4%; right: -40px; width: 460px; height: 92%; z-index: 0; }
.rstw-motifs--left { bottom: 2%; left: -70px; width: 240px; height: 70%; opacity: .85; }
.rstw-shape { position: absolute; display: block; filter: drop-shadow(0 10px 22px rgba(33, 18, 21, .10)); }

/* Kulintang / electric sun — concentric segmented wheel */
.rstw-shape--sun {
    width: 190px; height: 190px; border-radius: 50%;
    background: conic-gradient(from 12deg,
        var(--c-orange) 0deg 45deg, var(--c-gold) 45deg 90deg,
        var(--c-red-2) 90deg 135deg, var(--c-blue-2) 135deg 180deg,
        var(--c-blue) 180deg 225deg, var(--c-gold) 225deg 270deg,
        var(--c-orange) 270deg 315deg, var(--c-red) 315deg 360deg);
    -webkit-mask: radial-gradient(circle, #000 0 20%, transparent 22% 32%, #000 34% 60%, transparent 62%);
            mask: radial-gradient(circle, #000 0 20%, transparent 22% 32%, #000 34% 60%, transparent 62%);
    animation: rstwSpin 52s linear infinite;
}
@keyframes rstwSpin { to { transform: rotate(360deg); } }

/* T'nalak — woven diamond lattice square */
.rstw-shape--tnalak {
    width: 150px; height: 150px; border-radius: 14px; overflow: hidden;
    background:
        repeating-linear-gradient(45deg, var(--c-blue) 0 4px, transparent 4px 20px),
        repeating-linear-gradient(-45deg, var(--c-red-2) 0 4px, transparent 4px 20px),
        var(--c-peach);
}

/* Lotus / wordmark-style petal pair (two quarter circles) */
.rstw-shape--petal {
    width: 120px; height: 120px;
    background: linear-gradient(135deg, var(--c-orange), var(--c-red-2));
    border-radius: 100% 0 100% 0;
}

/* T'nalak diamond with inset frames */
.rstw-shape--diamond {
    width: 96px; height: 96px; transform: rotate(45deg);
    background: var(--c-red);
    box-shadow: inset 0 0 0 8px var(--cream), inset 0 0 0 18px var(--c-blue-2);
}

/* Tribal triangle */
.rstw-shape--tri {
    width: 0; height: 0;
    border-left: 52px solid transparent;
    border-right: 52px solid transparent;
    border-bottom: 88px solid var(--c-gold);
    filter: drop-shadow(0 8px 16px rgba(33, 18, 21, .12));
}

/* Kulintang concentric rings */
.rstw-shape--rings {
    width: 118px; height: 118px; border-radius: 50%;
    background: radial-gradient(circle,
        var(--c-red-2) 0 24%, var(--cream) 25% 38%,
        var(--c-blue) 39% 58%, var(--cream) 59% 72%,
        var(--c-gold) 73% 92%, transparent 93%);
}

/* Star */
.rstw-shape--star {
    width: 62px; height: 62px; background: var(--c-red-2);
    clip-path: polygon(50% 0, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
}

/* Lightning bolt */
.rstw-shape--bolt {
    width: 54px; height: 74px; background: var(--c-gold);
    clip-path: polygon(58% 0, 20% 52%, 46% 52%, 34% 100%, 82% 40%, 54% 40%, 70% 0);
}

/* Right-cluster placement */
.rstw-motifs--right .rstw-shape--sun    { top: 0;    right: 30px; }
.rstw-motifs--right .rstw-shape--tnalak { top: 150px; right: 200px; }
.rstw-motifs--right .rstw-shape--petal  { top: 210px; right: 40px; transform: rotate(-15deg); }
.rstw-motifs--right .rstw-shape--diamond{ top: 330px; right: 210px; }
.rstw-motifs--right .rstw-shape--tri    { top: 340px; right: 70px; transform: rotate(-8deg); }
.rstw-motifs--right .rstw-shape--rings  { top: 470px; right: 150px; }
.rstw-motifs--right .rstw-shape--star   { top: 120px; right: 0; }
.rstw-motifs--right .rstw-shape--bolt   { top: 470px; right: 30px; }

/* Left-cluster placement */
.rstw-motifs--left .rstw-shape--sun     { bottom: 0;   left: 20px; width: 150px; height: 150px; }
.rstw-motifs--left .rstw-shape--diamond { bottom: 150px; left: 90px; }
.rstw-motifs--left .rstw-shape--petal   { bottom: 180px; left: 0; transform: rotate(120deg); }
.rstw-motifs--left .rstw-shape--tri     { bottom: 30px;  left: 110px; }
.rstw-motifs--left .rstw-shape--star    { bottom: 250px; left: 40px; }

.rstw-hero__content { position: relative; z-index: 2; max-width: 900px; margin: 0 auto; }

/* Organizer / partner logo bar */
.rstw-hero__orgs {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: clamp(18px, 4vw, 44px);
    max-width: 540px;
    margin: 0 auto 20px;
}
.rstw-org-logos {
    width: auto;
    height: auto;
    max-width: min(860px, 96%);
    object-fit: contain;
    display: block;
    margin: 0 auto;
}
.rstw-org { display: flex; align-items: center; gap: 14px; text-align: left; }
.rstw-org__logo { height: 82px; width: auto; flex-shrink: 0; }
.rstw-org__text { display: flex; flex-direction: column; line-height: 1; color: var(--ink); }
.rstw-org__dost { font-size: clamp(1.6rem, 4vw, 2.5rem); font-weight: 900; letter-spacing: -1px; color: var(--c-blue); }
.rstw-org__region { font-size: clamp(.95rem, 2.6vw, 1.55rem); font-weight: 500; letter-spacing: .5px; color: #3a2a20; margin-top: 3px; }
.rstw-org__tag { font-size: clamp(.58rem, 1.4vw, .8rem); font-weight: 700; color: var(--c-orange); margin-top: 5px; }
.rstw-org__logo-img { width: auto; flex-shrink: 0; object-fit: contain; }
.rstw-org__dostlogo { height: clamp(42px, 6vw, 58px); }
.rstw-org__seal { height: clamp(40px, 5.5vw, 54px); }
.rstw-org__asean { height: clamp(44px, 6.2vw, 60px); }

@media (max-width: 640px) {
    .rstw-hero__orgs {
        gap: 18px 22px;
        text-align: center;
    }
}
.rstw-hero__tagline {
    margin: 0 0 10px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.rstw-hero__tagline-fil {
    font-size: clamp(1.15rem, 2.8vw, 1.95rem);
    font-weight: 800;
    color: var(--c-blue);
    letter-spacing: .2px;
}
.rstw-hero__tagline-sub {
    font-size: clamp(.85rem, 1.9vw, 1.12rem);
    font-weight: 500;
    color: #5a4a3d;
}
.rstw-hero__eyebrow {
    display: inline-block;
    padding: 7px 18px;
    border: 1px solid rgba(20, 76, 141, .28);
    border-radius: 999px;
    font-size: 13px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--c-blue);
    background: rgba(255, 255, 255, .7);
    margin-bottom: 10px;
}
.rstw-hero__title {
    font-size: clamp(3.6rem, 12vw, 8.5rem);
    font-weight: 900;
    line-height: 1;
    margin: -2px 0 10px;
    letter-spacing: -2px;
}
.rstw-hero__lockup {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: clamp(12px, 2.4vw, 34px);
    animation: rstwFloat 6s ease-in-out infinite;
}
@keyframes rstwFloat {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-9px); }
}
/* "2026" stacked — banner-style outlined blocks */
.rstw-hero__year {
    display: flex;
    flex-direction: column;
    line-height: .78;
    font-size: .46em;
    font-weight: 900;
    letter-spacing: -1px;
}
.rstw-hero__year span {
    color: #fbe9d8;
    -webkit-text-stroke: 3px var(--c-orange);
}
.rstw-hero__year span:first-child {
    color: #e6eefa;
    -webkit-text-stroke-color: var(--c-blue);
}
/* Full "2026 · RSTW · Zamboanga Peninsula" lockup image (CSS fallback if missing) */
.rstw-hero__wordmark-img {
    display: block;
    margin: 0 auto;
    width: auto;
    height: auto;
    max-width: min(1000px, 90%);
    object-fit: contain;
    filter: drop-shadow(0 12px 26px rgba(33, 18, 21, .14));
}
.rstw-hero__wordmark-fallback {
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
/* "RSTW" — each letter carries a gradient fill from the brand palette */
.rstw-hero__word { display: inline-flex; align-items: center; }
.rstw-hero__letters { display: inline-flex; }
.rstw-hero__letter {
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    filter: drop-shadow(0 10px 22px rgba(33, 18, 21, .18));
}
.rstw-hero__letter--r { background-image: linear-gradient(140deg, var(--c-orange) 0%, var(--c-red-2) 100%); }
.rstw-hero__letter--s { background-image: linear-gradient(140deg, var(--c-red-2) 0%, var(--c-blue) 100%); }
.rstw-hero__letter--t { background-image: linear-gradient(140deg, var(--c-orange) 0%, var(--c-blue-2) 100%); }
.rstw-hero__letter--w { background-image: linear-gradient(140deg, var(--c-blue) 0%, var(--c-blue-2) 100%); }
/* Region band under the wordmark */
.rstw-hero__region {
    display: block;
    font-size: clamp(1rem, 2.6vw, 1.6rem);
    font-weight: 800;
    letter-spacing: clamp(4px, 1.4vw, 12px);
    text-transform: uppercase;
    background: linear-gradient(135deg, var(--c-blue) 0%, var(--c-orange) 55%, var(--c-red-2) 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    margin: 0 0 24px;
    filter: drop-shadow(0 3px 12px rgba(20, 76, 141, .22));
}
/* Official theme phrase */
.rstw-hero__theme {
    max-width: 760px;
    margin: 0 auto 16px;
    line-height: 1.4;
}
.rstw-hero__theme-lead {
    display: block;
    font-size: clamp(1.15rem, 2.6vw, 1.6rem);
    font-weight: 800;
    letter-spacing: -.3px;
}
.rstw-hero__theme-a { color: var(--c-red-2); }
.rstw-hero__theme-b { color: var(--c-blue); }
.rstw-hero__theme-rest {
    display: block;
    font-size: clamp(1rem, 2.2vw, 1.32rem);
    font-weight: 700;
    color: #2c1a12;
    margin-top: 4px;
}
.rstw-hero__meta {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px 26px;
    justify-content: center;
    font-size: 15px;
    font-weight: 500;
    color: #4b3b30;
}
.rstw-hero__meta i { color: var(--c-orange); margin-right: 8px; }

.rstw-count {
    display: flex;
    gap: 14px;
    justify-content: center;
    margin-bottom: 26px;
}
.rstw-count__box {
    background: #ffffff;
    border: 1px solid rgba(20, 76, 141, .14);
    border-radius: 16px;
    padding: 16px 14px;
    min-width: 96px;
    box-shadow: 0 12px 28px rgba(20, 76, 141, .08);
}
.rstw-count__box span { display: block; font-size: clamp(2.6rem, 4vw, 3.2rem); font-weight: 800; line-height: 1; color: var(--c-blue); }
.rstw-count__box small { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #9a8574; }
.rstw-count__live { color: var(--c-orange); font-weight: 700; font-size: 1.4rem; }

/* Partner credits strip — very wide artwork (~8.4:1), so cap the width and
   let height follow the aspect ratio rather than fixing it. */
.rstw-hero__collab {
    display: block;
    width: 100%;
    max-width: 620px;
    height: auto;
    margin: 0 auto 26px;
}

.rstw-hero__actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

/* ---------- Buttons ---------- */
.rstw-btn {
    display: inline-block;
    padding: 14px 32px;
    border-radius: 999px;
    font-weight: 600;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s, opacity .2s;
}
.rstw-btn:hover { transform: translateY(-2px); }
.rstw-btn--primary {
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    color: #fff;
    box-shadow: 0 12px 30px rgba(225, 27, 34, .4);
}
.rstw-btn--ghost { color: var(--c-blue); border: 1px solid rgba(20, 76, 141, .3); background: rgba(255, 255, 255, .6); }
.rstw-btn--ghost:hover { background: #fff; }
.rstw-btn--light { background: #fff; color: var(--brand); box-shadow: 0 12px 30px rgba(0, 0, 0, .18); }

/* ---------- Sections ---------- */
.rstw-section { padding: 96px 24px; }
.rstw-section--muted { background: transparent; }   /* was #f7f2e8; now shows the page bg.png */
.rstw-container { max-width: 1160px; margin: 0 auto; }
.rstw-kicker {
    display: inline-block;
    color: var(--c-blue);
    font-weight: 700;
    font-size: 12.5px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    margin-bottom: 14px;
    /* pill treatment so the kicker reads as a badge, not stray small caps */
    padding: 7px 16px;
    border-radius: 999px;
    background: linear-gradient(135deg, rgba(20, 76, 141, .10), rgba(70, 160, 193, .12));
    border: 1px solid rgba(20, 76, 141, .16);
}
.rstw-heading { text-align: center; max-width: 620px; margin: 0 auto 56px; }
.rstw-heading h2, .rstw-about__text h2 { font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 800; margin: 0 0 16px; letter-spacing: -.5px; }

.rstw-about { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: center; }
.rstw-about__text p { color: #4b5563; line-height: 1.8; margin-bottom: 16px; }
.rstw-about__focus { list-style: none; padding: 0; margin: 0 0 20px; display: grid; gap: 14px; }
.rstw-about__focus li { display: flex; align-items: flex-start; gap: 14px; }
.rstw-about__focus i {
    flex-shrink: 0;
    display: grid; place-items: center;
    width: 44px; height: 44px;
    border-radius: 12px;
    font-size: 22px;
    color: var(--c-blue);
    background: linear-gradient(135deg, rgba(20, 76, 141, .12), rgba(236, 134, 76, .14));
}
.rstw-about__focus strong { display: block; color: #111827; font-size: 1.02rem; margin-bottom: 2px; }
.rstw-about__focus span { color: #6b7280; font-size: .92rem; line-height: 1.5; }
.rstw-frame {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 30px 60px rgba(11, 17, 32, .18);
    aspect-ratio: 16 / 9;
    border: 1px solid rgba(11, 17, 32, .06);
}
.rstw-frame iframe { width: 100%; height: 100%; display: block; }

/* ---------- Pillars ---------- */
.rstw-pillars { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
.rstw-card {
    position: relative;
    overflow: hidden;
    background: #fff;
    border: 1px solid rgba(11, 17, 32, .06);
    border-radius: 20px;
    padding: 38px 30px;
    transition: transform .25s, box-shadow .25s;
}
/* Pillar card filling its coverflow slot */
.rstw-cf--pillars .rstw-card {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    box-shadow: 0 22px 44px rgba(11, 17, 32, .14), 0 44px 80px rgba(20, 76, 141, .16);
}
.rstw-cf--pillars .rstw-card__icon { margin-left: auto; margin-right: auto; }
.rstw-cf__card .rstw-card:hover { transform: none; }
.rstw-card::before {
    content: "";
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 5px;
    background: linear-gradient(90deg, var(--c-orange), var(--c-red-2), var(--c-blue), var(--c-blue-2));
}
.rstw-card:hover { transform: translateY(-6px); box-shadow: 0 24px 50px rgba(11, 17, 32, .12); }
.rstw-card__icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    font-size: 30px;
    color: #fff;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    margin-bottom: 22px;
}
.rstw-pillars .rstw-card:nth-child(1) .rstw-card__icon { background: linear-gradient(135deg, var(--c-orange), var(--c-red-2)); }
.rstw-pillars .rstw-card:nth-child(2) .rstw-card__icon { background: linear-gradient(135deg, var(--c-blue), var(--c-blue-2)); }
.rstw-pillars .rstw-card:nth-child(3) .rstw-card__icon { background: linear-gradient(135deg, var(--c-red-2), var(--c-blue)); }
.rstw-pillars .rstw-card:nth-child(4) .rstw-card__icon { background: linear-gradient(135deg, var(--c-gold), var(--c-orange)); }
.rstw-card h3 { font-size: 1.4rem; font-weight: 700; margin: 0 0 10px; }
.rstw-card p { color: #4b5563; line-height: 1.7; margin: 0; }

/* ---------- Download App ---------- */
.rstw-app { display: grid; grid-template-columns: 1.1fr .9fr; gap: 56px; align-items: center; }
.rstw-app__text p { color: #4b5563; line-height: 1.8; margin-bottom: 22px; max-width: 520px; }
.rstw-app__features { list-style: none; padding: 0; margin: 0 0 30px; display: grid; gap: 14px; }
.rstw-app__features li { display: flex; align-items: center; gap: 12px; font-weight: 500; color: var(--ink); }
.rstw-app__features i {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    border-radius: 12px;
    font-size: 20px;
    color: var(--c-blue);
    background: rgba(20, 76, 141, .1);
}
.rstw-app__download { margin-top: 28px; }
.rstw-app__stores { display: flex; gap: 14px; flex-wrap: wrap; }
.rstw-store {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 12px 22px;
    border-radius: 14px;
    background: var(--ink);
    color: #fff;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
}
.rstw-store:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgba(33, 18, 21, .28); }
.rstw-store i { font-size: 30px; }
.rstw-store span { display: flex; flex-direction: column; line-height: 1.15; font-weight: 700; font-size: 17px; }
.rstw-store small { font-size: 10px; font-weight: 500; letter-spacing: .5px; text-transform: uppercase; opacity: .75; }

.rstw-app__visual { display: flex; flex-direction: column; align-items: center; justify-content: center; }
.rstw-phone {
    position: relative;
    width: 260px;
    height: 520px;
    border-radius: 42px;
    padding: 14px;
    background: linear-gradient(160deg, #143a6b, #0a1c33);
    box-shadow: 0 40px 80px rgba(10, 28, 51, .38), inset 0 0 0 2px rgba(255, 255, 255, .05);
}
.rstw-phone__notch {
    position: absolute;
    top: 14px;
    left: 50%;
    transform: translateX(-50%);
    width: 120px;
    height: 24px;
    background: #0a1c33;
    border-radius: 0 0 16px 16px;
    z-index: 3;
}
.rstw-phone__screen {
    width: 100%;
    height: 100%;
    border-radius: 30px;
    background: radial-gradient(120% 120% at 50% 0%, #1f5596 0%, #0e2a50 60%, #071a33 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    overflow: hidden;
    position: relative;
}
.rstw-phone__screen::after {
    content: "";
    position: absolute;
    width: 240px;
    height: 240px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(236, 134, 76, .38), transparent 70%);
    top: -40px;
}
.rstw-phone__badge {
    position: relative;
    z-index: 2;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    color: #fff;
    font-weight: 800;
    font-size: 30px;
    letter-spacing: 1px;
    padding: 8px 18px;
    border-radius: 14px;
}
.rstw-phone__year { position: relative; z-index: 2; font-size: 46px; font-weight: 800; color: var(--accent); line-height: 1; }
.rstw-phone__tag { position: relative; z-index: 2; font-size: 13px; letter-spacing: 1px; color: rgba(255, 255, 255, .7); text-transform: uppercase; }


/* ---------- App user manual ---------- */
.rstw-guide { margin-top: 72px; }
.rstw-guide__head { text-align: center; max-width: 560px; margin: 0 auto 32px; }
.rstw-guide__head h3 {
    margin: 0 0 8px;
    font-size: clamp(1.4rem, 2.6vw, 1.9rem);
    font-weight: 800;
    letter-spacing: -.3px;
}
.rstw-guide__head p { margin: 0; color: #6b7280; font-size: 14px; }

/* Opens the downloadable book edition */
.rstw-guide__book {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-top: 18px;
    padding: 11px 14px 11px 18px;
    border-radius: 999px;
    cursor: pointer;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--ink);
    background: #fff;
    border: 1px solid rgba(11, 17, 32, .12);
    box-shadow: 0 6px 18px rgba(11, 17, 32, .07);
    transition: transform .25s, box-shadow .25s, border-color .25s;
}
.rstw-guide__book:hover {
    transform: translateY(-2px);
    border-color: rgba(226, 32, 50, .35);
    box-shadow: 0 12px 26px rgba(11, 17, 32, .12);
}
.rstw-guide__book i { font-size: 18px; color: var(--brand); }
.rstw-guide__book em {
    font-style: normal;
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: .1em;
    padding: 4px 9px;
    border-radius: 999px;
    color: #fff;
    background: linear-gradient(135deg, var(--c-blue), var(--c-blue-2));
}

/* Task list beside the steps; collapses to stacked on narrow screens */
.rstw-guide__body {
    display: grid;
    grid-template-columns: minmax(260px, 340px) 1fr;
    gap: 28px;
    align-items: start;
}
.rstw-guide__tabs { display: grid; gap: 10px; }

.rstw-guide__tab {
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100%;
    padding: 14px 16px;
    text-align: left;
    cursor: pointer;
    border-radius: 16px;
    background: rgba(255, 255, 255, .75);
    border: 1px solid rgba(11, 17, 32, .09);
    transition: background .25s, border-color .25s, transform .25s, box-shadow .25s;
}
.rstw-guide__tab:hover { transform: translateX(3px); background: #fff; }
.rstw-guide__tab.is-active {
    background: #fff;
    border-color: rgba(20, 76, 141, .35);
    box-shadow: 0 10px 26px rgba(11, 17, 32, .10);
}
.rstw-guide__tab-icon {
    flex-shrink: 0;
    display: grid;
    place-items: center;
    width: 40px; height: 40px;
    border-radius: 12px;
    font-size: 19px;
    color: #fff;
    background: linear-gradient(135deg, var(--c-blue), var(--c-blue-2));
    box-shadow: 0 6px 14px rgba(20, 76, 141, .28);
    transition: background .25s;
}
.rstw-guide__tab.is-active .rstw-guide__tab-icon {
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    box-shadow: 0 6px 14px rgba(226, 32, 50, .3);
}
.rstw-guide__tab-text { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
.rstw-guide__tab-text strong { font-size: 14px; font-weight: 700; color: var(--ink); line-height: 1.35; }
.rstw-guide__tab-text small { font-size: 11.5px; color: #8a8f98; }
.rstw-guide__tab-chev { margin-left: auto; font-size: 20px; color: #c2c7d0; transition: color .25s, transform .25s; }
.rstw-guide__tab.is-active .rstw-guide__tab-chev { color: var(--brand); transform: translateX(3px); }

/* Step panel */
.rstw-guide__panel {
    padding: 30px 30px 26px;
    border-radius: 20px;
    background: #fff;
    border: 1px solid rgba(11, 17, 32, .08);
    box-shadow: 0 20px 44px rgba(11, 17, 32, .07);
}
.rstw-guide__panel:focus-visible { outline: 2px solid var(--c-blue); outline-offset: 3px; }

.rstw-guide__steps { list-style: none; margin: 0; padding: 0; display: grid; gap: 4px; }
.rstw-guide__step {
    position: relative;
    display: flex;
    gap: 16px;
    padding: 0 0 22px;
}
/* Connector line between step numbers */
.rstw-guide__step::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 34px;
    bottom: 0;
    width: 2px;
    background: linear-gradient(rgba(20, 76, 141, .22), rgba(20, 76, 141, .04));
}
.rstw-guide__step:last-child { padding-bottom: 0; }
.rstw-guide__step:last-child::before { display: none; }

.rstw-guide__num {
    flex-shrink: 0;
    display: grid;
    place-items: center;
    width: 32px; height: 32px;
    border-radius: 50%;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, var(--c-blue), var(--c-blue-3));
    box-shadow: 0 4px 10px rgba(20, 76, 141, .25);
}
.rstw-guide__step-body { display: flex; flex-direction: column; gap: 4px; padding-top: 4px; }
.rstw-guide__step-body strong { font-size: 14.5px; font-weight: 700; color: var(--ink); }
.rstw-guide__step-body span { font-size: 13.5px; line-height: 1.65; color: #5b6472; }

.rstw-guide__tip {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin: 24px 0 0;
    padding: 13px 16px;
    border-radius: 14px;
    font-size: 13px;
    line-height: 1.6;
    color: #6b5b4d;
    background: linear-gradient(135deg, rgba(255, 207, 46, .16), rgba(246, 179, 10, .07));
    border: 1px solid rgba(246, 179, 10, .35);
}
.rstw-guide__tip i { font-size: 17px; color: var(--c-gold-2); flex-shrink: 0; }
.rstw-guide__tip strong { color: var(--ink); }

@media (max-width: 900px) {
    .rstw-guide__body { grid-template-columns: 1fr; gap: 20px; }
    .rstw-guide__tab-chev { display: none; }
    .rstw-guide__panel { padding: 24px 20px 22px; }
}

/* ---------- CTA ---------- */
.rstw-cta { padding: 24px; }
.rstw-cta__inner {
    position: relative;
    background: radial-gradient(120% 160% at 0% 0%, #1a4c86, #0b1f3a 62%);
    border: 1px solid rgba(255, 255, 255, .08);
    border-radius: 28px;
    padding: 58px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    color: #fff;
    flex-wrap: wrap;
    overflow: hidden;
    box-shadow: 0 30px 70px rgba(11, 17, 32, .35);
}
.rstw-cta__glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; }
.rstw-cta__glow--1 { width: 320px; height: 320px; background: rgba(226, 32, 50, .5); top: -140px; left: -60px; }
.rstw-cta__glow--2 { width: 300px; height: 300px; background: rgba(236, 134, 76, .48); bottom: -160px; right: -40px; }
.rstw-cta__pattern {
    position: absolute;
    inset: 0;
    background-image:
        repeating-linear-gradient(45deg, rgba(255, 255, 255, .05) 0 2px, transparent 2px 24px),
        repeating-linear-gradient(-45deg, rgba(255, 255, 255, .05) 0 2px, transparent 2px 24px);
    mask-image: radial-gradient(95% 130% at 100% 0%, #000, transparent 72%);
    -webkit-mask-image: radial-gradient(95% 130% at 100% 0%, #000, transparent 72%);
    pointer-events: none;
}

.rstw-cta__text { position: relative; z-index: 2; }
.rstw-cta__badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    color: var(--accent);
    background: rgba(255, 210, 63, .12);
    border: 1px solid rgba(255, 210, 63, .3);
    padding: 6px 14px;
    border-radius: 999px;
    margin-bottom: 16px;
}
.rstw-cta__text h2 { font-size: clamp(1.7rem, 3.6vw, 2.5rem); font-weight: 800; margin: 0 0 10px; letter-spacing: -.5px; color: #ffffff; }
.rstw-cta__text p { margin: 0 0 18px; color: rgba(255, 255, 255, .78); max-width: 520px; line-height: 1.6; }
.rstw-cta__meta { list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 12px 22px; }
.rstw-cta__meta li { font-size: 14px; color: rgba(255, 255, 255, .9); }
.rstw-cta__meta i { color: var(--accent); margin-right: 7px; }

.rstw-cta__action { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 10px; }
.rstw-cta__btn { font-size: 16px; }
.rstw-cta__btn i { transition: transform .2s; }
.rstw-cta__btn:hover i { transform: translateX(4px); }
.rstw-cta__note { font-size: 12px; color: rgba(255, 255, 255, .55); }

/* ---------- Footer ---------- */
.rstw-footer { position: relative; z-index: 50; background: #0b1f3a; color: rgba(255, 255, 255, .72); padding: 72px 24px 28px; }
.rstw-footer__grid { display: grid; grid-template-columns: 1.4fr 1fr; gap: 40px; }
.rstw-footer__brand img { margin-bottom: 18px; }
.rstw-footer__brand p { margin: 0 0 8px; }
.rstw-footer__logo { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.rstw-footer__logo-img { height: 44px; width: auto; display: block; }
.rstw-footer__logo-badge {
    font-weight: 800; font-size: 1.5rem; color: #fff;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    padding: 4px 12px; border-radius: 10px; letter-spacing: .5px;
}
.rstw-footer__logo-year {
    font-weight: 800; font-size: 1.5rem;
    background: linear-gradient(135deg, var(--accent), var(--brand-2));
    -webkit-background-clip: text; background-clip: text;
    -webkit-text-fill-color: transparent; color: transparent;
}
.rstw-footer__region {
    text-transform: uppercase; letter-spacing: 3px; font-size: 13px;
    font-weight: 700; color: var(--accent) !important; margin: 0 0 14px !important;
}
.rstw-footer__tag { font-size: 13px; color: rgba(255, 255, 255, .5); max-width: 440px; font-style: italic; }
.rstw-footer__contact h4 { color: #fff; margin: 0 0 18px; font-size: 1.1rem; }
.rstw-footer__contact ul { list-style: none; padding: 0; margin: 0; }
.rstw-footer__contact li { margin-bottom: 10px; font-size: 14px; }
/* Partnership strip. The artwork is dark-blue ink on transparency, so it needs
   a light backing to stay legible against the dark footer. */
.rstw-footer__collab {
    max-width: 1160px;
    margin: 44px auto 0;
    padding: 14px 22px;
    background: #ffffff;
    border-radius: 14px;
}
.rstw-footer__collab-img {
    display: block;
    width: 100%;
    max-width: 880px;
    height: auto;
    margin: 0 auto;
}

.rstw-footer__bar {
    max-width: 1160px;
    margin: 48px auto 0;
    padding-top: 24px;
    border-top: 1px solid rgba(255, 255, 255, .1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    flex-wrap: wrap;
    gap: 14px;
}
.rstw-footer__social { list-style: none; display: flex; gap: 10px; padding: 0; margin: 0; }
.rstw-footer__social a {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background: rgba(255, 255, 255, .08);
    color: #fff;
    transition: background .25s;
}
.rstw-footer__social a:hover { background: var(--brand); }

/* ---------- Back to top ---------- */
.rstw-top {
    position: fixed;
    right: 24px;
    bottom: 24px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 0;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    pointer-events: none;
    transform: translateY(12px);
    transition: opacity .3s, transform .3s;
    z-index: 999;
    box-shadow: 0 10px 24px rgba(225, 27, 34, .4);
}
.rstw-top.is-visible { opacity: 1; pointer-events: auto; transform: translateY(0); }

/* Floating always-visible countdown */
.rstw-countbar {
    position: fixed;
    left: 24px;
    bottom: 24px;
    display: inline-flex;
    align-items: center;
    gap: 14px;
    padding: 14px 24px;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--c-red-2) 0%, var(--c-orange) 55%, var(--c-gold) 100%);
    color: #fff;
    text-decoration: none;
    box-shadow: 0 14px 32px rgba(225, 32, 50, .35);
    opacity: 0;
    pointer-events: none;
    transform: translateY(12px);
    transition: opacity .3s, transform .3s;
    z-index: 998;
}
.rstw-countbar.is-visible { opacity: 1; pointer-events: auto; transform: translateY(0); }
.rstw-countbar:hover { transform: translateY(-2px); }
.rstw-countbar__label { display: inline-flex; align-items: center; gap: 7px; font-size: 15px; font-weight: 700; opacity: .95; }
.rstw-countbar__label i { color: #fff; font-size: 19px; }
.rstw-countbar__unit { font-size: 22px; font-weight: 800; font-variant-numeric: tabular-nums; }
.rstw-countbar__unit small { font-size: 12px; font-weight: 600; opacity: .72; margin-left: 2px; }
@media (max-width: 560px) {
    .rstw-countbar { left: 12px; bottom: 12px; padding: 11px 16px; gap: 9px; }
    .rstw-countbar__label { display: none; }
    .rstw-countbar__unit { font-size: 18px; }
}

/* ---------- Responsive ---------- */

/* Tablet / small laptop */
@media (max-width: 1024px) {
    .rstw-section { padding: 76px 22px; }
    .rstw-hero { padding: 78px 22px 40px; min-height: auto; align-items: flex-start; }
    .rstw-about { gap: 40px; }
    .rstw-app { gap: 40px; }
    .rstw-container { max-width: 900px; }
    /* four pillars are too tight at this width — drop to a 2×2 grid */
    .rstw-pillars { grid-template-columns: repeat(2, 1fr); }
    .rstw-motifs--right { transform: scale(.8); transform-origin: top right; opacity: .8; }
    .rstw-motifs--left { transform: scale(.7); transform-origin: bottom left; opacity: .55; }
}


@media (max-width: 860px) {
    .rstw-about { grid-template-columns: 1fr; }
    .rstw-pillars { grid-template-columns: 1fr; }
    .rstw-footer__grid { grid-template-columns: 1fr; }
    .rstw-app { grid-template-columns: 1fr; gap: 40px; }
    .rstw-app__visual { order: -1; }

    .rstw-nav__toggle { display: block; }
    .rstw-nav__menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        flex-direction: column;
        gap: 0;
        background: #fff;
        /* padding and shadow must live on .is-open: max-height clamps the
           content box only, so a closed menu would still show 16px of white
           plus a drop shadow over the hero. */
        box-shadow: none;
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height .3s ease, padding .3s ease;
    }
    .rstw-nav__menu.is-open {
        max-height: 420px;
        padding: 8px 0;
        box-shadow: 0 20px 40px rgba(11, 17, 32, .12);
    }
    .rstw-nav__menu li { width: 100%; }
    .rstw-nav__menu a { display: block; padding: 14px 24px; color: var(--ink) !important; }
    .rstw-nav__cta { margin: 8px 24px; text-align: center; }
}

@media (max-width: 640px) {
    .rstw-section { padding: 60px 18px; }
    /* Extra top padding clears the fixed navbar so the DOST/ASEAN logos
       don't tuck under it. */
    .rstw-hero { padding: 96px 18px 36px; min-height: auto; align-items: flex-start; }
    .rstw-hero__orgs { margin-bottom: 16px; }

    /* Hero typography tuned for phones: larger theme text with looser line
       spacing so the two-line phrases read comfortably instead of cramped. */
    .rstw-hero__theme { margin-bottom: 20px; line-height: 1.5; }
    .rstw-hero__theme-lead { font-size: 1.28rem; line-height: 1.35; }
    .rstw-hero__theme-rest { font-size: 1.06rem; line-height: 1.45; margin-top: 8px; }
    .rstw-hero__meta {
        font-size: 14.5px;
        line-height: 1.25;
        gap: 6px 20px;
        margin-bottom: 24px;
    }
    .rstw-hero__meta li { justify-content: center; line-height: 1.25; }
    /* long uppercase string — shrink it so it stops eating two lines */
    .rstw-hero__eyebrow { font-size: 11px; letter-spacing: 1px; padding: 6px 14px; line-height: 1.4; }
    /* Wide collaboration strip becomes an unreadable sliver on phones — hide it */
    .rstw-hero__collab { display: none; }
    /* Filipino tagline — hidden on phones to tighten the hero */
    .rstw-hero__tagline { display: none; }
    .rstw-app__stores { flex-direction: column; }
    .rstw-store { width: 100%; justify-content: center; }
    /* .rstw-btn is inline-block, so justify-content alone was a no-op —
       make it a real flex box so the icon + label centre together. */
    .rstw-app__download {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
}

@media (max-width: 520px) {
    /* keep all four units on a single row: let the boxes shrink and share
       the width instead of holding a min-width and wrapping */
    .rstw-count { gap: 8px; flex-wrap: nowrap; }
    .rstw-count__box { min-width: 0; flex: 1; padding: 12px 4px; }
    .rstw-count__box span { font-size: 1.6rem; }
    .rstw-count__box small { font-size: 10px; letter-spacing: .3px; }
    .rstw-cta__inner { padding: 36px 26px; }
    .rstw-cta__action { align-items: stretch; width: 100%; }
    .rstw-cta__btn { justify-content: center; }
    .rstw-cta__note { text-align: center; }
    .rstw-hero__actions { flex-direction: column; align-items: stretch; }
    .rstw-hero__actions .rstw-btn { justify-content: center; }
}

/* Small phones — keep the big 3D wordmark from overflowing */
@media (max-width: 400px) {
    .rstw-hero__title { font-size: clamp(2.6rem, 15vw, 3.4rem); }
    .rstw-hero__eyebrow { font-size: 10px; letter-spacing: .6px; padding: 5px 12px; }
    .rstw-count { gap: 6px; }
    .rstw-count__box { padding: 10px 2px; }
    .rstw-count__box span { font-size: 1.35rem; }
    .rstw-count__box small { font-size: 9px; letter-spacing: 0; }
    .rstw-org__logo { height: 54px; }
    .rstw-org__seal { height: 60px; }
    .rstw-org__dost { font-size: 1.5rem; }
    .rstw-org__region { font-size: 1rem; }
}
</style>
