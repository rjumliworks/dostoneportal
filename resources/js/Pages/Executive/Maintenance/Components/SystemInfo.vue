<template>
    <div class="row">
        <div class="col-lg-6">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle">
                    <h5 class="card-title mb-0 fs-14">Application</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush fs-13">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Laravel Version</span>
                            <span class="fw-semibold">{{ info.laravel_version }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">PHP Version</span>
                            <span class="fw-semibold">{{ info.php_version }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Environment</span>
                            <span class="badge" :class="info.environment === 'production' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'">{{ info.environment }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Debug Mode</span>
                            <span class="badge" :class="info.debug_mode ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'">{{ info.debug_mode ? 'Enabled' : 'Disabled' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Maintenance Mode</span>
                            <span class="badge" :class="info.maintenance_mode ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'">{{ info.maintenance_mode ? 'Down' : 'Live' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Timezone</span>
                            <span class="fw-semibold">{{ info.timezone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Server Time</span>
                            <span class="fw-semibold">{{ info.server_time }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle">
                    <h5 class="card-title mb-0 fs-14">Server &amp; Services</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush fs-13">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Server Software</span>
                            <span class="fw-semibold text-truncate ms-2" style="max-width: 60%;">{{ info.server_software }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Operating System</span>
                            <span class="fw-semibold">{{ info.os }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Database</span>
                            <span class="fw-semibold">{{ info.database_driver }} ({{ info.database_version || 'n/a' }})</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Cache Driver</span>
                            <span class="fw-semibold">{{ info.cache_driver }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Queue Connection</span>
                            <span class="fw-semibold">{{ info.queue_connection }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Session Driver</span>
                            <span class="fw-semibold">{{ info.session_driver }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Filesystem Disk</span>
                            <span class="fw-semibold">{{ info.filesystem_disk }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle">
                    <h5 class="card-title mb-0 fs-14">PHP Limits</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush fs-13">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Memory Limit</span>
                            <span class="fw-semibold">{{ info.memory_limit }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Upload Max Filesize</span>
                            <span class="fw-semibold">{{ info.upload_max_filesize }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Post Max Size</span>
                            <span class="fw-semibold">{{ info.post_max_size }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Max Execution Time</span>
                            <span class="fw-semibold">{{ info.max_execution_time }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle">
                    <h5 class="card-title mb-0 fs-14">Scheduled Tasks</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0 fs-12">
                            <thead class="table-light">
                                <tr>
                                    <th>Command</th>
                                    <th>Schedule</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(task, index) in scheduledTasks" :key="index">
                                    <td><code>{{ task.command }}</code></td>
                                    <td>{{ task.schedule }}</td>
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
        info: { type: Object, required: true },
        scheduledTasks: { type: Array, default: () => [] }
    }
}
</script>
