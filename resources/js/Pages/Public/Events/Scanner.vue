<template>
    <Head title="Opening"/>
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light position-relative overflow-hidden">
        <svg class="pillars-page-bg" viewBox="-900 -900 1800 1800" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <g class="pillar-page-quadrant" :class="{ 'is-colored': hasScanned }">
                <path d="M 0 0 L -1400 0 A 1400 1400 0 0 1 0 -1400 Z" fill="#E8940C" opacity="0.035"/>
            </g>
            <g class="pillar-page-quadrant" :class="{ 'is-colored': hasScanned }">
                <path d="M 0 0 L 0 -1400 A 1400 1400 0 0 1 1400 0 Z" fill="#274F79" opacity="0.035"/>
            </g>
            <g class="pillar-page-quadrant" :class="{ 'is-colored': hasScanned }">
                <path d="M 0 0 L 1400 0 A 1400 1400 0 0 1 0 1400 Z" fill="#C4577B" opacity="0.035"/>
            </g>
            <g class="pillar-page-quadrant" :class="{ 'is-colored': hasScanned }">
                <path d="M 0 0 L 0 1400 A 1400 1400 0 0 1 -1400 0 Z" fill="#3D8149" opacity="0.035"/>
            </g>

            <g
                v-for="(shape, i) in floatingShapes"
                :key="i"
                class="pillar-page-quadrant"
                :class="{ 'is-colored': hasScanned }"
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

                    <!-- Wealth Creation: bar chart, coin stack, dollar-sign post, growth arrow, briefcase -->
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

                    <!-- Wealth Protection: shield, castle turret, padlock tower, umbrella, vault box -->
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

                    <!-- Human Well Being: heart on a stand, people pair, gift ribbon, medical cross, helping hands, DOST atom/sprout -->
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

                    <!-- Sustainability: pine tree, round tree, grass tufts, radiating-branch tree, wind turbine, leaf -->
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

        <div class="camera-stage position-relative" ref="cameraStage">
            <svg class="pillars-bg" viewBox="-350 -350 1700 1700" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="centerGrad" cx="50%" cy="35%" r="75%">
                        <stop offset="0%" stop-color="#EAF6FF"/>
                        <stop offset="55%" stop-color="#BFE3F5"/>
                        <stop offset="100%" stop-color="#8FC7E8"/>
                    </radialGradient>
                    <filter id="softShadow" x="-40%" y="-40%" width="180%" height="180%">
                        <feDropShadow dx="0" dy="3" stdDeviation="4" flood-color="#00000030"/>
                    </filter>
                </defs>

                <g class="pillar-quadrant" :class="{ 'is-colored': hasScanned }">
                    <g transform="translate(254.1,467.6) rotate(277.5) scale(2.00)"><rect x="-24" y="-22" width="12" height="22" fill="#F4A623" opacity="0.65"/><rect x="-8" y="-38" width="12" height="38" fill="#F4A623" opacity="0.75"/><rect x="8" y="-55" width="12" height="55" fill="#F4A623" opacity="0.8500000000000001"/><rect x="24" y="-70" width="12" height="70" fill="#F4A623" opacity="0.9500000000000001"/><path d="M -26 -75 L 8 -95 L 24 -80 L 30 -100" stroke="#F4A623" stroke-width="4" fill="none" stroke-linecap="round"/><path d="M 22 -100 L 30 -100 L 30 -92" stroke="#F4A623" stroke-width="4" fill="none" stroke-linecap="round"/></g>
                    <g transform="translate(270.9,405.1) rotate(292.5) scale(2.30)"><ellipse cx="0" cy="-8" rx="26" ry="9" fill="#E8940C" opacity="0.6"/><ellipse cx="0" cy="-21" rx="26" ry="9" fill="#E8940C" opacity="0.7"/><ellipse cx="0" cy="-34" rx="26" ry="9" fill="#E8940C" opacity="0.8"/><ellipse cx="0" cy="-47" rx="26" ry="9" fill="#E8940C" opacity="0.9"/><text x="0" y="-43" text-anchor="middle" font-size="14" font-weight="700" fill="#ffffff" font-family="Arial">$</text></g>
                    <g transform="translate(303.2,349.0) rotate(307.5) scale(1.90)">
                        <rect x="-4" y="-60" width="8" height="60" fill="#FFC857"/>
                        <circle cx="0" cy="-72" r="18" fill="#FFC857"/>
                        <text x="0" y="-66" text-anchor="middle" font-size="18" font-weight="700" fill="#ffffff" font-family="Arial">$</text>
                    </g>
                    <g transform="translate(349.0,303.2) rotate(322.5) scale(2.40)">
                        <path d="M -26 0 L -10 -30 L 4 -14 L 26 -55" stroke="#D98C0F" stroke-width="7" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M 6 -55 L 26 -55 L 26 -35" stroke="#D98C0F" stroke-width="7" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <g transform="translate(405.1,270.9) rotate(337.5) scale(2.10)">
                        <rect x="-24" y="-40" width="48" height="34" rx="4" fill="#F4A623"/>
                        <rect x="-10" y="-50" width="20" height="12" rx="2" fill="none" stroke="#F4A623" stroke-width="5"/>
                        <rect x="-24" y="-26" width="48" height="4" fill="#ffffff" opacity="0.5"/>
                    </g>
                    <g transform="translate(467.6,254.1) rotate(352.5) scale(2.20)"><rect x="-24" y="-22" width="12" height="22" fill="#E8940C" opacity="0.65"/><rect x="-8" y="-38" width="12" height="38" fill="#E8940C" opacity="0.75"/><rect x="8" y="-55" width="12" height="55" fill="#E8940C" opacity="0.8500000000000001"/><rect x="24" y="-70" width="12" height="70" fill="#E8940C" opacity="0.9500000000000001"/><path d="M -26 -75 L 8 -95 L 24 -80 L 30 -100" stroke="#E8940C" stroke-width="4" fill="none" stroke-linecap="round"/><path d="M 22 -100 L 30 -100 L 30 -92" stroke="#E8940C" stroke-width="4" fill="none" stroke-linecap="round"/></g>
                </g>

                <g class="pillar-quadrant" :class="{ 'is-colored': hasScanned }">
                    <g transform="translate(532.4,254.1) rotate(7.5) scale(2.00)">
                        <path d="M 0 -80 L 28 -66 L 28 -30 Q 28 4 0 18 Q -28 4 -28 -30 L -28 -66 Z" fill="#2E5C8A"/>
                        <path d="M -10 -42 L -3 -32 L 14 -55" stroke="#ffffff" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <g transform="translate(594.9,270.9) rotate(22.5) scale(2.30)"><rect x="-20" y="-55" width="40" height="55" fill="#1F4568"/><rect x="-20" y="-65" width="8" height="12" fill="#1F4568"/><rect x="-8" y="-65" width="8" height="12" fill="#1F4568"/><rect x="4" y="-65" width="8" height="12" fill="#1F4568"/><rect x="16" y="-65" width="8" height="12" fill="#1F4568"/><rect x="-6" y="-24" width="12" height="24" fill="#ffffff" opacity="0.35"/></g>
                    <g transform="translate(651.0,303.2) rotate(37.5) scale(1.90)">
                        <rect x="-18" y="-42" width="36" height="42" rx="4" fill="#4A80B5"/>
                        <path d="M -11 -42 L -11 -58 A 11 11 0 0 1 11 -58 L 11 -42" fill="none" stroke="#4A80B5" stroke-width="7"/>
                        <circle cx="0" cy="-22" r="5" fill="#ffffff"/>
                    </g>
                    <g transform="translate(696.8,349.0) rotate(52.5) scale(2.40)">
                        <rect x="-3" y="-20" width="6" height="20" fill="#7a5230"/>
                        <path d="M -32 -20 Q -32 -62 0 -62 Q 32 -62 32 -20 Z" fill="#274F79"/>
                        <path d="M -32 -20 Q -22 -10 -12 -20" stroke="#274F79" stroke-width="4" fill="none"/>
                        <path d="M -12 -20 Q -2 -10 8 -20" stroke="#274F79" stroke-width="4" fill="none"/>
                        <path d="M 8 -20 Q 18 -10 28 -20" stroke="#274F79" stroke-width="4" fill="none"/>
                    </g>
                    <g transform="translate(729.1,405.1) rotate(67.5) scale(2.10)">
                        <rect x="-24" y="-40" width="48" height="40" rx="4" fill="#2E5C8A"/>
                        <circle cx="0" cy="-20" r="10" fill="none" stroke="#ffffff" stroke-width="3"/>
                        <circle cx="0" cy="-20" r="2.5" fill="#ffffff"/>
                    </g>
                    <g transform="translate(745.9,467.6) rotate(82.5) scale(2.20)">
                        <path d="M 0 -80 L 28 -66 L 28 -30 Q 28 4 0 18 Q -28 4 -28 -30 L -28 -66 Z" fill="#1F4568"/>
                        <path d="M -10 -42 L -3 -32 L 14 -55" stroke="#ffffff" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                </g>

                <g class="pillar-quadrant" :class="{ 'is-colored': hasScanned }">
                    <g transform="translate(745.9,532.4) rotate(97.5) scale(2.00)">
                        <rect x="-3" y="-25" width="6" height="25" fill="#D14D72" opacity="0.7"/>
                        <path d="M 0 -30 C -32 -55 -20 -85 0 -65 C 20 -85 32 -55 0 -30 Z" fill="#D14D72"/>
                    </g>
                    <g transform="translate(729.1,594.9) rotate(112.5) scale(2.30)"><circle cx="-14" cy="-58" r="10" fill="#B83A5D"/><path d="M -26 0 Q -26 -42 -14 -42 Q -2 -42 -2 0 Z" fill="#B83A5D"/><circle cx="14" cy="-58" r="10" fill="#B83A5D"/><path d="M 2 0 Q 2 -42 14 -42 Q 26 -42 26 0 Z" fill="#B83A5D"/></g>
                    <g transform="translate(696.8,651.0) rotate(127.5) scale(1.90)">
                        <rect x="-6" y="-70" width="12" height="70" fill="#EA8CA6"/>
                        <rect x="-24" y="-52" width="48" height="14" fill="#EA8CA6"/>
                    </g>
                    <g transform="translate(651.0,696.8) rotate(142.5) scale(2.40)">
                        <path d="M -26 0 Q -26 -35 0 -35 Q 26 -35 26 0" fill="none" stroke="#C4577B" stroke-width="8" stroke-linecap="round"/>
                        <circle cx="0" cy="-48" r="16" fill="#C4577B"/>
                    </g>
                    <g transform="translate(594.9,729.1) rotate(157.5) scale(2.10)">
                        <rect x="-3" y="-25" width="6" height="25" fill="#7a5230" opacity="0.7"/>
                        <circle cx="0" cy="-55" r="26" fill="#D14D72"/>
                        <circle cx="-9" cy="-60" r="3" fill="#ffffff"/>
                        <circle cx="9" cy="-60" r="3" fill="#ffffff"/>
                        <path d="M -10 -48 Q 0 -40 10 -48" stroke="#ffffff" stroke-width="3" fill="none" stroke-linecap="round"/>
                    </g>
                    <g transform="translate(532.4,745.9) rotate(172.5) scale(2.20)">
                        <rect x="-3" y="-25" width="6" height="25" fill="#B83A5D" opacity="0.7"/>
                        <path d="M 0 -30 C -32 -55 -20 -85 0 -65 C 20 -85 32 -55 0 -30 Z" fill="#B83A5D"/>
                    </g>
                </g>

                <g class="pillar-quadrant" :class="{ 'is-colored': hasScanned }">
                    <g transform="translate(467.6,745.9) rotate(187.5) scale(2.00)">
                        <rect x="-4" y="-18" width="8" height="18" fill="#7a5230"/>
                        <path d="M 0 -85 L 22 -50 L 10 -50 L 28 -22 L -28 -22 L -10 -50 L -22 -50 Z" fill="#4C9A5B"/>
                    </g>
                    <g transform="translate(405.1,729.1) rotate(202.5) scale(2.30)">
                        <rect x="-4" y="-14" width="8" height="14" fill="#7a5230"/>
                        <circle cx="0" cy="-42" r="26" fill="#3D8149"/>
                        <circle cx="-14" cy="-28" r="16" fill="#3D8149"/>
                        <circle cx="14" cy="-28" r="16" fill="#3D8149"/>
                    </g>
                    <g transform="translate(349.0,696.8) rotate(217.5) scale(1.90)"><path d="M -14 0 Q -22 -20.0 -26.8 -40" stroke="#78C187" stroke-width="6" fill="none" stroke-linecap="round"/><path d="M -5 0 Q -1 -27.5 1.4000000000000004 -55" stroke="#78C187" stroke-width="6" fill="none" stroke-linecap="round"/><path d="M 5 0 Q 1 -29.0 -1.4000000000000004 -58" stroke="#78C187" stroke-width="6" fill="none" stroke-linecap="round"/><path d="M 14 0 Q 22 -21.0 26.8 -42" stroke="#78C187" stroke-width="6" fill="none" stroke-linecap="round"/></g>
                    <g transform="translate(303.2,651.0) rotate(232.5) scale(2.40)"><rect x="-3" y="-95" width="6" height="95" fill="#c8c8c8"/><circle cx="0" cy="-95" r="5" fill="#999"/><path d="M 0 -95 Q 0.0 -107 0.0 -125.0" stroke="#469153" stroke-width="8" fill="none" stroke-linecap="round"/><path d="M 0 -95 Q 12.99038105676658 -107 25.98076211353316 -80.0" stroke="#469153" stroke-width="8" fill="none" stroke-linecap="round"/><path d="M 0 -95 Q -12.990381056766577 -107 -25.980762113533153 -79.99999999999999" stroke="#469153" stroke-width="8" fill="none" stroke-linecap="round"/></g>
                    <g transform="translate(270.9,594.9) rotate(247.5) scale(2.10)">
                        <path d="M 0 0 Q -30 -35 0 -75 Q 30 -35 0 0 Z" fill="#4C9A5B"/>
                        <path d="M 0 -8 Q 0 -40 0 -70" stroke="#ffffff" stroke-width="2.5" opacity="0.6" fill="none"/>
                    </g>
                    <g transform="translate(254.1,532.4) rotate(262.5) scale(2.20)">
                        <rect x="-4" y="-18" width="8" height="18" fill="#7a5230"/>
                        <path d="M 0 -85 L 22 -50 L 10 -50 L 28 -22 L -28 -22 L -10 -50 L -22 -50 Z" fill="#3D8149"/>
                    </g>
                </g>

                <g class="pillar-core" :class="{ 'is-colored': hasScanned }">
                    <circle cx="500" cy="500" r="240" fill="url(#centerGrad)" stroke="#FFFFFF" stroke-width="14" filter="url(#softShadow)"/>
                    <circle cx="500" cy="500" r="240" fill="none" stroke="#5FA8D3" stroke-width="3" opacity="0.6"/>
                    <line x1="255.0" y1="500.0" x2="-168.0" y2="500.0" stroke="#FFFFFF" stroke-width="5" opacity="0.85"/>
                    <line x1="500.0" y1="255.0" x2="500.0" y2="-168.0" stroke="#FFFFFF" stroke-width="5" opacity="0.85"/>
                    <line x1="745.0" y1="500.0" x2="1168.0" y2="500.0" stroke="#FFFFFF" stroke-width="5" opacity="0.85"/>
                    <line x1="500.0" y1="745.0" x2="500.0" y2="1168.0" stroke="#FFFFFF" stroke-width="5" opacity="0.85"/>
                </g>
            </svg>

            <canvas ref="fireworksCanvas" class="fireworks-canvas"></canvas>

            <div
                class="camera-ring"
                :class="'ring-' + ringState"
                :style="{ '--progress': ringDisplayProgress + '%', '--ring-color': ringColor }">
                <div class="camera-circle">
                    <video
                        ref="video"
                        autoplay
                        playsinline
                        class="camera-circle-video">
                    </video>

                    <div class="capture-success" :class="{ 'is-error': ringOutcome === 'error' }" v-if="ringState === 'success'">
                        <i :class="ringOutcome === 'error' ? 'ri-close-line' : 'ri-check-line'"></i>
                    </div>

                    <div class="capture-flash" v-if="flashActive"></div>

                    <button
                        type="button"
                        class="btn-scan-trigger"
                        :disabled="ringState !== 'idle' || scanning"
                        @click="captureScan">
                        <i class="ri-camera-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
const FIREWORKS_PALETTE = [
    '#F4A623', '#E8940C', '#FFC857',
    '#2E5C8A', '#1F4568', '#4A80B5',
    '#D14D72', '#B83A5D', '#EA8CA6',
    '#4C9A5B', '#3D8149', '#78C187'
];

const FIREWORKS_SHOW_DURATION = 10000;

// A few shades per pillar (matching its icon colors in the SVG), used to tint the floating background icons
const PILLAR_PALETTES = [
    ['#F4A623', '#E8940C', '#FFC857', '#D98C0F'],
    ['#2E5C8A', '#1F4568', '#4A80B5', '#274F79'],
    ['#D14D72', '#B83A5D', '#EA8CA6', '#C4577B'],
    ['#4C9A5B', '#3D8149', '#78C187', '#469153']
];

function randBetween(min, max) {
    return Math.random() * (max - min) + min;
}

// Keeps a point at least minRadius away from the center by pushing it radially outward,
// so floating icons never drift over the camera circle.
function pushAwayFromCenter(x, y, minRadius) {
    const dist = Math.hypot(x, y) || 0.001;
    if (dist >= minRadius) return { x, y };
    const scale = minRadius / dist;
    return { x: x * scale, y: y * scale };
}

// Randomly scattered, slow-drifting low-opacity icons per pillar quadrant, filling whatever
// screen size renders them (the SVG uses preserveAspectRatio="slice" to always cover the viewport).
function generateFloatingShapes() {
    const perQuadrant = 16;
    const centerKeepOut = 420; // clears the camera ring
    // How many distinct icon templates exist per quadrant (see the v-for template block) -
    // iconIndex cycles through all of them so every icon type is guaranteed to appear, not just likely to.
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
            const pos = pushAwayFromCenter(
                signX * randBetween(60, 1300),
                signY * randBetween(60, 1300),
                centerKeepOut
            );
            shapes.push({
                quadrant,
                iconIndex: i % iconCount,
                color: palette[Math.floor(randBetween(0, palette.length))],
                x: pos.x,
                y: pos.y,
                rotate: randBetween(0, 360),
                scale: randBetween(0.7, 1.6),
                opacity: randBetween(0.14, 0.28),
                duration: randBetween(6, 12),
                delay: -randBetween(0, 10),
                drift: (Math.random() < 0.5 ? -1 : 1) * randBetween(12, 28)
            });
        }
    });

    return shapes;
}

class FireworkParticle {
    constructor(x, y, color, speedMin, speedMax, angle) {
        this.x = x;
        this.y = y;
        const a = angle === undefined ? randBetween(0, Math.PI * 2) : angle;
        const speed = randBetween(speedMin, speedMax);
        this.vx = Math.cos(a) * speed;
        this.vy = Math.sin(a) * speed;
        this.color = color;
        this.alpha = 1;
        this.gravity = 0.02;
        this.decay = randBetween(0.01, 0.02);
        this.size = randBetween(2, 4);
    }
    update() {
        this.vy += this.gravity;
        this.vx *= 0.985;
        this.vy *= 0.985;
        this.x += this.vx;
        this.y += this.vy;
        this.alpha -= this.decay;
    }
    draw(ctx) {
        ctx.save();
        ctx.globalAlpha = Math.max(this.alpha, 0);
        ctx.fillStyle = this.color;
        ctx.shadowColor = this.color;
        ctx.shadowBlur = 6;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }
    get dead() {
        return this.alpha <= 0;
    }
}

// A shell that launches outward from the camera ring on a straight trail and
// bursts into a FireworkParticle explosion once it reaches its target distance -
// the "rocket" half of an aerial firework, as opposed to FireworkParticle's burst half.
class FireworkRocket {
    constructor(x, y, angle, distance, color) {
        this.x = x;
        this.y = y;
        this.angle = angle;
        this.color = color;
        this.distance = distance;
        this.traveled = 0;
        this.speed = randBetween(9, 13);
        this.vx = Math.cos(angle) * this.speed;
        this.vy = Math.sin(angle) * this.speed;
        this.trail = [];
    }
    update() {
        this.trail.push({ x: this.x, y: this.y, alpha: 1 });
        if (this.trail.length > 14) this.trail.shift();
        this.trail.forEach(t => { t.alpha -= 0.09; });

        this.x += this.vx;
        this.y += this.vy;
        this.traveled += this.speed;
    }
    draw(ctx) {
        ctx.save();
        this.trail.forEach(t => {
            if (t.alpha <= 0) return;
            ctx.globalAlpha = t.alpha;
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.arc(t.x, t.y, 2, 0, Math.PI * 2);
            ctx.fill();
        });

        ctx.globalAlpha = 1;
        ctx.fillStyle = '#fff';
        ctx.shadowColor = this.color;
        ctx.shadowBlur = 8;
        ctx.beginPath();
        ctx.arc(this.x, this.y, 2.5, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }
    get burst() {
        return this.traveled >= this.distance;
    }
}

export default {
    layout: null,
    props: ['code','station'],
    data() {
        return {
            cameraStream: null,
            hasScanned: false,
            ringState: 'idle',
            ringOutcome: 'success',
            ringDisplayProgress: 0,
            ringColor: '#dc3545',
            fireworksRunning: false,
            flashActive: false,
            floatingShapes: generateFloatingShapes(),
            scanning: false,
            lastScan: null,
        };
    },
    async mounted() {
        try {
            await this.initCamera();
        } catch (err) {
            console.error('Failed to start camera:', err);
        }
    },
    beforeUnmount() {
        this._ringSweeping = false;
        if (this.cameraStream) {
            this.cameraStream.getTracks().forEach(track => track.stop());
        }
    },
    methods: {
        async initCamera() {
            this.cameraStream = await navigator.mediaDevices.getUserMedia({ video: true });
            this.$refs.video.srcObject = this.cameraStream;
        },
        async captureScan() {
            if (this.ringState !== 'idle' || this.scanning) return;

            const video = this.$refs.video;
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            // Camera-style flash right at the instant of capture, so the trigger moment is unmistakable
            this.flashActive = true;
            setTimeout(() => {
                this.flashActive = false;
            }, 250);

            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
            const formData = new FormData();
            formData.append('image', blob, 'capture.jpg');

            this.scanning = true;
            this.ringColor = '#dc3545';
            this.ringState = 'running';
            this.startRingSweep();

            try {
                const res = await axios.post('/check', formData);
                this.lastScan = res.data.data;
                this.finishRingSweep('#34d399', 'success');
                this.hasScanned = true;
                this.launchFireworks();
                setTimeout(() => {
                    this.hasScanned = false;
                }, FIREWORKS_SHOW_DURATION);
            } catch (err) {
                console.error('Scan check failed:', err);
                this.lastScan = null;
                this.finishRingSweep('#dc3545', 'error');
            } finally {
                this.scanning = false;
            }
        },
        // Sweeps toward (but never quite reaches) full while /check is in flight, since the
        // real duration depends on the recognition request rather than a fixed animation length.
        startRingSweep() {
            this._ringSweeping = true;
            this.ringDisplayProgress = 0;
            const start = performance.now();
            const plateau = 92;
            const rampDuration = 1600;

            const step = (now) => {
                if (!this._ringSweeping) return;
                this.ringDisplayProgress = Math.min(plateau, ((now - start) / rampDuration) * plateau);
                requestAnimationFrame(step);
            };

            requestAnimationFrame(step);
        },
        finishRingSweep(color, outcome) {
            this._ringSweeping = false;
            this.ringColor = color;
            this.ringOutcome = outcome;
            this.ringDisplayProgress = 100;
            this.ringState = 'success';

            setTimeout(() => {
                this.ringState = 'idle';
                this.ringDisplayProgress = 0;
            }, 2000);
        },
        // Both firework effects share one canvas/particle-array/animation-loop so they never
        // fight over clearRect - each spawner just pushes particles and nudges the loop awake.
        getFireworksCanvas() {
            const canvas = this.$refs.fireworksCanvas;
            if (!this._fireworksSized) {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                this._fireworksSized = true;
            }
            return canvas;
        },
        ensureFireworksLoop(canvas) {
            if (this._fireworksLoopRunning) return;
            this._fireworksLoopRunning = true;
            this._fireworksParticles = this._fireworksParticles || [];
            this._fireworksRockets = this._fireworksRockets || [];
            const ctx = canvas.getContext('2d');

            const animate = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                this._fireworksRockets.forEach(r => r.update());
                this._fireworksRockets.forEach(r => r.draw(ctx));

                // Any rocket that reached its target distance detonates into a
                // radial spark burst right where its trail ends.
                this._fireworksRockets.filter(r => r.burst).forEach(r => {
                    const sparkCount = randBetween(45, 70);
                    for (let i = 0; i < sparkCount; i++) {
                        this._fireworksParticles.push(new FireworkParticle(r.x, r.y, r.color, 1.5, 5));
                    }
                });
                this._fireworksRockets = this._fireworksRockets.filter(r => !r.burst);

                this._fireworksParticles.forEach(p => p.update());
                this._fireworksParticles.forEach(p => p.draw(ctx));
                this._fireworksParticles = this._fireworksParticles.filter(p => !p.dead);

                if (this._fireworksParticles.length > 0 || this._fireworksRockets.length > 0 || (this._fireworksActiveSpawns || 0) > 0) {
                    requestAnimationFrame(animate);
                } else {
                    this._fireworksLoopRunning = false;
                }
            };
            requestAnimationFrame(animate);
        },
        // Aerial-fireworks celebration on a successful scan: shells launch from the
        // camera ring toward random points scattered across the whole screen, each
        // trailing behind it, and burst into a spark explosion on arrival. Runs as
        // a ~10s show of staggered waves rather than one instant burst.
        launchFireworks() {
            if (this.fireworksRunning) return;
            this.fireworksRunning = true;

            const canvas = this.getFireworksCanvas();
            const stageRect = this.$refs.cameraStage.getBoundingClientRect();
            const centerX = stageRect.left + stageRect.width / 2;
            const centerY = stageRect.top + stageRect.height / 2;
            const launchRadius = Math.min(canvas.width, canvas.height) * 0.12;

            this._fireworksParticles = this._fireworksParticles || [];
            this._fireworksRockets = this._fireworksRockets || [];
            this._fireworksActiveSpawns = (this._fireworksActiveSpawns || 0) + 1;

            const launchRocket = () => {
                const color = FIREWORKS_PALETTE[Math.floor(randBetween(0, FIREWORKS_PALETTE.length))];
                // A random point anywhere on screen (with a small margin so bursts
                // don't detonate right at the edge) - this is what makes the show
                // cover the whole viewport instead of just the area around the ring.
                const targetX = randBetween(canvas.width * 0.06, canvas.width * 0.94);
                const targetY = randBetween(canvas.height * 0.08, canvas.height * 0.85);
                const angle = Math.atan2(targetY - centerY, targetX - centerX);
                const distance = Math.hypot(targetX - centerX, targetY - centerY);
                const sx = centerX + Math.cos(angle) * launchRadius;
                const sy = centerY + Math.sin(angle) * launchRadius;

                this._fireworksRockets.push(new FireworkRocket(sx, sy, angle, distance, color));
            };

            const launchWave = () => {
                const rocketCount = Math.floor(randBetween(4, 7));
                for (let i = 0; i < rocketCount; i++) {
                    setTimeout(launchRocket, i * randBetween(40, 120));
                }
            };

            this.ensureFireworksLoop(canvas);

            // Waves of shells spread out across a ~10s show
            const waveCount = 14;
            for (let i = 0; i < waveCount; i++) {
                setTimeout(launchWave, i * 650 + randBetween(0, 200));
            }

            setTimeout(() => {
                this._fireworksActiveSpawns = Math.max(0, this._fireworksActiveSpawns - 1);
                this.fireworksRunning = false;
            }, FIREWORKS_SHOW_DURATION);
        },
    }
}
</script>

<style scoped>
.camera-stage {
    width: 640px;
    height: 640px;
    z-index: 1;
}

.pillars-page-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
}

.pillar-page-quadrant {
    filter: grayscale(1);
    transition: filter 0.8s ease;
}

.pillar-page-quadrant.is-colored {
    filter: grayscale(0);
}

.pillars-bg {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) translateZ(0);
    width: 950px;
    height: 950px;
    z-index: 0;
    pointer-events: none;
    will-change: opacity;
    isolation: isolate;
}

.fireworks-canvas {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    pointer-events: none;
}

.pillar-quadrant,
.pillar-core {
    filter: grayscale(1);
    opacity: 0.45;
    transition: filter 0.8s ease, opacity 0.8s ease;
}

.pillar-quadrant.is-colored,
.pillar-core.is-colored {
    filter: grayscale(0);
    opacity: 1;
}

.camera-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 274px;
    height: 274px;
    border-radius: 50%;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: conic-gradient(#e9ecef 0% 100%);
}

.camera-ring.ring-running {
    background: conic-gradient(var(--ring-color, #dc3545) 0% var(--progress, 0%), #e9ecef var(--progress, 0%) 100%);
}

.camera-ring.ring-success {
    background: conic-gradient(var(--ring-color, #34d399) 0% 100%);
}

.camera-circle {
    position: relative;
    width: 250px;
    height: 250px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #ffffff33;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.camera-circle-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: scaleX(-1);
}

.capture-success {
    position: absolute;
    inset: 0;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(52, 211, 153, 0.8);
    animation: capture-success-pop 0.3s ease;
}

.capture-success.is-error {
    background-color: rgba(220, 53, 69, 0.8);
}

.capture-success i {
    color: #ffffff;
    font-size: 4rem;
}

@keyframes capture-success-pop {
    from {
        opacity: 0;
        transform: scale(0.6);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.capture-flash {
    position: absolute;
    inset: 0;
    z-index: 4;
    background-color: #ffffff;
    animation: capture-flash-fade 0.25s ease-out forwards;
    pointer-events: none;
}

@keyframes capture-flash-fade {
    from {
        opacity: 0.9;
    }
    to {
        opacity: 0;
    }
}

.btn-scan-trigger {
    position: absolute;
    bottom: 12%;
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
    z-index: 3;
    cursor: pointer;
    transition: opacity 0.2s ease, transform 0.15s ease;
}

.btn-scan-trigger:not(:disabled):active {
    transform: translateX(-50%) scale(0.92);
}

.btn-scan-trigger:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

</style>
