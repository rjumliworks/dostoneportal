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
                    <h5 class="mb-0 fs-14"><span class="text-body">Equipment Details</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Basic Information of the equipment</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom border-bottom">
            <p class="mb-0 text-primary fs-12 fw-semibold">Specification</p>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 398px); overflow: auto;">
            <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-price-tag-3-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ equipment.old_code || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Old Code</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-bookmark-3-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ equipment.detail?.brand || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Brand</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-shape-2-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ equipment.detail?.model || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Model</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-hand-coin-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ equipment.detail?.price ? Number(equipment.detail.price).toLocaleString('en-US',{minimumFractionDigits:2}) : '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Price</p>
                        </div>
                    </div>
                </li>
            </ul>
            <hr class="text-muted"/>
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Maintenance Plan</p>
            <hr class="text-muted mb-2"/>
            <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-tools-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ equipment.maintenance_plan || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Plan</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-calendar-event-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ equipment.maintenance_due || '-' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Due Date</p>
                        </div>
                    </div>
                </li>
            </ul>
            <hr class="text-muted"/>
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Assignment</p>
            <hr class="text-muted mb-1"/>
            <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-user-2-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ equipment.current_assignment?.user_name || 'Not assigned' }}</h6>
                            <p class="fs-11 mb-0 text-muted">Assigned To</p>
                        </div>
                    </div>
                </li>
                <li class="list-group-item px-0" v-if="equipment.current_assignment?.start_at">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-calendar-check-fill text-primary"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{ equipment.current_assignment.start_at }}</h6>
                            <p class="fs-11 mb-0 text-muted">Assigned Since</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    props: ['equipment']
}
</script>
