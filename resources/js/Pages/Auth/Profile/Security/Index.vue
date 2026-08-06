<template>
    <Head title="Account Security" />
    <PageHeader title="Account Security" pageTitle="User" />
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-body bg-white rounded-bottom rounded-top p-4" style="height: calc(100vh - 223px); overflow: auto;">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto mb-3">
                            <img :src="$page.props.user.data.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                        </div>
                        <h5 class="fs-16 mb-0">{{ $page.props.user.data.name }}</h5>
                        <p class="text-muted mb-0">{{ $page.props.user.data.position }}</p>
                    </div>
                    <hr class="text-muted">
                    <b-list-group class="list-group-fill-success mt-4">
                        <BListGroupItem :active="activeTab === 1" href="#" class="list-group-item-action" @click="show(1)">
                            <i class="ri-shield-check-line align-middle me-2"></i>Password & Security
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 2" href="#" class="list-group-item-action" @click="show(2)">
                            <i class="ri-shield-keyhole-fill align-middle me-2"></i>Authentication History
                        </BListGroupItem>
                        <BListGroupItem :active="activeTab === 3" href="#" class="list-group-item-action" @click="show(3)">
                            <i class="ri-history-line align-middle me-2"></i>Activity History
                        </BListGroupItem>
                    </b-list-group>
                    <hr class="text-muted">
                    <Link href="/profile" class="btn btn-soft-secondary w-100">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to Profile
                    </Link>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <Security v-if="activeTab === 1"/>
            <AuthenticationLog v-if="activeTab === 2"/>
            <ActivityLog v-if="activeTab === 3"/>
        </div>
    </div>
</template>
<script>
import { Link } from "@inertiajs/vue3"
import Security from '../Pages/Security.vue';
import ActivityLog from '../Pages/ActivityLog.vue';
import AuthenticationLog from "../Pages/AuthenticationLog.vue";
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { Link, PageHeader, AuthenticationLog, ActivityLog, Security },
    data() {
        return {
            activeTab: 1,
        };
    },
    methods: {
        show(tab){
            this.activeTab = tab;
        },
    }
}
</script>
