<template>
    <div class="row">
        <div class="col-lg-4">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fs-14">Disk Usage</h5>
                    <BLink @click="$emit('refresh')" v-b-tooltip.hover title="Refresh">
                        <i class="bx bx-refresh fs-16"></i>
                    </BLink>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between fs-12 text-muted mb-1">
                        <span>Used: {{ formatBytes(info.disk.used) }}</span>
                        <span>Free: {{ formatBytes(info.disk.free) }}</span>
                    </div>
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar" role="progressbar"
                             :class="info.disk.percent_used >= 90 ? 'bg-danger' : (info.disk.percent_used >= 75 ? 'bg-warning' : 'bg-success')"
                             :style="{ width: info.disk.percent_used + '%' }">
                        </div>
                    </div>
                    <p class="text-center fs-12 text-muted mb-0">{{ info.disk.percent_used }}% of {{ formatBytes(info.disk.total) }} used</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle">
                    <h5 class="card-title mb-0 fs-14">Storage Breakdown</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 fs-13">
                            <thead class="table-light">
                                <tr>
                                    <th>Directory</th>
                                    <th class="text-end">Size</th>
                                    <th class="text-end" style="width: 15%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(dir, index) in info.directories" :key="index">
                                    <td>
                                        <div class="fw-semibold">{{ dir.label }}</div>
                                        <div class="text-muted fs-11 text-truncate" style="max-width: 320px;">{{ dir.path }}</div>
                                    </td>
                                    <td class="text-end">{{ dir.exists ? formatBytes(dir.size) : '—' }}</td>
                                    <td class="text-end">
                                        <span class="badge" :class="dir.exists ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'">
                                            {{ dir.exists ? 'Found' : 'Missing' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        info: { type: Object, required: true }
    },
    emits: ['refresh'],
    methods: {
        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            const units = ['B', 'KB', 'MB', 'GB', 'TB'];
            const exponent = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
            const value = bytes / Math.pow(1024, exponent);
            return `${value.toFixed(exponent === 0 ? 0 : 2)} ${units[exponent]}`;
        }
    }
}
</script>
