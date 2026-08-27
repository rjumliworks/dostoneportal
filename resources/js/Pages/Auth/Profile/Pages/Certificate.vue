<template>
<div class="card bg-light-subtle shadow-none border">
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-alarm-fill text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Digital Certificate</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">Philippine National Public Key Infrastructure (PNPKI)</p>
            </div>
            <div class="flex-shrink-0" style="width: 45%;">

            </div>
        </div>
    </div>


    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 291px); overflow: auto;">
        <div class="card bg-light-subtle border-1 rounded-bottom shadow-none mb-0 p-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="border bg-white rounded border-dashed p-2 mb-3 h-100">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0" style="height:2.5rem;width:2.5rem;">
                                    <div class="avatar-title h-100 w-100 rounded fs-20" :class="certificate?.has_p12 ? 'bg-success-subtle text-success' : 'bg-light text-muted'">
                                        <i :class="certificate?.has_p12 ? 'ri-checkbox-circle-fill' : 'ri-upload-2-line'"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="text-muted mb-0 fs-12">Philippine National Public Key Infrastructure (PNPKI) :</p>
                                    <h5 class="mb-0 fs-12">{{ certificate?.has_p12 ? 'Certificate uploaded' : 'No certificate uploaded yet' }}</h5>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <input type="file" ref="p12Input" accept=".p12,application/x-pkcs12" class="d-none" @change="onP12Selected" />
                                <button type="button" class="btn btn-sm btn-outline-primary" :disabled="p12Uploading" @click="$refs.p12Input.click()">
                                    <i class="ri-upload-2-line align-bottom me-1"></i> {{ certificate?.has_p12 ? 'Replace' : 'Upload' }}
                                </button>
                            </div>
                        </div>
                        <div class="rounded border d-flex align-items-center justify-content-center overflow-hidden bg-light-subtle" style="height:8rem;width:100%;">
                            <i style="font-size: 50px;" :class="certificate?.has_p12 ? ' ri-shield-keyhole-fill  text-muted' : 'ri-shield-keyhole-line text-muted'"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border bg-white rounded border-dashed p-2 mb-3 h-100">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0" style="height:2.5rem;width:2.5rem;">
                                    <div class="avatar-title h-100 w-100 rounded fs-20" :class="certificate?.has_signature ? 'bg-success-subtle text-success' : 'bg-light text-muted'">
                                        <i :class="certificate?.has_signature ? 'ri-checkbox-circle-fill' : 'ri-upload-2-line'"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="text-muted mb-0 fs-12">E-Signature :</p>
                                    <h5 class="mb-0 fs-12">{{ certificate?.has_signature ? 'Signature uploaded' : 'No signature uploaded yet' }}</h5>
                                </div>
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" @click="$refs.signatureCrop.show()">
                                    <i class="ri-upload-2-line align-bottom me-1"></i> {{ certificate?.has_signature ? 'Replace' : 'Upload' }}
                                </button>
                            </div>
                        </div>
                        <div class="rounded border d-flex align-items-center justify-content-center overflow-hidden bg-light-subtle" style="height:8rem;width:100%;">
                            <img v-if="certificate?.signature_url" :src="certificate.signature_url" alt="Signature" style="max-height:100%;max-width:100%;object-fit:contain;" />
                            <i v-else class="ri-quill-pen-line text-muted fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border bg-white rounded border-dashed p-2 mb-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0" style="height:2.5rem;width:2.5rem;">
                        <div class="avatar-title h-100 w-100 rounded fs-20" :class="certificate?.is_checked ? 'bg-success-subtle text-success' : 'bg-light text-muted'">
                            <i class="ri-lock-2-fill"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <div class="input-group">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                v-model="passwordForm.password"
                                class="form-control"
                                :class="{ 'is-invalid': passwordForm.errors.password || passwordVerify.valid === false }"
                                :placeholder="certificate?.has_password ? 'Enter new password to change' : 'Enter p12 password'"
                                autocomplete="off"
                                :disabled="!certificate?.has_p12"
                            />
                            <span v-if="certificate?.has_p12" class="input-group-text bg-white" style="width:2.25rem;justify-content:center;">
                                <span v-if="passwordVerify.checking" class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                                <i v-else-if="passwordVerify.valid === true" class="ri-checkbox-circle-fill text-success"></i>
                                <i v-else-if="passwordVerify.valid === false" class="ri-close-circle-fill text-danger"></i>
                            </span>
                            <button type="button" class="btn btn-outline-secondary" @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                            </button>
                            <button type="button" class="btn btn-primary" :disabled="!passwordForm.password || passwordForm.processing" @click="savePassword">
                                <i class="ri-save-3-line"></i>
                            </button>
                        </div>
                        <InputError :message="passwordForm.errors.password" />
                        <div v-if="!certificate?.has_p12" class="text-muted fs-12 mt-1">
                            Upload a PNPKI certificate (.p12) first before setting the password.
                        </div>
                        <div v-else-if="passwordVerify.valid === true" class="text-success fs-12 mt-1">
                            <i class="ri-checkbox-circle-line align-bottom me-1"></i> Password matches the uploaded PNPKI certificate.
                        </div>
                        <div v-else-if="passwordVerify.valid === false" class="text-danger fs-12 mt-1">
                            <i class="ri-error-warning-line align-bottom me-1"></i> Password does not match the uploaded PNPKI certificate.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<SignatureCrop ref="signatureCrop" />
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Shared/Components/Forms/InputError.vue';
import SignatureCrop from '../Modals/SignatureCrop.vue';
export default {
    components: { InputError, SignatureCrop },
    data(){
        return {
            showPassword: false,
            p12Uploading: false,
            passwordForm: useForm({
                option: 'certificate_password',
                password: '',
            }),
            passwordVerify: {
                checking: false,
                valid: null,
            },
            passwordVerifyTimer: null,
            passwordVerifyToken: 0,
        }
    },
    computed: {
        certificate(){
            return this.$page.props.user.data.certificate;
        }
    },
    watch: {
        'passwordForm.password'(value){
            clearTimeout(this.passwordVerifyTimer);

            if (!this.certificate?.has_p12 || !value || value.length < 4) {
                this.passwordVerify.checking = false;
                this.passwordVerify.valid = null;
                return;
            }

            this.passwordVerify.checking = true;
            this.passwordVerifyTimer = setTimeout(() => this.verifyTypedPassword(value), 500);
        },
    },
    mounted(){
        this.checkStoredPassword();
    },
    methods: {
        checkStoredPassword(){
            if (!this.certificate?.has_p12 || !this.certificate?.has_password) return;

            axios.get('/profile', { params: { option: 'certificate-password-check' } })
                .then(({ data }) => {
                    if (this.passwordForm.password) return; // live typing check takes precedence
                    this.passwordVerify.valid = data.checked ? !!data.valid : null;
                })
                .catch(() => {});
        },
        verifyTypedPassword(password){
            const token = ++this.passwordVerifyToken;

            axios.post('/profile/certificate/verify-password', { password })
                .then(({ data }) => {
                    if (token !== this.passwordVerifyToken) return; // stale response
                    this.passwordVerify.checking = false;
                    this.passwordVerify.valid = data.checked ? !!data.valid : null;
                })
                .catch(() => {
                    if (token !== this.passwordVerifyToken) return;
                    this.passwordVerify.checking = false;
                    this.passwordVerify.valid = null;
                });
        },
        savePassword(){
            this.passwordForm.post('/profile', {
                preserveScroll: true,
                onSuccess: () => {
                    this.passwordForm.reset('password');
                    this.showPassword = false;
                    this.passwordVerify.valid = null;
                    this.checkStoredPassword();
                },
            });
        },
        onP12Selected(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('p12', file);
            formData.append('option', 'certificate');

            this.p12Uploading = true;
            this.$inertia.post('/profile', formData, {
                preserveScroll: true,
                forceFormData: true,
                onFinish: () => {
                    this.p12Uploading = false;
                    event.target.value = '';
                },
            });
        },
    }
}
</script>
