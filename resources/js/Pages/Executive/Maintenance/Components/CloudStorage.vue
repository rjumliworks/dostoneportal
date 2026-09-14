<template>
    <div class="card border shadow-none">
        <div class="card-header bg-light-subtle d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav aria-label="breadcrumb" class="mb-0">
                <ol class="breadcrumb mb-0 fs-13">
                    <li class="breadcrumb-item">
                        <BLink @click="load('')"><i class="ri-cloud-line align-bottom me-1"></i>Bucket Root</BLink>
                    </li>
                    <li class="breadcrumb-item" v-for="(crumb, index) in breadcrumb" :key="index"
                        :class="{ active: index === breadcrumb.length - 1 }">
                        <BLink v-if="index !== breadcrumb.length - 1" @click="load(crumb.prefix)">{{ crumb.name }}</BLink>
                        <span v-else>{{ crumb.name }}</span>
                    </li>
                </ol>
            </nav>
            <div class="d-flex gap-2">
                <b-button variant="soft-secondary" size="sm" :disabled="loading" @click="load(prefix)">
                    <i class="bx bx-refresh align-bottom me-1"></i>Refresh
                </b-button>
                <b-button v-if="prefix" variant="primary" size="sm" :disabled="downloadingFolder" @click="downloadFolder()">
                    <i class="ri-folder-zip-line align-bottom me-1"></i>
                    {{ downloadingFolder ? 'Preparing…' : 'Download Folder (.zip)' }}
                </b-button>
            </div>
        </div>
        <div class="card-body p-0">
            <div v-if="loading" class="text-center py-5 text-muted">
                <i class="ri-loader-4-line fs-24 align-bottom"></i> Loading…
            </div>
            <div v-else class="table-responsive">
                <table class="table align-middle mb-0 fs-13">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th class="text-end">Size</th>
                            <th class="text-end">Last Modified</th>
                            <th class="text-end" style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="folders.length === 0 && files.length === 0">
                            <td colspan="4" class="text-center py-4 text-muted">This folder is empty.</td>
                        </tr>
                        <tr v-for="(folder, index) in folders" :key="'f' + index" style="cursor: pointer;" @click="load(folder.prefix)">
                            <td><i class="ri-folder-fill me-2 text-warning"></i>{{ folder.name }}</td>
                            <td class="text-end text-muted">—</td>
                            <td class="text-end text-muted">—</td>
                            <td class="text-end">
                                <i class="ri-arrow-right-s-line"></i>
                            </td>
                        </tr>
                        <tr v-for="(file, index) in files" :key="'file' + index">
                            <td><i class="ri-file-line me-2 text-muted"></i>{{ file.name }}</td>
                            <td class="text-end">{{ formatBytes(file.size) }}</td>
                            <td class="text-end">{{ file.last_modified }}</td>
                            <td class="text-end">
                                <a :href="`/system-maintenance/s3/download?key=${encodeURIComponent(file.key)}`" class="btn btn-soft-info btn-sm" v-b-tooltip.hover title="Download">
                                    <i class="ri-download-2-line align-bottom"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="nextToken" class="text-center py-2 border-top">
                <b-button variant="light" size="sm" :disabled="loadingMore" @click="loadMore()">
                    {{ loadingMore ? 'Loading…' : 'Load More' }}
                </b-button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ['message'],
    data() {
        return {
            prefix: '',
            breadcrumb: [],
            folders: [],
            files: [],
            nextToken: null,
            loading: false,
            loadingMore: false,
            downloadingFolder: false
        }
    },
    created() {
        this.load('');
    },
    methods: {
        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            const exponent = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
            const value = bytes / Math.pow(1024, exponent);
            return `${value.toFixed(exponent === 0 ? 0 : 2)} ${units[exponent]}`;
        },
        load(prefix) {
            this.loading = true;
            this.prefix = prefix;
            axios.get('/system-maintenance/s3', { params: { prefix } })
                .then(response => {
                    this.breadcrumb = response.data.breadcrumb;
                    this.folders = response.data.folders;
                    this.files = response.data.files;
                    this.nextToken = response.data.next_token;
                })
                .catch(() => this.$emit('message', { success: false, message: 'Failed to load S3 contents.' }))
                .finally(() => this.loading = false);
        },
        loadMore() {
            this.loadingMore = true;
            axios.get('/system-maintenance/s3', { params: { prefix: this.prefix, token: this.nextToken } })
                .then(response => {
                    this.folders = this.folders.concat(response.data.folders);
                    this.files = this.files.concat(response.data.files);
                    this.nextToken = response.data.next_token;
                })
                .catch(() => this.$emit('message', { success: false, message: 'Failed to load more items.' }))
                .finally(() => this.loadingMore = false);
        },
        downloadFolder() {
            this.downloadingFolder = true;
            axios.get('/system-maintenance/s3/download-folder', {
                params: { prefix: this.prefix },
                responseType: 'blob'
            })
            .then(response => {
                const disposition = response.headers['content-disposition'] || '';
                const match = disposition.match(/filename="?([^"]+)"?/);
                const filename = match ? match[1] : 'download.zip';
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(url);
            })
            .catch(async (error) => {
                let message = 'Failed to download folder.';
                if (error.response && error.response.data instanceof Blob) {
                    try {
                        const text = await error.response.data.text();
                        message = JSON.parse(text).message || message;
                    } catch (e) {}
                }
                this.$emit('message', { success: false, message });
            })
            .finally(() => this.downloadingFolder = false);
        }
    }
}
</script>
