<template>
    <Head title="Registration — RSTW 2026" />

    <div class="rstw rstw-regpage">
     

        <main class="rstw-regpage__main">
            <PreRegistrationForm :session="session" :dropdowns="dropdowns" />
        </main>

    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import PreRegistrationForm from './FormVip.vue';

export default {
    layout: null,
    components: { Head, Link, PreRegistrationForm },
    props: ['dropdowns','session'],
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

.rstw-regpage__bar {
    background: #102340;
    box-shadow: 0 6px 24px rgba(11, 17, 32, .18);
    padding: 12px 0;
}
.rstw-regpage__bar-inner {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
.rstw-regpage__brand-img { height: 34px; width: auto; display: block; }
.rstw-regpage__back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    opacity: .9;
    transition: opacity .2s;
}
.rstw-regpage__back:hover { opacity: 1; }

.rstw-regpage__main { flex: 1; }

.rstw-regpage__foot {
    background: #0b1f3a;
    color: rgba(255, 255, 255, .7);
    font-size: 13px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}
.rstw-regpage__foot a { color: rgba(255, 255, 255, .7); text-decoration: none; }
.rstw-regpage__foot a:hover { color: #fff; }

@media (max-width: 640px) {
    .rstw-regpage__bar-inner { padding: 0 18px; }
}
</style>
