<template>
    <div class="card shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="fs-24 text-danger"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span>test</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">
                     
                        <span>
                            Expired
                        </span>
                    </p>
                </div>
                <div class="flex-shrink-0"></div>
            </div>
        </div>
        <div class="card-body border-bottom bg-white">
            <p class="mb-0 text-primary fs-12 fw-semibold">Storage Status : </p>
        </div>
        <div class="card bg-white border-bottom shadow-none mb-0" style="height: calc(100vh - 300px); overflow-x: hidden;">
            <div class="p-4 mb-n4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0"><i :class="progressIcon" class="fs-17"></i></div>
                    <div class="flex-grow-1 ms-3 overflow-hidden">
                        <div class="progress mb-2 progress-sm">
                            <div class="progress-bar" :class="progressBarClass" role="progressbar" :style="{ width: percentUsed + '%' }" :aria-valuenow="percentUsed"  aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-muted fs-12 d-block text-truncate"><b>1.2 GB</b> of <b>10 GB</b> used (12%)</span>
                    </div>
                </div>
            </div>
            <hr class="text-muted"/>
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Document Management System</p>
            <hr class="text-muted mb-2"/>
            <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                <li class="list-group-item px-0">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i class="ri-folder-2-fill text-warning fs-17"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">ISO 9001</h6>
                            <p class="fs-11 mb-0 text-muted">You can create up to 4 folders with your plan</p>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <h6 class="mt-2 me-2 fs-12">1 of 3</h6>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- <hr class="text-muted"/>
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Subscription Overview</p>
            <hr class="text-muted mb-2"/>
            <div class="row g-2 pe-3 ps-3 pt-2">
                <div class="col-md-6">
                    <div class="d-flex border border-dashed rounded p-2">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-2">
                            <div class="avatar-title bg-light rounded-circle fs-14 text-primary"><i class="ri-calendar-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-10">1 :</p>
                            <h6 class="text-truncate fw-semibold fs-11 mb-0">1</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex border border-dashed rounded p-2">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-2">
                            <div class="avatar-title bg-light rounded-circle fs-14 text-primary"><i class="ri-calendar-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-10">2</p>
                            <h6 class="text-truncate fw-semibold fs-11 mb-0">2</h6>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- <div class="p-3 mt-auto" >
                <div class="alert alert-danger d-flex align-items-center mb-0">
                    <div class="flex-shrink-0">
                        <i class="ri-cloud-line text-danger align-bottom display-6"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-danger fs-13 mb-0">Upgrade to Pro</h5>
                        <p class="text-muted mb-0 fs-11">Get more space for your...</p>
                    </div>
                    <div class="flex-shrink-0 text-end">
                        <button class="btn btn-sm btn-danger float-end"><i class="ri-upload-cloud-line align-bottom"></i> Upgrade Now</button>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>

<script>
export default {
   
}
</script>
