<template>
    <div class="row">
        <div class="col-lg-6">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle">
                    <h5 class="card-title mb-0 fs-14">Application Cache</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted fs-13">Clears the application, configuration, route, and view caches (equivalent to <code>optimize:clear</code>).</p>
                    <b-button variant="primary" size="sm" :disabled="clearing" @click="clearCache()">
                        <i class="ri-refresh-line align-bottom me-1"></i>
                        {{ clearing ? 'Clearing…' : 'Clear Cache' }}
                    </b-button>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border shadow-none">
                <div class="card-header bg-light-subtle">
                    <h5 class="card-title mb-0 fs-14">Maintenance Mode</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted fs-13" v-if="!maintenanceMode">
                        Puts the application into maintenance mode. Visitors will see a maintenance page until it's brought back online.
                    </p>
                    <p class="text-muted fs-13" v-else>
                        The application is currently in maintenance mode.
                        <span v-if="bypassUrl">Use <a :href="bypassUrl">this link</a> to access it while it's down.</span>
                    </p>
                    <b-button v-if="!maintenanceMode" variant="warning" size="sm" :disabled="toggling" @click="toggleMode(true)">
                        <i class="ri-tools-line align-bottom me-1"></i>
                        {{ toggling ? 'Please wait…' : 'Enable Maintenance Mode' }}
                    </b-button>
                    <b-button v-else variant="success" size="sm" :disabled="toggling" @click="toggleMode(false)">
                        <i class="ri-play-line align-bottom me-1"></i>
                        {{ toggling ? 'Please wait…' : 'Bring Application Online' }}
                    </b-button>
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
    emits: ['message'],
    data() {
        return {
            clearing: false,
            toggling: false,
            maintenanceMode: this.info.maintenance_mode,
            bypassUrl: null
        }
    },
    watch: {
        'info.maintenance_mode'(value) {
            this.maintenanceMode = value;
        }
    },
    methods: {
        clearCache() {
            this.clearing = true;
            axios.post('/system-maintenance/cache-clear')
                .then(response => this.$emit('message', response.data))
                .catch(() => this.$emit('message', { success: false, message: 'Failed to clear cache.' }))
                .finally(() => this.clearing = false);
        },
        toggleMode(enable) {
            this.toggling = true;
            axios.post('/system-maintenance/mode', { enable })
                .then(response => {
                    this.maintenanceMode = response.data.maintenance_mode;
                    this.bypassUrl = response.data.bypass_url || null;
                    this.$emit('message', response.data);
                })
                .catch(() => this.$emit('message', { success: false, message: 'Failed to update maintenance mode.' }))
                .finally(() => this.toggling = false);
        }
    }
}
</script>
