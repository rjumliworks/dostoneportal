<template>
    <Teleport to="body">
        <Transition name="man-fade">
            <div
                v-if="open"
                class="man-overlay"
                role="dialog"
                aria-modal="true"
                aria-labelledby="man-title"
                @click.self="close"
            >
                <div class="man-modal">
                    <!-- ── Toolbar ── -->
                    <header class="man-modal__bar">
                        <div class="man-modal__bar-title">
                            <i class="ri-book-open-line"></i>
                            <span>App User Manual</span>
                        </div>

                        <div class="man-modal__bar-actions">
                            <button ref="dlBtn" class="man-modal__dl" type="button" @click="print">
                                <i class="ri-download-2-line"></i> Download PDF
                            </button>
                            <button class="man-modal__close" type="button" aria-label="Close manual" @click="close">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    </header>

                    <!-- ══════════ 3D book ══════════ -->
                    <div class="man-stage">
                        <div
                            ref="book"
                            class="book3d"
                            :class="{ 'is-open': bookOpen }"
                            :style="{ '--leaves': leaves.length }"
                        >
                            <!-- Static boards beneath the leaves. The left one carries the
                                 hero panel, which faces the title page while the book is
                                 still on its first spread. -->
                            <div class="book3d__board book3d__board--left">
                                <!-- Always rendered so it is present in the printed
                                     document regardless of the page on screen; hidden
                                     visually once the first leaf turns onto it. -->
                                <div class="sheet board-panel" :class="{ 'is-hidden': spread !== 0 }">
                                    <ManualPage :page="{ kind: 'hero' }" :meta="meta" :guides="guides" :faqs="faqs" />
                                </div>
                            </div>
                            <!-- On the closing spread the right board carries the same
                                 hero panel as the opening one, so the book ends the way
                                 it began instead of on an empty half. -->
                            <div class="book3d__board book3d__board--right">
                                <div
                                    class="sheet board-panel board-panel--end"
                                    :class="{ 'is-hidden': spread !== totalSpreads - 1 }"
                                >
                                    <ManualPage :page="{ kind: 'hero' }" :meta="meta" :guides="guides" :faqs="faqs" />
                                </div>
                            </div>

                            <!-- Leaves: each has a front and a back face -->
                            <div
                                v-for="(leaf, i) in leaves"
                                :key="i"
                                :ref="el => leafEls[i] = el"
                                class="leaf"
                                :class="{ 'is-flipped': i < spread }"
                                :style="{ zIndex: i < spread ? i + 1 : leaves.length - i }"
                            >
                                <!-- FRONT (recto — right-hand side when closed) -->
                                <div class="leaf__face leaf__face--front">
                                    <div class="sheet" :class="`sheet--${leaf.front.kind}`">
                                        <ManualPage :page="leaf.front" :meta="meta" :guides="guides" :faqs="faqs" />
                                    </div>
                                    <span class="leaf__shade leaf__shade--front" aria-hidden="true"></span>
                                </div>

                                <!-- BACK (verso — becomes the left page once flipped) -->
                                <div class="leaf__face leaf__face--back" :class="{ 'is-blank': !leaf.back }">
                                    <div v-if="leaf.back" class="sheet" :class="`sheet--${leaf.back.kind}`">
                                        <ManualPage :page="leaf.back" :meta="meta" :guides="guides" :faqs="faqs" />
                                    </div>
                                    <span class="leaf__shade leaf__shade--back" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Page controls ── -->
                    <footer class="man-modal__nav">
                        <button
                            class="man-nav__btn"
                            type="button"
                            :disabled="spread === 0 || flipping"
                            aria-label="Previous page"
                            @click="prev"
                        ><i class="ri-arrow-left-s-line"></i></button>

                        <div class="man-nav__dots">
                            <button
                                v-for="n in totalSpreads"
                                :key="n"
                                class="man-nav__dot"
                                :class="{ 'is-on': spread === n - 1 }"
                                :aria-label="`Go to page ${n}`"
                                @click="goTo(n - 1)"
                            ></button>
                        </div>

                        <span class="man-nav__count">{{ spread + 1 }} / {{ totalSpreads }}</span>

                        <button
                            class="man-nav__btn"
                            type="button"
                            :disabled="spread >= totalSpreads - 1 || flipping"
                            aria-label="Next page"
                            @click="next"
                        ><i class="ri-arrow-right-s-line"></i></button>
                    </footer>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script>
import gsap from 'gsap';
import { MANUAL_META, GUIDES, FAQS } from './appManual.js';
import ManualPage from './ManualPage.vue';

export default {
    name: 'AppManualModal',
    components: { ManualPage },
    props: { open: { type: Boolean, default: false } },
    emits: ['close'],

    data() {
        return {
            meta: MANUAL_META,
            guides: GUIDES,
            faqs: FAQS,
            spread: 0,        // how many leaves are currently flipped
            flipping: false,
            bookOpen: false,  // drives the "book widens" opening move
            leafEls: [],
        };
    },

    computed: {
        /**
         * A book with N leaves opens to N+1 spreads — the extra one shows the
         * final leaf's verso. Counting spreads as `leaves.length` leaves the
         * closing page permanently unreachable.
         */
        totalSpreads() { return this.leaves.length + 1; },

        /**
         * Pair the logical pages into physical leaves. A real leaf carries two
         * pages — recto on the front, verso on the back — which is what makes
         * the flip read as paper rather than as sliding cards.
         */
        leaves() {
            const pages = [
                { kind: 'cover' },
                { kind: 'toc' },
                ...this.guides.map((g, i) => ({ kind: 'chapter', index: i })),
                { kind: 'questions' },
                { kind: 'faq' },
                { kind: 'end' },
            ];

            const out = [];
            for (let i = 0; i < pages.length; i += 2) {
                out.push({ front: pages[i], back: pages[i + 1] || null });
            }
            return out;
        },
    },

    watch: {
        open(isOpen) {
            if (isOpen) {
                this.lastFocus = document.activeElement;
                document.body.style.overflow = 'hidden';
                this.spread = 0;
                this.bookOpen = false;
                this.$nextTick(() => {
                    this.$refs.dlBtn?.focus();
                    // Let the modal transition land, then open the cover.
                    gsap.delayedCall(0.45, () => { this.bookOpen = true; });
                });
            } else {
                document.body.style.overflow = '';
                this.lastFocus?.focus?.();
            }
        },
    },

    mounted() { window.addEventListener('keydown', this.onKey); },

    beforeUnmount() {
        window.removeEventListener('keydown', this.onKey);
        document.body.style.overflow = '';
    },

    beforeUpdate() { this.leafEls = []; },

    methods: {
        close() { this.$emit('close'); },

        onKey(e) {
            if (!this.open) return;
            if (e.key === 'Escape') this.close();
            if (e.key === 'ArrowRight') this.next();
            if (e.key === 'ArrowLeft') this.prev();
        },

        /**
         * Flip one leaf.
         *
         * The rotation itself is a CSS transition on .is-flipped, but the
         * *shading* is animated here: a gradient overlay darkens as the page
         * stands at 90° and clears as it lands. Without that light response a
         * 3D flip reads as flat cardboard — the shading is what sells paper.
         */
        flip(dir) {
            if (this.flipping) return;
            const i = dir > 0 ? this.spread : this.spread - 1;
            const el = this.leafEls[i];
            if (!el) return;

            this.flipping = true;
            this.spread += dir;

            const shades = el.querySelectorAll('.leaf__shade');
            gsap.timeline({ onComplete: () => { this.flipping = false; } })
                .fromTo(shades,
                    { opacity: 0 },
                    { opacity: 0.55, duration: 0.4, ease: 'power2.in' })
                .to(shades, { opacity: 0, duration: 0.45, ease: 'power2.out' });
        },

        next() { if (this.spread < this.totalSpreads - 1) this.flip(1); },
        prev() { if (this.spread > 0) this.flip(-1); },

        /** Jump to a leaf, flipping through the intervening pages in sequence. */
        goTo(target) {
            target = Math.max(0, Math.min(this.totalSpreads - 1, target));
            if (this.flipping || target === this.spread) return;
            const dir = target > this.spread ? 1 : -1;
            const step = () => {
                if (this.spread === target) return;
                this.flip(dir);
                gsap.delayedCall(0.28, step);
            };
            step();
        },

        /**
         * Print / Save as PDF.
         * Printing a 3D-transformed stack would output overlapping garbage, so
         * the print stylesheet flattens the book: transforms are cleared and
         * every leaf face becomes an ordinary sheet in reading order.
         */
        print() {
            document.body.classList.add('is-printing-manual');
            const cleanup = () => {
                document.body.classList.remove('is-printing-manual');
                window.removeEventListener('afterprint', cleanup);
            };
            window.addEventListener('afterprint', cleanup);
            window.print();
            setTimeout(cleanup, 1500);   // Safari does not always fire afterprint
        },
    },
};
</script>

<!-- Global: print rules reach outside this component, and Teleport moves the
     modal out of the scoped tree. -->
<style>
@media print {
    /* Landscape sheet holding a two-page spread — each book page fills one
       half, so the PDF reads like the open book rather than a stack of
       portrait pages. */
    @page { size: A4 landscape; margin: 10mm; }

    body.is-printing-manual > *:not(.man-overlay) { display: none !important; }

    body.is-printing-manual .man-overlay {
        position: static !important;
        inset: auto !important;
        display: block !important;
        padding: 0 !important;
        background: #fff !important;
        overflow: visible !important;
        backdrop-filter: none !important;
    }
    body.is-printing-manual .man-modal {
        max-width: none !important; max-height: none !important;
        height: auto !important; border-radius: 0 !important;
        box-shadow: none !important; overflow: visible !important;
        display: block !important; background: #fff !important;
    }
    body.is-printing-manual .man-modal__bar,
    body.is-printing-manual .man-modal__nav { display: none !important; }

    /* Flatten the 3D stage into a flowing document. */
    body.is-printing-manual .man-stage {
        perspective: none !important;
        display: block !important;
        padding: 0 !important;
        background: #fff !important;
        overflow: visible !important;
    }
    body.is-printing-manual .book3d {
        transform: none !important;
        transform-style: flat !important;
        width: auto !important; height: auto !important;
        /* Faces become siblings in a wrapping row: two per landscape sheet. */
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: stretch !important;
    }

    /* The hero panel is a real page — it opens the document facing the cover. */
    body.is-printing-manual .book3d__board--left {
        position: static !important;
        display: block !important;
        width: 50% !important;
        height: var(--print-page-h) !important;
        overflow: hidden !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: #fff !important;
    }
    /* The closing panel duplicates the opening one — print it once. */
    body.is-printing-manual .book3d__board--right { display: none !important; }

    /* `display: contents` lifts each leaf's two faces into the flex row, so
       front and back paginate as consecutive pages instead of overlapping. */
    body.is-printing-manual .leaf {
        display: contents !important;
    }
    body.is-printing-manual .leaf__face {
        position: static !important;
        transform: none !important;
        backface-visibility: visible !important;
        width: 50% !important;
        /* A fixed height is what forces exactly two halves per sheet. */
        height: var(--print-page-h) !important;
        overflow: hidden !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: #fff !important;
        break-inside: avoid;
        page-break-inside: avoid;
    }
    body.is-printing-manual .leaf__shade,
    body.is-printing-manual .leaf__face::after { display: none !important; }

    body.is-printing-manual .sheet {
        /* A4 landscape half-page is narrower than the screen page, so the type
           scale steps down to match. */
        font-size: 10.5pt !important;
        height: 100% !important;
        overflow: hidden !important;
        box-shadow: none !important;
        background: #fff !important;
    }
    /* A leaf with no verso would otherwise print an empty half. */
    /* A leaf with no verso must not print an empty half. */
    body.is-printing-manual .leaf__face.is-blank { display: none !important; }

    /* The facing panel is hidden on screen past the first spread — always print it. */
    body.is-printing-manual .board-panel { opacity: 1 !important; }

    /* Nothing may force a break after the final page. */
    body.is-printing-manual .book3d > *:last-child,
    body.is-printing-manual .leaf__face:last-child {
        break-after: avoid !important;
        page-break-after: avoid !important;
    }
}
</style>

<style scoped>
/* ══════════ Overlay + shell ══════════ */
.man-overlay {
    position: fixed;
    inset: 0;
    z-index: 3000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    background: rgba(6, 12, 26, .82);
    backdrop-filter: blur(8px);
}
.man-modal {
    /* Printable height of an A4 landscape sheet is 210mm less 2x10mm margins
       = 190mm. Deliberately a few mm under: an exact fit rounds up in most
       print engines and emits a blank sheet after every page. */
    --print-page-h: 184mm;

    --ink: #211215;
    --brand: #E22032;
    --brand-2: #EC864C;
    --accent: #F1A442;
    --c-blue: #144C8D;
    --c-blue-2: #46A0C1;
    --c-blue-3: #5386BF;
    --navy: #102340;
    --muted: #5b6472;

    font-family: 'Agrandir', 'Roboto', system-ui, -apple-system, Segoe UI, sans-serif;
    color: var(--ink);
    /* Full-screen: the book wants every pixel it can get. */
    width: 100%;
    height: 100%;
    max-height: none;
    display: flex;
    flex-direction: column;
    border-radius: 0;
    overflow: hidden;
    background: linear-gradient(180deg, #131c2e, #0c1422);
}

.man-modal__bar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; padding: 14px 18px; flex-shrink: 0;
    background: var(--navy); color: #fff;
}
.man-modal__bar-title { display: inline-flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; }
.man-modal__bar-title i { font-size: 19px; color: var(--accent); }
.man-modal__bar-actions { display: flex; align-items: center; gap: 10px; }
.man-modal__dl {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 18px; border: 0; border-radius: 999px;
    font-size: 13px; font-weight: 600; color: #fff; cursor: pointer;
    background: linear-gradient(135deg, var(--brand), var(--brand-2));
    box-shadow: 0 8px 18px rgba(226, 32, 50, .3);
    transition: transform .2s, box-shadow .2s;
}
.man-modal__dl:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(226, 32, 50, .38); }
.man-modal__close {
    width: 34px; height: 34px; display: grid; place-items: center;
    border-radius: 50%; cursor: pointer; font-size: 20px; color: #fff;
    background: rgba(255, 255, 255, .1); border: 1px solid rgba(255, 255, 255, .18);
    transition: background .2s;
}
.man-modal__close:hover { background: rgba(255, 255, 255, .2); }

/* ══════════ 3D stage ══════════
 * perspective on the stage (not the book) so every leaf shares one vanishing
 * point — per-element perspective makes pages appear to bend independently. */
.man-stage {
    flex: 1;
    min-height: 0;
    display: grid;
    place-items: center;
    padding: 24px 24px;
    overflow: hidden;
    perspective: 2600px;
    perspective-origin: 50% 42%;
    background:
        radial-gradient(70% 60% at 50% 40%, rgba(70, 160, 193, .10), transparent 70%);
}

.book3d {
    /* Fill the stage vertically: the toolbar (~62px), nav (~66px) and stage
       padding (~60px) are the only other consumers of viewport height.
       Width follows from the height at A4 proportion (1 : 1.414), so the page
       keeps a believable paper shape at any window size while showing a whole
       chapter without scrolling. */
    --page-h: min(880px, calc(100vh - 196px));
    --page-w: min(44vw, calc(var(--page-h) * 0.707));

    position: relative;
    width: calc(var(--page-w) * 2);
    height: var(--page-h);
    transform-style: preserve-3d;
    /* Closed: sits square-on and slightly back. Open: tips into a shallow
       three-quarter view, which is what reads as a physical object. */
    transform: rotateX(0deg) translateZ(-60px);
    transition: transform 1s cubic-bezier(.16, 1, .3, 1);
}
.book3d.is-open { transform: rotateX(8deg) translateZ(0); }

/* The boards are the visible "paper underneath" on each side. */
.book3d__board {
    position: absolute;
    top: 0;
    width: var(--page-w);
    height: 100%;
    background: #f4f1ea url('/images/2026/bg.png') center / cover no-repeat;
    border-radius: 3px;
}
.book3d__board--left {
    left: 0;
    overflow: hidden;
    border-radius: 4px 2px 2px 4px;
    /* spine shadow falls to the right, toward the gutter */
    box-shadow: inset -18px 0 26px -18px rgba(0, 0, 0, .45),
                -10px 14px 34px rgba(0, 0, 0, .3);
}
.book3d__board--right {
    left: var(--page-w);
    overflow: hidden;
    border-radius: 2px 4px 4px 2px;
    box-shadow: inset 18px 0 26px -18px rgba(0, 0, 0, .45),
                10px 14px 34px rgba(0, 0, 0, .3);
}

/* ── Leaves ── */
.leaf {
    position: absolute;
    top: 0;
    left: var(--page-w);          /* right half — leaves live on the recto side */
    width: var(--page-w);
    height: 100%;
    transform-style: preserve-3d;
    transform-origin: left center; /* the spine */
    transition: transform .85s cubic-bezier(.53, .06, .28, .99);
}
.leaf.is-flipped { transform: rotateY(-180deg); }

.leaf__face {
    position: absolute;
    inset: 0;
    overflow: hidden;
    backface-visibility: hidden;
    /* bg.png is a near-white petal pattern — it reads as a watermarked stock
       under the page content rather than as a picture. */
    background: #fff url('/images/2026/bg.png') center / cover no-repeat;
}
.leaf__face--front { border-radius: 2px 5px 5px 2px; box-shadow: inset 14px 0 22px -18px rgba(0,0,0,.4); }
.leaf__face--back {
    transform: rotateY(180deg);
    border-radius: 5px 2px 2px 5px;
    box-shadow: inset -14px 0 22px -18px rgba(0,0,0,.4);
}

/* Light response during the flip — animated from GSAP. */
.leaf__shade {
    position: absolute;
    inset: 0;
    opacity: 0;
    pointer-events: none;
}
.leaf__shade--front { background: linear-gradient(to left, rgba(0,0,0,.55), rgba(0,0,0,0) 55%); }
.leaf__shade--back { background: linear-gradient(to right, rgba(0,0,0,.55), rgba(0,0,0,0) 55%); }

/* The printable sheet inside each face */
.sheet {
    position: relative;
    width: 100%;
    height: 100%;
    overflow-y: auto;
    /* Root of the page type scale. Every size inside ManualPage is expressed
       in em, so the whole page re-proportions with the paper instead of
       overflowing on short screens or looking tiny on large ones.
       The divisor is tuned so the longest chapter fills — but does not exceed
       — one page; the clamp keeps it readable at both extremes. */
    font-size: clamp(8.5px, calc(var(--page-h) / 47), 17px);
    /* transparent so the leaf's watermarked paper shows through */
    background: transparent;
    scrollbar-width: thin;
}
/* Slim scrollbar — the default chrome looks wrong on a page of a book. */
.sheet::-webkit-scrollbar { width: 5px; }
.sheet::-webkit-scrollbar-thumb { background: rgba(11, 17, 32, .18); border-radius: 3px; }
.sheet::-webkit-scrollbar-track { background: transparent; }

/* Fade at the foot of a page that still overflows, so clipped content is
   visibly "more" rather than an abrupt cut. Sits above the paper, below the
   flip shading. */
.leaf__face::after {
    content: '';
    position: absolute;
    left: 0; right: 0; bottom: 0;
    height: 26px;
    pointer-events: none;
    background: linear-gradient(to top, rgba(255, 255, 255, .95), rgba(255, 255, 255, 0));
}

/* ══════════ Nav ══════════ */
.man-modal__nav {
    display: flex; align-items: center; justify-content: center;
    gap: 16px; padding: 14px 18px; flex-shrink: 0;
    background: rgba(0, 0, 0, .25);
    border-top: 1px solid rgba(255, 255, 255, .07);
}
.man-nav__btn {
    width: 38px; height: 38px; display: grid; place-items: center;
    border-radius: 50%; cursor: pointer; font-size: 22px; color: #fff;
    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .16);
    transition: background .2s, transform .2s, opacity .2s;
}
.man-nav__btn:hover:not(:disabled) { background: rgba(255, 255, 255, .18); transform: scale(1.06); }
.man-nav__btn:disabled { opacity: .3; cursor: not-allowed; }
.man-nav__dots { display: flex; gap: 7px; }
.man-nav__dot {
    width: 8px; height: 8px; padding: 0; border: 0; border-radius: 50%;
    cursor: pointer; background: rgba(255, 255, 255, .25);
    transition: background .3s, transform .3s;
}
.man-nav__dot.is-on { background: var(--accent); transform: scale(1.4); }
.man-nav__count { font-size: 12px; color: rgba(255, 255, 255, .5); font-variant-numeric: tabular-nums; min-width: 54px; text-align: center; }

/* The hero panel dissolves as the first leaf turns onto it. */
.board-panel { transition: opacity .35s ease .3s; }
.board-panel.is-hidden { opacity: 0; transition-delay: 0s; }

/* ══════════ Transition ══════════ */
.man-fade-enter-active, .man-fade-leave-active { transition: opacity .3s ease; }
.man-fade-enter-active .man-modal, .man-fade-leave-active .man-modal {
    transition: transform .4s cubic-bezier(.16, 1, .3, 1), opacity .4s ease;
}
.man-fade-enter-from, .man-fade-leave-to { opacity: 0; }
.man-fade-enter-from .man-modal, .man-fade-leave-to .man-modal { transform: translateY(24px) scale(.96); opacity: 0; }

/* ══════════ Narrow screens: one page, no spread ══════════ */
@media (max-width: 820px) {
    .man-stage { padding: 18px 12px; perspective: 1600px; }
    .book3d {
        --page-h: min(680px, calc(100vh - 176px));
        --page-w: min(92vw, calc(var(--page-h) * 0.707));
        width: var(--page-w);      /* single page — a spread will not fit */
    }
    /* With no right half, leaves sit at the left edge and the boards collapse. */
    .leaf { left: 0; }
    .book3d__board--left { display: none; }
    .book3d__board--right { left: 0; }
}

/* Reduced motion: keep the page turn, drop the tilt and the long easing. */
@media (prefers-reduced-motion: reduce) {
    .book3d, .leaf { transition-duration: .01ms !important; }
    .book3d.is-open { transform: none; }
}
</style>
