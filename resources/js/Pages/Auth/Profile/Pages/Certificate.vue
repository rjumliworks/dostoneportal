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
            <div class="border bg-white rounded border-dashed p-1 mb-3">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-file-text-fill"></i></div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-12">Philippine National Public Key Infrastructure (PNPKI) :</p>
                        <h5 class="mb-0 fs-12">sda</h5>
                    </div>
                    <div class="flex-shrink-0 ms-2">
                        <div class="d-flex gap-1">
                            <button type="button" @click="openPdf" class="btn btn-icon text-muted btn-sm fs-18"><i class="ri-download-2-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border bg-white rounded border-dashed p-1 mb-3">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm me-2">
                        <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-lock-2-fill"></i></div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-0 fs-12">Password :</p>
                        <h5 class="mb-0 fs-12">sda</h5>
                    </div>
                    <div class="flex-shrink-0 ms-2">
                        <div class="d-flex gap-1">
                            <button type="button" @click="openPdf" class="btn btn-icon text-muted btn-sm fs-18"><i class="ri-eye-line"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <file-pond
            name="p12file"
            ref="pond"
            allow-multiple="false"
            max-files="1"
            accepted-file-types="application/x-pkcs12"
            label-idle='Drag & Drop your P12 file or <span class="filepond--label-action">Browse</span>'
            :allow-process="false"
            @addfile="handleAddFile"
            />
            <file-pond
            name="signatureFile"
            ref="pond"
            allow-multiple="false"
            max-files="1"
            accepted-file-types="image/png"
            label-idle='Drag & Drop your signature PNG or <span class="filepond--label-action">Browse</span>'
            :allow-process="false"
            @addfile="handleSignatureFile"
        />
        </div>
    </div>
</div>
</template>
<script>
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
const FilePond = vueFilePond(FilePondPluginFileValidateType);
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { InputLabel, TextInput, FilePond },
    data(){
        return {
            form: useForm({
                id: this.$page.props.user.data.id,
                username: this.$page.props.user.data.username,
                email: this.$page.props.user.data.email,
                firstname: this.$page.props.user.data.firstname,
                middlename: this.$page.props.user.data.middlename,
                lastname: this.$page.props.user.data.lastname,
                suffix: this.$page.props.user.data.suffix,
                sex: this.$page.props.user.data.sex,
                mobile: this.$page.props.user.data.mobile,
            }),
        }
    },
    methods: {
        handleSignatureFile(error, fileItem) {
            if (error) return console.error('FilePond error:', error);

            const file = fileItem.file;
            const formData = new FormData();
            formData.append('signature', file);
            formData.append('option', 'signature');

            this.$inertia.post('/profile', formData, {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: (page) => {
                    console.log(page);
                },
                onError: () => this.errors = this.$page.props.errors
            });
        },
        handleAddFile(error, fileItem) {
            if (error) return console.error('FilePond error:', error);

            const file = fileItem.file;
            const formData = new FormData();
            formData.append('p12', file);
            formData.append('option', 'certificate');

            this.$inertia.post('/profile', formData, {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: (page) => {
                    // Update selected.attachment with new data from backend
                
                    if (page.props.flash) {
                        this.selected.attachment = page.props.flash.data;
                    }

                    // Cache busting
                    this.pdfUrl = `/storage/uploads/testreports/${this.selected.attachment.name}?v=${Date.now()}`;

                    // Render updated PDF
                    setTimeout(() => {
                        this.renderPdf(this.currentPage);
                    }, 300);
                },
                onError: () => this.errors = this.$page.props.errors
            });
        },
        submit(){
            this.form.put('/profile/updated', {
                errorBag: "submit",
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {},
            });
        }
    }
}
</script>