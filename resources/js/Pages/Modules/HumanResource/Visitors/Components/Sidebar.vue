<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-information-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Visitor Details</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Basic Information of the user</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom border-bottom">
            <p class="mb-0 text-primary fs-12 fw-semibold">Total Hours Rendered</p>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 398px); overflow: auto;">
            
        </div>
    </div>
</template>
<script>
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(relativeTime);
export default {
    props: ['user'],
    data(){
        return {
            now: dayjs()
        }
    },
    mounted() {
        if (this.user?.roles) {
            this.user.roles.sort((a, b) => {
                if (a.is_active !== b.is_active) return b.is_active - a.is_active;

                if (a.is_active === 1) {
                    if (a.name === 'Employee' && b.name !== 'Employee') return 1;
                    if (a.name !== 'Employee' && b.name === 'Employee') return -1;
                }
                return 0;
            });
        }

        this.interval = setInterval(() => {
            this.now = dayjs(); // re-triggers computed values
        }, 60000);
    },
    beforeUnmount() {
        clearInterval(this.interval);
    },
    methods: { 
        timeAgo(date) {
            return dayjs(date).from(this.now);
        }
    }
}
</script>