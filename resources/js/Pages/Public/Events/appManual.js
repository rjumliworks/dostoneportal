

/**
 * appManual.js — single source of truth for the RSTW 2026 app user manual.
 *
 * Consumed by:
 *   - Pages/Rstw.vue      (interactive tabs in the Mobile App section)
 *   - Pages/Manual.vue    (printable / downloadable book edition)
 *
 * Keeping this in one module means the on-page guide and the PDF can never
 * drift apart — edit the steps once and both surfaces update.
 */

export const MANUAL_META = {
    title: 'RSTW 2026 Mobile App',
    subtitle: 'User Manual',
    edition: 'First edition',
    event: 'Regional Science, Technology, and Innovation Week',
    venue: 'Zamboanga del Norte Convention and Sports Center, Dipolog City',
    dates: 'August 12–14, 2026',
    publisher: 'DOST Regional Office No. IX',
    contact: 'ord@ro9.dost.gov.ph · (062) 991-1024',
};

/** Organiser contact details, shown on the manual's facing page. */
export const CONTACTS = [
    { icon: 'ri-building-4-line', label: 'Office', value: 'DOST Regional Office No. IX' },
    { icon: 'ri-map-pin-line', label: 'Address', value: 'Pettit Barracks, Zamboanga City, Philippines' },
    { icon: 'ri-phone-line', label: 'Telephone', value: '(062) 991-1024' },
    { icon: 'ri-mail-line', label: 'Email', value: 'ord@ro9.dost.gov.ph' },
    { icon: 'ri-global-line', label: 'Website', value: 'region9.dost.gov.ph' },
];

export const GUIDES = [
    {
        id: 'register',
        icon: 'ri-user-add-line',
        title: 'Quick & easy registration',
        time: 'about 3 minutes',
        intro: 'Pre-register before the event so your slot is reserved and your check-in photo is on file.',
        steps: [
            { title: 'Open the app and tap Register', text: 'Launch the RSTW 2026 app and choose "Register" on the welcome screen.' },
            { title: 'Fill in your details', text: 'Enter your name, email address, contact number, birth date, sex, designation, and affiliation.' },
            { title: 'Take your photo', text: 'Allow camera access, then capture a clear, well-lit photo of your face. This is what check-in will match against.' },
            { title: 'Sign and verify', text: 'Draw your signature, complete the security check, and accept the terms.' },
            { title: 'Login and Confirm your email', text: 'Sign in to the app with the email and password you registered with, then check that inbox, copy the verification code we sent, and enter it in the app to activate your account.' },
        ],
        tip: 'Register before you travel — on-site sign-ups take longer during peak hours.',
    },
    {
        id: 'checkin',
        icon: 'ri-user-received-line',
        title: 'Your face is your pass',
        time: 'under 10 seconds',
        intro: 'Your registration photo lets the entrance kiosk verify you without queuing at a desk.',
        steps: [
            { title: 'Head to the entrance kiosk', text: 'Look for the check-in counters at the venue entrance.' },
            { title: 'Face the camera', text: 'Stand within the marked area and look straight at the screen. Remove hats or sunglasses.' },
            { title: 'You are in', text: 'The kiosk matches your face to your registration photo, shows a green confirmation, and records your attendance — no badge or queue needed.' },
        ],
        tip: 'No signal? Check-in works offline — staff can also scan the QR code in your app.',
    },
    {
        id: 'sessions',
        icon: 'ri-calendar-check-line',
        title: 'Save your seat',
        time: 'about 30 seconds',
        intro: 'Browse the three-day programme in the app and reserve a place in the sessions you want to attend.',
        steps: [
            { title: 'Tap Sessions', text: 'Open the "Sessions" tab to see the full programme for all three days.' },
            { title: 'Find the session you want', text: 'Filter by day or topic, then tap a session to read its description, speaker, room, and schedule.' },
            { title: 'Tap Register', text: 'Choose "Register" on the session you want to attend. Seats are limited, so popular sessions fill early.' },
            { title: 'Check My Sessions', text: 'Your reserved sessions are listed under "My Sessions" with their times and rooms, ready for check-in on the day.' },
        ],
        tip: 'Register for sessions ahead of time — walk-in seats depend on remaining capacity.',
    },
    {
        id: 'survey',
        icon: 'ri-survey-line',
        title: 'Share feedback with the CSF survey',
        time: 'about 2 minutes',
        intro: 'The Client Satisfaction Feedback survey helps organisers improve future events.',
        steps: [
            { title: 'Open the survey prompt', text: 'After a session ends, the Client Satisfaction Feedback form appears in your notifications.' },
            { title: 'Rate your experience', text: 'Answer the rating questions about the session, venue, and organisers.' },
            { title: 'Add your comments', text: 'Leave suggestions in the open field — this is optional but genuinely read.' },
            { title: 'Submit', text: 'Tap "Submit". Completed surveys earn points toward your rewards total.' },
        ],
        tip: 'You can revisit any pending survey later from the "Feedback" tab.',
    },
    {
        id: 'points',
        icon: 'ri-medal-line',
        title: 'Earn points & rewards',
        time: 'ongoing',
        intro: 'Taking part across the three days builds a points balance you can redeem on site.',
        steps: [
            { title: 'Collect points as you go', text: 'Checking in, joining sessions, visiting exhibits, and completing surveys all add points.' },
            { title: 'Track your total', text: 'Open the "Points" tab to see your running balance and what earned it.' },
            { title: 'Claim at the rewards booth', text: 'Show your in-app QR code at the booth to redeem what you have earned.' },
        ],
        tip: 'Points reset at the end of the event, so redeem before you head home on the last day.',
    },
];

/** General questions about the app and the event. */
export const QUESTIONS = [
    { q: 'Do I need the app to attend?', a: 'Yes. The app is what you use throughout the event — it carries your QR code for check-in, lets you register for sessions, delivers the CSF survey, and tracks the points you earn. Install it and complete your registration before you travel so you are ready on day one.' },
    { q: 'Is there a registration fee?', a: 'No. Admission to RSTW 2026 is free. Pre-registering only reserves your slot and speeds up entry.' },
    { q: 'Is the app available on iPhone?', a: 'Yes. The RSTW 2026 app is available for both iPhone and Android — download it from the App Store or Google Play.' },
    { q: 'Can I register more than one person?', a: 'Each attendee needs their own registration, because check-in matches the photo and QR code to one person.' },
    { q: 'What should I bring on the day?', a: 'Your phone with the app installed, or a printed copy of your QR code. A valid ID is helpful if staff need to verify your details.' },
    { q: 'How is my personal information used?', a: 'Your details are used for event registration, check-in, and attendance records only. See the privacy policy on the event website for the full statement.' },
];

/** Troubleshooting appendix. */
export const FAQS = [
    { q: 'The camera will not open during registration.', a: 'Check that the app has camera permission in your phone settings, then close and reopen the app. If it still fails, register through the website instead and your photo can be taken at the venue.' },
    { q: 'Face check-in does not recognise me.', a: 'Strong backlighting, hats, and sunglasses are the usual causes. Step into the marked area, face the screen directly, and try again. Staff can check you in with your QR code if it still will not match.' },
    { q: 'I did not receive the verification code.', a: 'Look in your spam or promotions folder first. If it is still missing after a few minutes, request a new code from the app, or contact the organisers at the address below.' },
    { q: 'I cannot register for a session.', a: 'The session may already be full, or registration for it may have closed. Check the Sessions tab for remaining capacity, and ask the facilitator at the room whether walk-in seats are available.' },
    { q: 'My points total looks wrong.', a: 'Points can take a few minutes to appear after an activity. Pull down to refresh the Points tab. If something is still missing, visit the rewards booth with your QR code.' },
];

