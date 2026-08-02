<template>
    <Head title="Profile" />
    <PageHeader title="Signatory Profile" pageTitle="User" />
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-light-subtle shadow-none border">
                
                <div class="card-body bg-white rounded-bottom rounded-top p-4" style="height: calc(100vh - 223px); overflow: auto;">
                   <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto mb-3">
                            <img v-if="selected.data.user" :src="selected.data.user.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                            <img v-else-if="selected.data.is_oic" :src="selected.data.oic.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                            <img v-else :src="selected.data.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="previewImage"/>
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 v-if="selected.data.user" class="fs-16 mb-1">{{ selected.data.user.name }}</h5>
                        <h5 v-else-if="selected.data.is_oic" class="fs-16 mb-1">{{ selected.data.oic.name }}</h5>
                        <h5 v-else class="fs-16 mb-1">Not Assigned</h5>
                        <p v-if="!selected.data.is_oic" class="text-primary fw-semibold mb-n1">{{ selected.data.designation }}</p>
                        <p v-else class="text-warning fw-semibold mb-n1">OIC - {{ selected.data.designation }}</p>
                        <p class="text-muted fs-12 mb-0">({{ selected.data.assigned.name }})</p>
                    </div>
                    <hr class="text-muted">
                   {{ selected }}
                </div>
            </div>
        </div>
      
        <div class="col-md-9">
        </div>
    </div>
</template>
<script>
import { useForm } from "@inertiajs/vue3"
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader },
    props: ['selected'],
    data() {
        return {
            currentUrl: window.location.origin,
            activeTab: 1, 
            form: useForm({
                image: null,
            }),
        };
    },
    methods: {
       
    }
}
</script>