<template>
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-image-line text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Media Files</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">A list of all your uploaded images and videos.</p>
            </div>
            <div class="flex-shrink-0 text-end">
                <div class="list-grid-nav hstack gap-1">
                    <button type="button" id="grid-view-button" class="btn btn-soft-info nav-link btn-icon fs-14 filter-button material-shadow-none"><i class="ri-grid-fill"></i></button>
                    <button type="button" id="list-view-button" class="btn btn-soft-info nav-link btn-icon fs-14 filter-button material-shadow-none"><i class="ri-list-unordered"></i></button>
                    
                    <button
                        @click="triggerFileInput"
                        class="btn btn-primary"><i class="ri-add-fill me-1 align-bottom"></i>Upload</button>
                    <input
                    ref="fileInput"
                    type="file"
                    accept="image/*,video/*"
                    multiple
                    style="display: none;" 
                    @change="handleFileChange"
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="card bg-light-subtle rounded-bottom shadow-none mb-0" style="height: calc(100vh - 497px); overflow-x: hidden;">
        <div class="row row-cols-xxl-5 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 p-3 mt-2">
            <div class="col list-element" v-for="(list,index) in lists" v-bind:key="index">
                <div class="card explore-box card-animate">
                    <a class="glightbox" :href="list.signed_url">
                        <div class="explore-place-bid-img overflow-hidden rounded"> 
                            <img :src="list.signed_url" alt="" class="card-img-top explore-img">
                            <div class="bg-overlay"></div>
                        </div>
                    </a>
                  
                    <div class="card-body">
                        <div class="dropdown float-end mt-n2 mb-n2 me-n1" data-bs-container="body" data-bs-display="static">
              
                            <BDropdown variant="link" toggle-class="btn btn-light btn-sm dropdown" no-caret menu-class="dropdown-menu-end" :offset="{ alignmentAxis: -130, crossAxis: 0, mainAxis: 10 }"> 
                                <template #button-content> 
                                    <i class="ri-more-2-fill fs-16"></i>
                                </template>
                                <li>
                                    <a :href="list.signed_url" class="glightbox dropdown-item d-flex align-items-center">
                                        <i class="ri-eye-line me-2"></i> Preview
                                    </a>
                                </li>
                                <li>
                                    <a @click="openDetail(list,index)" class="dropdown-item d-flex align-items-center" role="button">
                                        <i class="ri-information-line me-2"></i> Details
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                    <li>
                                    <a @click="openRename(list,index)" class="dropdown-item d-flex align-items-center" role="button">
                                        <i class="ri-edit-2-line me-2"></i> Rename
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a @click="openDelete(list,index)" class="dropdown-item d-flex align-items-center text-danger" href="#removeFileItemModal" data-id="1" data-bs-toggle="modal" role="button">
                                        <i class="ri-delete-bin-6-line me-2"></i> Move to trash
                                    </a>
                                </li>
                            </BDropdown>
                        </div>
                        <h5 class="mb-0 mt-n2 fs-12 text-truncate text-primary">{{ list.name }}</h5>
                        <p class="text-muted fs-10 mb-n2">{{ formatSize(list.size) }}</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <Rename ref="rename"/>
    <Delete @message="fetch()" ref="delete"/>
    <Detail ref="detail"/>
</template>
<script>
import Detail from './Modals/Detail.vue';
import Rename from './Modals/Rename.vue';
import Delete from './Modals/Delete.vue';
import GLightbox from "glightbox";
import "glightbox/dist/css/glightbox.min.css";
export default {
    props: ['code'],
    components: { Detail, Rename, Delete },
    data(){
        return {
            lists: [],
            index: null
        }
    },
     mounted() {
        GLightbox({
            selector: ".glightbox",
            touchNavigation: true,
            loop: true,
            zoomable: true,
        });
    },
    created(){
        this.fetch();
    },
    methods : {
        fetch(page_url){
            page_url = page_url || '/visitors';
            return axios.get(page_url,{
                params : {
                    code: this.code,
                    option: 'files'
                }
            })
            .then(response => {
                this.lists = response.data.data;  
                this.$nextTick(() => {
                // Re-initialize GLightbox
                GLightbox({
                    selector: ".glightbox",
                    touchNavigation: true,
                    loop: true,
                    zoomable: true,
                });
            });  
            });
        },
        openRename(list,index){
            this.$refs.rename.show(list);
            this.index = index;
        },
        openDelete(list,index){
            this.$refs.delete.show(list,this.code);
            this.index = index;
        },
        openDetail(list,index){
            this.$refs.detail.show(list);
            this.index = index;
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        handleFileChange(e) {
            const selectedFiles = Array.from(e.target.files);

            selectedFiles.forEach((file, index) => {
                const clientId = Date.now() + Math.random();
                let preview = file.type.startsWith("image/") ? URL.createObjectURL(file) : null;

                // // Immediately show in Upload Manager
                // window.dispatchEvent(new CustomEvent("new-upload", {
                //     detail: { clientId, name: file.name, size: file.size, status: "Queued", progress: 0, folderId: this.folder.id, preview }
                // }));

                const formData = new FormData();
                formData.append('code', this.code);
                formData.append('option', 'file');
                formData.append('file', file);

                axios.post('/visitors', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                    onUploadProgress: (event) => {
                        window.dispatchEvent(new CustomEvent("upload-progress", {
                            detail: { clientId, progress: Math.round((event.loaded / event.total) * 100) }
                        }));
                        window.dispatchEvent(new CustomEvent("upload-status", {
                            detail: { clientId, status: "Processing" }
                        }));
                    }
                }).then(response => {
                    const staggerDelay = Math.random() * 2000; 
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent("upload-status", {
                            detail: { clientId, status: "Completed" }
                        }));
                    }, staggerDelay);
                    this.folder.files.unshift(response.data.file);
                    this.$emit('size',response.data.file.size);
                }).catch(response => {
                    window.dispatchEvent(new CustomEvent("upload-status", {
                        detail: { clientId, status: "Failed" }
                    }));
                    console.log(response.status);
                });
            });

            e.target.value = "";
        },
        formatSize(bytes) {
            if (bytes < 1024) return bytes + " B"
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + " KB"
            return (bytes / (1024 * 1024)).toFixed(1) + " MB"
        }
    }
}
</script>