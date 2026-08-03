<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 520px;" header-class="p-3 bg-light" title="Create post" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div>
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0 me-2">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle fs-17">
                            <img class="rounded-circle header-profile-user" :src="$page.props.user.data.avatar" @error="setDefaultImage($event)" :alt="$page.props.user.data.username">
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fs-14">{{ $page.props.user.data.name }}</h6>
                    <span class="badge bg-light text-dark border rounded-pill fs-11 fw-medium px-2 py-1">
                        <i class="ri-group-fill align-middle me-1"></i>Friends
                    </span>
                </div>
            </div>

            <textarea
                v-model="content"
                class="form-control border-0 fs-16 mb-3 p-0"
                style="resize: none; box-shadow: none;"
                :placeholder="`What's on your mind, ${firstName}?`"
                rows="4"
                autofocus
            ></textarea>

            <div v-if="images.length" class="row g-2 mb-3">
                <div class="col-4" v-for="(img, idx) in images" :key="idx">
                    <div class="position-relative">
                        <img :src="img" class="img-fluid rounded w-100" style="height: 90px; object-fit: cover;">
                        <button type="button"
                            class="btn btn-light rounded-circle position-absolute top-0 end-0 m-1 p-0 d-flex align-items-center justify-content-center"
                            style="width: 20px; height: 20px;"
                            @click="removeImage(idx)">
                            <i class="ri-close-line fs-12"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="border rounded p-2 d-flex align-items-center justify-content-between">
                <span class="fs-13 fw-medium ms-1 text-muted">Add to your post</span>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-icon btn-sm rounded-circle" title="Photo/video" @click="triggerFile">
                        <i class="ri-image-2-fill text-success fs-18"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-sm rounded-circle" title="Coming soon" disabled>
                        <i class="ri-user-add-fill text-primary fs-18"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-sm rounded-circle" title="Coming soon" disabled>
                        <i class="ri-emotion-happy-fill text-warning fs-18"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-sm rounded-circle" title="Coming soon" disabled>
                        <i class="ri-map-pin-fill text-danger fs-18"></i>
                    </button>
                </div>
            </div>

            <input ref="fileInput" type="file" accept="image/*" multiple class="d-none" @change="onFilesSelected">
        </div>
        <template v-slot:footer>
            <b-button @click="submit" variant="primary" block :disabled="!canPost">Post</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            showModal: false,
            content: '',
            images: [],
        }
    },
    computed: {
        firstName(){
            return this.$page.props.user.data.name?.split(' ')[0] ?? '';
        },
        canPost(){
            return this.content.trim().length > 0 || this.images.length > 0;
        }
    },
    methods: {
        show(mode = null){
            this.content = '';
            this.images = [];
            this.showModal = true;
            if (mode === 'photo') {
                this.$nextTick(() => this.triggerFile());
            }
        },
        hide(){
            this.showModal = false;
        },
        triggerFile(){
            this.$refs.fileInput.click();
        },
        onFilesSelected(e){
            Array.from(e.target.files || []).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = () => this.images.push(reader.result);
                reader.readAsDataURL(file);
            });
            e.target.value = '';
        },
        removeImage(idx){
            this.images.splice(idx, 1);
        },
        submit(){
            if (!this.canPost) return;
            this.$emit('posted', {
                id: Date.now(),
                content: this.content.trim(),
                images: [...this.images],
            });
            this.hide();
        }
    }
}
</script>
