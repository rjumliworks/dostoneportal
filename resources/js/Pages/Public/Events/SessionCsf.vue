<template>
    <Head title="Customer Satisfaction Feedback"/>
    <div class="csf-page">
        <div class="csf-card">
            <div class="csf-header">
                <div class="csf-brand">
                    <img src="@assets/images/logos/logo-sm.png" alt="" class="csf-brand__logo">
                    <img src="@assets/images/logos/bagongpilipinas.png" alt="" class="csf-brand__logo">
                </div>
                <h1 class="csf-title">
                    <span class="csf-title__accent">Customer</span>
                    <span class="csf-title__plain">Satisfaction</span>
                    <span class="csf-title__accent2">Feedback</span>
                </h1>
                <p class="csf-subtitle mb-0">{{ session.title }}</p>
                <p class="csf-venue mb-0" v-if="session.venue?.establishment">{{ session.venue.establishment }}</p>
            </div>

            <div v-if="!submitted" class="csf-body">
                <p class="csf-intro">Please take a few moments to complete this satisfaction form</p>

                <div class="csf-legend">
                    <div class="csf-legend__item" v-for="scale in scales" :key="scale.value">
                        <i :class="scale.icon" :style="{ color: scale.color }"></i>
                        <span>{{ scale.label }}</span>
                    </div>
                </div>

                <div class="csf-fields">
                    <div class="csf-field" :class="{ 'has-error': form.errors.name }">
                        <label>Name</label>
                        <input type="text" v-model="form.name" placeholder="Your full name" @input="clearError('name')">
                        <span class="csf-field__error" v-if="form.errors.name">{{ form.errors.name }}</span>
                    </div>
                    <div class="csf-field" :class="{ 'has-error': form.errors.email }">
                        <label>Email</label>
                        <input type="email" v-model="form.email" placeholder="you@example.com" @input="clearError('email')">
                        <span class="csf-field__error" v-if="form.errors.email">{{ form.errors.email }}</span>
                    </div>
                    <div class="csf-field" :class="{ 'has-error': form.errors.affiliation }">
                        <label>Affiliation</label>
                        <input type="text" v-model="form.affiliation" placeholder="Office / Organization" @input="clearError('affiliation')">
                        <span class="csf-field__error" v-if="form.errors.affiliation">{{ form.errors.affiliation }}</span>
                    </div>
                    <div class="csf-field" :class="{ 'has-error': form.errors.designation }">
                        <label>Designation</label>
                        <input type="text" v-model="form.designation" placeholder="Your position" @input="clearError('designation')">
                        <span class="csf-field__error" v-if="form.errors.designation">{{ form.errors.designation }}</span>
                    </div>
                </div>

                <div class="csf-questions">
                    <div class="csf-question" v-for="question in session.questions" :key="question.id">
                        <p class="csf-question__text">{{ question.name }}</p>
                        <div class="csf-question__scale">
                            <button
                                type="button"
                                v-for="scale in scales"
                                :key="scale.value"
                                class="csf-scale-btn"
                                :class="{ 'is-active': answers[question.id] === scale.value }"
                                :style="answers[question.id] === scale.value ? { color: scale.color } : null"
                                @click="setAnswer(question.id, scale.value)"
                            >
                                <i :class="scale.icon"></i>
                            </button>
                        </div>
                        <span class="csf-field__error" v-if="form.errors.questions">{{ form.errors.questions }}</span>
                    </div>
                </div>

                <div class="csf-field">
                    <label>Comments, complaints or suggestions <span class="csf-field__hint">(optional)</span></label>
                    <textarea v-model="form.comment" rows="3" placeholder="Comments, complaints or suggestions"></textarea>
                </div>

                <button type="button" class="csf-submit" :disabled="form.processing" @click="submit">
                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-1"></span>
                    Submit
                </button>
            </div>

            <div v-else class="csf-thankyou">
                <i class="ri-checkbox-circle-fill"></i>
                <h4>Thank you!</h4>
                <p class="mb-0">Your feedback has been submitted successfully.</p>
            </div>
        </div>
    </div>
</template>

<script>
import { Head, useForm } from '@inertiajs/vue3';

const SCALES = [
    { value: 5, label: 'Strongly +', icon: 'ri-emotion-laugh-line', color: '#3D8149' },
    { value: 4, label: 'Agree', icon: 'ri-emotion-happy-line', color: '#E8940C' },
    { value: 3, label: 'Neutral', icon: 'ri-emotion-normal-line', color: '#6c757d' },
    { value: 2, label: 'Disagree', icon: 'ri-emotion-unhappy-line', color: '#E8940C' },
    { value: 1, label: 'Strongly -', icon: 'ri-emotion-sad-line', color: '#C4577B' },
];

export default {
    layout: null,
    components: { Head },
    props: ['session'],
    data() {
        return {
            scales: SCALES,
            answers: {},
            submitted: false,
            form: useForm({
                name: null,
                email: null,
                affiliation: null,
                designation: null,
                comment: null,
                questions: [],
            }),
        };
    },
    methods: {
        setAnswer(questionId, value) {
            this.answers = { ...this.answers, [questionId]: value };
            this.clearError('questions');
        },
        clearError(field) {
            if (this.form.errors[field]) this.form.clearErrors(field);
        },
        submit() {
            const unanswered = this.session.questions.filter(q => !this.answers[q.id]);
            if (unanswered.length) {
                this.form.setError('questions', 'Please rate every question before submitting.');
                return;
            }

            this.form.questions = this.session.questions.map(q => ({
                id: q.id,
                rating: this.answers[q.id],
            }));

            this.form.post(`/session/${this.session.key}/csf`, {
                preserveScroll: true,
                onSuccess: () => {
                    this.submitted = true;
                },
            });
        },
    },
};
</script>

<style scoped>
.csf-page {
    min-height: 100vh;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    background: #f1f3f5;
    padding: 1.5rem 0.75rem;
}

.csf-card {
    width: 100%;
    max-width: 520px;
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    padding: 1.5rem;
    box-sizing: border-box;
}

.csf-brand {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.csf-brand__logo {
    height: 2rem;
    width: auto;
}

.csf-title {
    text-align: center;
    font-size: 1.35rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.csf-title__accent { color: #274F79; }
.csf-title__plain { color: #212529; margin: 0 0.35rem; }
.csf-title__accent2 { color: #E8940C; }

.csf-subtitle {
    text-align: center;
    font-weight: 600;
    color: #274F79;
    font-size: 0.9rem;
}

.csf-venue {
    text-align: center;
    color: #6c757d;
    font-size: 0.8rem;
}

.csf-header {
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 1rem;
}

.csf-intro {
    text-align: center;
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 1rem;
}

.csf-legend {
    display: flex;
    justify-content: space-between;
    background: #f8f9fa;
    border-radius: 0.75rem;
    padding: 0.75rem 0.5rem;
    margin-bottom: 1.25rem;
}

.csf-legend__item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.65rem;
    color: #495057;
    flex: 1;
    text-align: center;
}

.csf-legend__item i {
    font-size: 1.4rem;
}

.csf-fields {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    margin-bottom: 1.25rem;
}

.csf-field label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.25rem;
}

.csf-field__hint {
    font-weight: 400;
    color: #adb5bd;
}

.csf-field input,
.csf-field textarea {
    width: 100%;
    border: 1px solid #ced4da;
    border-radius: 0.5rem;
    padding: 0.55rem 0.75rem;
    font-size: 0.9rem;
    box-sizing: border-box;
}

.csf-field input:focus,
.csf-field textarea:focus {
    outline: none;
    border-color: #274F79;
}

.csf-field.has-error input,
.csf-field.has-error textarea {
    border-color: #dc3545;
}

.csf-field__error {
    display: block;
    color: #dc3545;
    font-size: 0.72rem;
    margin-top: 0.2rem;
}

.csf-questions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.csf-question {
    background: #f8f9fa;
    border-radius: 0.75rem;
    padding: 1rem 0.75rem;
    text-align: center;
}

.csf-question__text {
    font-weight: 600;
    color: #274F79;
    font-size: 0.85rem;
    margin-bottom: 0.75rem;
}

.csf-question__scale {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
}

.csf-scale-btn {
    background: none;
    border: none;
    font-size: 1.6rem;
    color: #adb5bd;
    cursor: pointer;
    line-height: 1;
    padding: 0.15rem;
    transition: transform 0.1s ease;
}

.csf-scale-btn.is-active {
    transform: scale(1.15);
}

.csf-submit {
    width: 100%;
    background: #274F79;
    color: #ffffff;
    border: none;
    border-radius: 0.5rem;
    padding: 0.7rem;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
}

.csf-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.csf-thankyou {
    text-align: center;
    padding: 2rem 0.5rem;
}

.csf-thankyou i {
    font-size: 3rem;
    color: #3D8149;
    margin-bottom: 0.5rem;
    display: block;
}
</style>
