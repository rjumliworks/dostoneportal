<template>
    <div class="card border shadow-none">
        <div class="card-header bg-light-subtle d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0 fs-14">Database Backups</h5>
                <p class="text-muted fs-12 mb-0">Compressed mysqldump snapshots stored in storage/app/backups.</p>
            </div>
            <b-button variant="primary" size="sm" :disabled="running" @click="runBackup()">
                <i class="ri-database-2-line align-bottom me-1"></i>
                {{ running ? 'Running…' : 'Backup Now' }}
            </b-button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 fs-13">
                    <thead class="table-light">
                        <tr>
                            <th>File</th>
                            <th>Size</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="lists.length === 0">
                            <td colspan="4" class="text-center py-4 text-muted">No backups found yet.</td>
                        </tr>
                        <tr v-for="(backup, index) in lists" :key="index">
                            <td><i class="ri-file-zip-line me-1 text-muted"></i>{{ backup.name }}</td>
                            <td>{{ formatBytes(backup.size) }}</td>
                            <td>{{ backup.created_at }}</td>
                            <td class="text-end">
                                <a :href="`/system-maintenance/backups/${backup.name}/download`" class="btn btn-soft-info btn-sm me-1" v-b-tooltip.hover title="Download">
                                    <i class="ri-download-2-line align-bottom"></i>
                                </a>
                                <b-button variant="soft-danger" size="sm" v-b-tooltip.hover title="Delete" @click="confirmDelete(backup.name)">
                                    <i class="ri-delete-bin-5-line align-bottom"></i>
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <b-modal v-model="showConfirm" header-class="p-3 bg-light" title="Delete Backup" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
            <div class="p-2">
                <div class="alert alert-danger alert-dismissible alert-additional fade show mb-xl-0 material-shadow" role="alert">
                    <div class="alert-body">
                        <div class="d-flex mt-n1 mb-n2">
                            <div class="flex-shrink-0 me-2">
                                <i class="ri-alert-line fs-14 align-middle"></i>
                            </div>
                            <div class="flex-grow-1 mt-1">
                                <h5 class="fs-13 alert-heading">Delete "{{ pendingDelete }}"?</h5>
                            </div>
                        </div>
                    </div>
                    <div class="alert-content">
                        <p class="mb-0 fs-10">This will permanently remove the backup file. This action cannot be undone.</p>
                    </div>
                </div>
            </div>
            <template v-slot:footer>
                <b-button @click="showConfirm = false" variant="light" block>Cancel</b-button>
                <b-button @click="deleteBackup()" variant="danger" :disabled="deleting" block>Delete</b-button>
            </template>
        </b-modal>
    </div>
</template>
<script>
export default {
    props: {
        lists: { type: Array, default: () => [] }
    },
    emits: ['refresh', 'message'],
    data() {
        return {
            running: false,
            deleting: false,
            showConfirm: false,
            pendingDelete: null
        }
    },
    methods: {
        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            const exponent = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
            const value = bytes / Math.pow(1024, exponent);
            return `${value.toFixed(exponent === 0 ? 0 : 2)} ${units[exponent]}`;
        },
        runBackup() {
            this.running = true;
            axios.post('/system-maintenance/backups')
                .then(response => {
                    this.$emit('message', response.data);
                    this.$emit('refresh');
                })
                .catch(() => {
                    this.$emit('message', { success: false, message: 'Backup request failed.' });
                })
                .finally(() => {
                    this.running = false;
                });
        },
        confirmDelete(name) {
            this.pendingDelete = name;
            this.showConfirm = true;
        },
        deleteBackup() {
            this.deleting = true;
            axios.delete('/system-maintenance/backups', { data: { filename: this.pendingDelete } })
                .then(response => {
                    this.$emit('message', response.data);
                    this.$emit('refresh');
                    this.showConfirm = false;
                })
                .catch(() => {
                    this.$emit('message', { success: false, message: 'Failed to delete backup.' });
                })
                .finally(() => {
                    this.deleting = false;
                });
        }
    }
}
</script>
