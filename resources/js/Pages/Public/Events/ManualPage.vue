<template>
    <!-- ── Hero panel: faces the title page on the left board ── -->
    <div v-if="page.kind === 'hero'" class="mp mp--hero">
        <img
            src="/images/2026/header_icons.png"
            alt="DOST Zamboanga Peninsula — OneDOST4U · Bagong Pilipinas · ASEAN Philippines 2026"
            class="hero__orgs"
            onerror="this.style.display='none'"
        >

        <p class="hero__tagline">
            <span class="hero__tagline-fil">Siyensya, Teknolohiya, at Inobasyon</span>
            <span class="hero__tagline-sub">Kabalikat sa Matatag, Maginhawa, at Panatag na Kinabukasan</span>
        </p>

        <span class="hero__eyebrow">{{ meta.event }}</span>

        <img
            src="/images/2026/2026RSTW.png"
            alt="2026 RSTW — Zamboanga Peninsula"
            class="hero__wordmark"
            onerror="this.style.display='none'"
        >

        <p class="hero__theme">
            <span class="hero__theme-lead">
                <b class="t-red">Science,</b> <b class="t-blue">Technology,</b> &amp; <b class="t-red">Digital Innovation</b>
            </span>
            <span class="hero__theme-rest">Driving Food Security, Sustainable Energy, &amp; National Resilience</span>
        </p>

        <ul class="hero__meta">
            <li><i class="ri-map-pin-fill"></i> {{ meta.venue }}</li>
            <li><i class="ri-calendar-fill"></i> {{ meta.dates }}</li>
        </ul>

        <!-- Organiser contacts fill the panel's lower half -->
        <div class="hero__contacts">
            <span class="hero__contacts-head">Contact Details</span>
            <ul>
                <li v-for="c in contacts" :key="c.label">
                    <i :class="c.icon"></i>
                    <span>{{ c.value }}</span>
                </li>
            </ul>
        </div>

        <img
            src="/images/2026/collaboration.png"
            alt="In collaboration with Province of Zamboanga del Norte and DOST-TAPI"
            class="hero__collab"
            onerror="this.style.display='none'"
        >
    </div>

    <!-- ── Cover ── -->
    <div v-else-if="page.kind === 'cover'" class="mp mp--cover">
        <div class="cover__rule" aria-hidden="true"></div>
        <img src="/images/2026/2026RSTW.png" alt="" class="cover__logo">
        <p class="cover__event">{{ meta.event }}</p>
        <h1 id="man-title" class="cover__title">{{ meta.title }}</h1>
        <p class="cover__subtitle">{{ meta.subtitle }}</p>

        <dl class="cover__meta">
            <div><dt>Dates</dt><dd>{{ meta.dates }}</dd></div>
            <div><dt>Venue</dt><dd>{{ meta.venue }}</dd></div>
            <div><dt>Published by</dt><dd>{{ meta.publisher }}</dd></div>
        </dl>

        <p class="cover__edition">{{ meta.edition }}</p>
    </div>

    <!-- ── Contents ── -->
    <div v-else-if="page.kind === 'toc'" class="mp">
        <header class="mp__head">
            <span class="mp__eyebrow">Contents</span>
            <h2 class="mp__title">Your three days, sorted</h2>
            <p class="mp__lede">
                Five short guides — from signing up to cashing in your points.
            </p>
        </header>

        <ol class="toc">
            <li v-for="(g, i) in guides" :key="g.id" class="toc__row">
                <span class="toc__num">{{ pad(i + 1) }}</span>
                <span class="toc__label">{{ g.title }}</span>
                <span class="toc__dots" aria-hidden="true"></span>
            </li>
            <li class="toc__row">
                <span class="toc__num">{{ pad(guides.length + 1) }}</span>
                <span class="toc__label">Frequently asked questions</span>
                <span class="toc__dots" aria-hidden="true"></span>
            </li>
            <li class="toc__row">
                <span class="toc__num">{{ pad(guides.length + 2) }}</span>
                <span class="toc__label">Troubleshooting</span>
                <span class="toc__dots" aria-hidden="true"></span>
            </li>
        </ol>

        <div class="callout">
            <strong>Before you start</strong>
            <p>
                Download the RSTW 2026 app before you travel — it is available
                on the App Store for iPhone and on Google Play for Android.
                You will need it throughout the event for check-in, sessions,
                surveys, and points.
            </p>
        </div>
    </div>

    <!-- ── Chapter ── -->
    <div v-else-if="page.kind === 'chapter'" class="mp">
        <header class="mp__head">
            <span class="mp__eyebrow">Chapter {{ pad(page.index + 1) }}</span>
            <h2 class="mp__title">{{ guide.title }}</h2>
            <p class="mp__lede">{{ guide.intro }}</p>
            <p class="mp__time"><i class="ri-time-line"></i> Takes {{ guide.time }}</p>
        </header>

        <ol class="steps">
            <li v-for="(s, n) in guide.steps" :key="n" class="steps__item">
                <span class="steps__num">{{ n + 1 }}</span>
                <div class="steps__body">
                    <strong>{{ s.title }}</strong>
                    <p>{{ s.text }}</p>
                </div>
            </li>
        </ol>

        <p v-if="guide.tip" class="tip">
            <strong>Tip</strong><span>{{ guide.tip }}</span>
        </p>
    </div>

    <!-- ── Frequently asked questions ── -->
    <div v-else-if="page.kind === 'questions'" class="mp">
        <header class="mp__head">
            <span class="mp__eyebrow">Chapter {{ pad(guides.length + 1) }}</span>
            <h2 class="mp__title">Frequently asked questions</h2>
            <p class="mp__lede">The things attendees ask us most often.</p>
        </header>

        <dl class="faq">
            <div v-for="(f, i) in questions" :key="i" class="faq__row">
                <dt>{{ f.q }}</dt>
                <dd>{{ f.a }}</dd>
            </div>
        </dl>
    </div>

    <!-- ── Troubleshooting ── -->
    <div v-else-if="page.kind === 'faq'" class="mp">
        <header class="mp__head">
            <span class="mp__eyebrow">Chapter {{ pad(guides.length + 2) }}</span>
            <h2 class="mp__title">Troubleshooting</h2>
            <p class="mp__lede">Common problems and what to do about them.</p>
        </header>

        <dl class="faq">
            <div v-for="(f, i) in faqs" :key="i" class="faq__row">
                <dt>{{ f.q }}</dt>
                <dd>{{ f.a }}</dd>
            </div>
        </dl>
    </div>

    <!-- ── Closing page ── -->
    <div v-else-if="page.kind === 'end'" class="mp mp--end">
        <img src="/images/2026/2026RSTW.png" alt="" class="end__logo">
        <h2 class="end__title">Still stuck?</h2>
        <p class="end__copy">
            Visit the registration desk at the venue, or contact
            {{ meta.publisher }}.
        </p>
        <p class="end__contact">{{ meta.contact }}</p>
        <p class="end__foot">{{ meta.dates }} · {{ meta.venue }}</p>
    </div>
</template>

<script>
import { CONTACTS, QUESTIONS } from './appManual.js';

export default {
    name: 'ManualPage',
    props: {
        page: { type: Object, required: true },
        meta: { type: Object, required: true },
        guides: { type: Array, required: true },
        faqs: { type: Array, required: true },
    },
    data() {
        return { contacts: CONTACTS, questions: QUESTIONS };
    },
    computed: {
        /** Resolved only for chapter pages. */
        guide() { return this.guides[this.page.index] || {}; },
    },
    methods: {
        pad(n) { return String(n).padStart(2, '0'); },
    },
};
</script>

<style scoped>
/* ═══════════════════════════════════════════════════════════════════════
 * Page furniture.
 *
 * Everything here is sized in `em`, relative to the font-size set on .sheet
 * (see AppManualModal). That root scales with the page height, so a chapter
 * occupies the same proportion of the page on a 13" laptop, a 4K monitor, or
 * a phone — no page ever needs to scroll, and nothing looks undersized on a
 * large display. Colour tokens are inherited from .man-modal.
 * ═══════════════════════════════════════════════════════════════════════ */
.mp {
    position: relative;
    min-height: 100%;
    padding: 2.3em 2.45em 2.6em;
    /* transparent so the leaf's watermarked paper shows through */
    background: transparent;
}

/* ── Cover ── */
.mp--cover {
    display: flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
    padding-top: 4em;
    background: radial-gradient(120% 70% at 50% 0%, rgba(20, 76, 141, .06), transparent 60%);
}
.cover__rule {
    position: absolute; top: 0; left: 0; right: 0; height: .55em;
    background: linear-gradient(90deg, var(--c-blue), var(--c-blue-2), var(--accent), var(--brand-2), var(--brand));
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
}
.cover__logo { width: 10.8em; max-width: 55%; height: auto; margin: 0 auto 1.85em; display: block; }
.cover__event { margin: 0 0 .78em; font-size: .73em; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; color: var(--c-blue); }
.cover__title { margin: 0 0 .16em; font-size: 2.28em; font-weight: 800; letter-spacing: -.04em; line-height: 1.12; }
.cover__subtitle { margin: 0 0 1.7em; font-size: 1.17em; font-weight: 600; color: var(--brand); }
.cover__meta {
    display: grid; gap: .7em; margin: 0 auto 1.7em; max-width: 23em;
    text-align: left; padding: 1.15em 1.38em; border-radius: .77em;
    background: rgba(248, 250, 252, .85); border: 1px solid rgba(11, 17, 32, .07);
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
}
.cover__meta > div { display: grid; grid-template-columns: 6em 1fr; gap: .77em; align-items: baseline; }
.cover__meta dt { font-size: .7em; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--muted); }
.cover__meta dd { margin: 0; font-size: .88em; font-weight: 600; line-height: 1.4; }
.cover__edition { margin: 0; font-size: .81em; color: var(--muted); }

/* ── Head ── */
.mp__head { margin-bottom: 1.3em; padding-bottom: .92em; border-bottom: 1px solid rgba(11, 17, 32, .1); }
.mp__eyebrow { display: block; margin-bottom: .6em; font-size: .7em; font-weight: 800; letter-spacing: .2em; text-transform: uppercase; color: var(--brand); }
.mp__title { margin: 0; font-size: 1.66em; font-weight: 800; letter-spacing: -.03em; line-height: 1.22; }
.mp__lede { margin: .6em 0 0; font-size: .92em; line-height: 1.55; color: var(--muted); }
.mp__time {
    display: inline-flex; align-items: center; gap: .38em;
    margin: .7em 0 0; padding: .3em .77em; border-radius: 999px;
    font-size: .81em; font-weight: 600; color: var(--c-blue);
    background: rgba(20, 76, 141, .08);
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
}

/* ── Contents ── */
.toc { list-style: none; margin: 0; padding: 0; }
.toc__row { display: flex; align-items: baseline; gap: .77em; padding: .7em 0; border-bottom: 1px dotted rgba(11, 17, 32, .18); }
.toc__num { font-size: .81em; font-weight: 800; color: var(--brand); font-variant-numeric: tabular-nums; }
.toc__label { font-size: .96em; font-weight: 600; }
.toc__dots { flex: 1; }

/* ── Steps ── */
.steps { list-style: none; margin: 0; padding: 0; }
.steps__item { position: relative; display: flex; gap: .92em; padding-bottom: 1em; break-inside: avoid; }
.steps__item::before {
    content: '';
    position: absolute; left: 1em; top: 2.3em; bottom: .23em; width: 2px;
    background: linear-gradient(rgba(20, 76, 141, .25), rgba(20, 76, 141, .05));
}
.steps__item:last-child { padding-bottom: 0; }
.steps__item:last-child::before { display: none; }
.steps__num {
    flex-shrink: 0; display: grid; place-items: center;
    width: 2.08em; height: 2.08em; border-radius: 50%;
    font-size: .88em; font-weight: 700; color: #fff;
    background: linear-gradient(135deg, var(--c-blue), var(--c-blue-3));
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
}
.steps__body { padding-top: .23em; }
.steps__body strong { display: block; font-size: .92em; font-weight: 700; margin-bottom: .23em; }
.steps__body p { margin: 0; font-size: .85em; line-height: 1.55; color: var(--muted); }

.tip {
    display: flex; gap: .7em; align-items: baseline;
    margin: 1.15em 0 0; padding: .77em 1em;
    border-radius: .7em; border-left: 3px solid var(--accent);
    background: rgba(241, 164, 66, .12);
    font-size: .85em; line-height: 1.6; color: #6b5b4d;
    break-inside: avoid;
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
}
.tip strong { flex-shrink: 0; color: var(--ink); text-transform: uppercase; font-size: .77em; letter-spacing: .12em; }

.callout {
    margin-top: 1.7em; padding: 1.15em 1.38em; border-radius: .77em;
    background: rgba(248, 250, 252, .85); border: 1px solid rgba(11, 17, 32, .08);
    break-inside: avoid;
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
}
.callout strong { display: block; margin-bottom: .38em; font-size: .92em; font-weight: 700; }
.callout p { margin: 0; font-size: .88em; line-height: 1.65; color: var(--muted); }

/* ── FAQ ── */
.faq { margin: 0; }
.faq__row { padding: .92em 0; border-bottom: 1px solid rgba(11, 17, 32, .08); break-inside: avoid; }
.faq__row:last-child { border-bottom: 0; }
.faq dt { font-size: .92em; font-weight: 700; margin-bottom: .38em; }
.faq dd { margin: 0; font-size: .88em; line-height: 1.65; color: var(--muted); }

/* ── Closing page ── */
.mp--end {
    display: flex; flex-direction: column; justify-content: center;
    align-items: center; text-align: center; gap: .6em;
    background: radial-gradient(110% 70% at 50% 100%, rgba(226, 32, 50, .06), transparent 60%);
}
.end__logo { width: 8.5em; max-width: 45%; height: auto; margin-bottom: 1.08em; }
.end__title { margin: 0; font-size: 1.6em; font-weight: 800; letter-spacing: -.02em; }
.end__copy { margin: 0; max-width: 20em; font-size: .92em; line-height: 1.7; color: var(--muted); }
.end__contact { margin: .46em 0 0; font-size: .92em; font-weight: 600; color: var(--brand); }
.end__foot { margin: 1.7em 0 0; font-size: .77em; letter-spacing: .05em; color: var(--muted); }

/* ── Hero panel (left board, facing the title page) ── */
.mp--hero {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: .85em;
    padding: 2.6em 2.2em;
}
.hero__orgs { width: 100%; max-width: 21em; height: auto; margin-bottom: .4em; }
.hero__tagline { margin: 0; display: flex; flex-direction: column; gap: .2em; }
.hero__tagline-fil { font-size: 1.15em; font-weight: 800; color: var(--c-blue); letter-spacing: -.01em; }
.hero__tagline-sub { font-size: .85em; color: var(--muted); }
.hero__eyebrow {
    display: inline-block;
    padding: .4em 1.1em;
    border-radius: 999px;
    font-size: .62em;
    font-weight: 700;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: var(--c-blue);
    border: 1px solid rgba(20, 76, 141, .3);
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
}
.hero__wordmark { width: 100%; max-width: 17em; height: auto; margin: .3em 0; }
.hero__theme { margin: 0; display: flex; flex-direction: column; gap: .25em; }
.hero__theme-lead { font-size: 1.05em; font-weight: 800; letter-spacing: -.02em; }
.hero__theme-lead .t-red { color: var(--brand); }
.hero__theme-lead .t-blue { color: var(--c-blue); }
.hero__theme-rest { font-size: .88em; font-weight: 700; color: var(--ink); line-height: 1.45; }
.hero__meta {
    list-style: none; margin: .3em 0 0; padding: 0;
    display: flex; flex-direction: column; gap: .4em;
    font-size: .82em; color: var(--muted);
}
.hero__meta li { display: inline-flex; align-items: center; justify-content: center; gap: .45em; }
.hero__meta i { color: var(--brand-2); }
.hero__contacts {
    width: 100%;
    max-width: 22em;
    margin-top: .6em;
    padding: 1.1em 1.3em;
    border-radius: .8em;
    text-align: left;
    background: rgba(20, 76, 141, .05);
    border: 1px solid rgba(20, 76, 141, .14);
    -webkit-print-color-adjust: exact; print-color-adjust: exact;
}
.hero__contacts-head {
    display: block;
    margin-bottom: .7em;
    font-size: .66em;
    font-weight: 800;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--brand);
}
.hero__contacts ul { list-style: none; margin: 0; padding: 0; display: grid; gap: .5em; }
.hero__contacts li { display: flex; align-items: flex-start; gap: .6em; font-size: .82em; line-height: 1.45; color: var(--ink); }
.hero__contacts i { flex-shrink: 0; font-size: .95em; color: var(--c-blue); margin-top: .12em; }

.hero__collab { width: 100%; max-width: 24em; height: auto; margin-top: auto; padding-top: 1.2em; }

/* ── Print ──
 * The sheet sets a fixed pt root, and everything here is already em-relative,
 * so no size needs restating. Only the box model changes: each page now fills
 * one half of a landscape sheet rather than a whole portrait one. */
@media print {
    .mp {
        min-height: 100%;
        height: 100%;
        padding: 9mm 10mm;
        background: #fff;
        /* clip rather than spill into the facing page */
        overflow: hidden;
    }
    .mp--cover { justify-content: center; padding-top: 10mm; }
    .mp--hero { justify-content: flex-start; gap: .6em; }
    .steps__item { padding-bottom: 1.1em; }
}
</style>
