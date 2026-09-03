<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-inbox-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Pending Maintenance Requests</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Requests awaiting action across all assets</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 594px); overflow: auto;">
            <ul class="list-group list-group-flush border-dashed mb-n2 mt-n2" v-if="lists.length">
                <li class="list-group-item px-0" v-for="(list,index) in lists" v-bind:key="index">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i :class="typeIcon(list.maintainable_type)" class="text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <Link :href="viewLink(list)" class="fw-semibold fs-13 text-primary">{{ list.maintainable_name || '-' }}</Link>
                            <span class="fs-11 text-muted ms-1">{{ list.code }}</span>
                            <p class="fs-12 mb-0">{{ list.work_requested }}</p>
                            <p class="fs-11 text-muted mb-0">{{ list.requested_at }} &middot; {{ list.requester || '-' }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span v-if="list.priority" class="badge bg-secondary-subtle text-secondary">{{ list.priority.name }}</span>
                        </div>
                    </div>
                </li>
            </ul>
            <p v-else class="text-muted text-center mb-0">No pending requests.</p>
        </div>
    </div>
</template>
<script>
export default {
    props: ['lists'],
    methods: {
        typeIcon(type){
            return { equipment: 'ri-mac-fill', vehicle: 'ri-car-fill', building: 'ri-government-fill' }[type] || 'ri-tools-fill';
        },
        viewLink(list){
            if(list.maintainable_type === 'equipment'){ return `/equipments/${list.maintainable_code}`; }
            if(list.maintainable_type === 'vehicle'){ return `/vehicles/${list.maintainable_code}`; }
            if(list.maintainable_type === 'building'){ return `/buildings/${list.maintainable_code}`; }
            return '#';
        }
    }
}
</script>
