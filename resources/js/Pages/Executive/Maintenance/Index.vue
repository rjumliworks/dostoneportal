<template>
    <Head title="Maintenance"/>
    <PageHeader title="Maintenance" pageTitle="Executive" />
    <BRow>
        <div class="col-md-12">
            <div v-if="alert" class="alert alert-dismissible fade show" :class="alert.success ? 'alert-success' : 'alert-danger'" role="alert">
                {{ alert.message }}
                <button type="button" class="btn-close" @click="alert = null"></button>
            </div>
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-settings-4-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">System Maintenance</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">System tools, database backups, configuration, and storage details.</p>
                        </div>
                    </div>
                </div>
                <div class="card bg-white border-bottom shadow-none" no-body>
                    <ul class="nav nav-tabs nav-tabs-custom nav-primary fs-12" role="tablist">
                        <li class="nav-item">
                            <BLink @click="tab = 'system'" class="nav-link py-3" :class="{ 'active': tab === 'system' }" data-bs-toggle="tab" role="tab">
                                <i class="ri-information-line me-1 align-bottom"></i> System Info
                            </BLink>
                        </li>
                        <li class="nav-item">
                            <BLink @click="tab = 'storage'" class="nav-link py-3" :class="{ 'active': tab === 'storage' }" data-bs-toggle="tab" role="tab">
                                <i class="ri-hard-drive-2-line me-1 align-bottom"></i> Storage
                            </BLink>
                        </li>
                        <li class="nav-item">
                            <BLink @click="tab = 'backups'" class="nav-link py-3" :class="{ 'active': tab === 'backups' }" data-bs-toggle="tab" role="tab">
                                <i class="ri-database-2-line me-1 align-bottom"></i> Backups
                                <BBadge v-if="backupsList.length > 0" class="align-middle ms-1 bg-primary-subtle text-primary">{{ backupsList.length }}</BBadge>
                            </BLink>
                        </li>
                        <li class="nav-item">
                            <BLink @click="tab = 'tools'" class="nav-link py-3" :class="{ 'active': tab === 'tools' }" data-bs-toggle="tab" role="tab">
                                <i class="ri-tools-fill me-1 align-bottom"></i> System Tools
                            </BLink>
                        </li>
                    </ul>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <SystemInfo v-if="tab === 'system'" :info="info" :scheduled-tasks="scheduledTasks" />
                    <Storage v-if="tab === 'storage'" :info="storage" @refresh="fetchStorage()" />
                    <Backups v-if="tab === 'backups'" :lists="backupsList" @refresh="fetchBackups()" @message="showAlert" />
                    <Tools v-if="tab === 'tools'" :info="info" @message="showAlert" />
                </div>
            </div>
        </div>
    </BRow>
</template>
<script>
import PageHeader from '@/Shared/Components/PageHeader.vue';
import SystemInfo from './Components/SystemInfo.vue';
import Storage from './Components/Storage.vue';
import Backups from './Components/Backups.vue';
import Tools from './Components/Tools.vue';

export default {
    components: { PageHeader, SystemInfo, Storage, Backups, Tools },
    props: ['systemInfo', 'storageInfo', 'backups', 'scheduledTasks'],
    data() {
        return {
            tab: 'system',
            alert: null,
            info: this.systemInfo,
            storage: this.storageInfo,
            backupsList: this.backups
        }
    },
    methods: {
        showAlert(payload) {
            this.alert = payload;
        },
        fetchStorage() {
            axios.get('/system-maintenance', { params: { option: 'storage-info' } })
                .then(response => this.storage = response.data)
                .catch(() => {});
        },
        fetchBackups() {
            axios.get('/system-maintenance', { params: { option: 'backups' } })
                .then(response => this.backupsList = response.data)
                .catch(() => {});
        }
    }
}
</script>
