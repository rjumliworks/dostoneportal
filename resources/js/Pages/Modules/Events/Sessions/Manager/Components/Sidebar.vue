<template>
    <b-col lg="3">
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
                    <h5 class="mb-0 fs-14"><span class="text-body">Session Overview</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Summary of informations</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom border-bottom">
            <p class="mb-0 text-primary fs-12 fw-semibold">My E-Wallet</p>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 405px); overflow: auto;">
            <ul class="list-group list-group-flush border-dashed mb-n4 mt-n2 p-3">
                
            </ul>
            <hr class="text-muted"/>
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Transaction History</p>
            <hr class="text-muted mb-1"/>
            <simplebar v-if="wallet" data-simplebar style="max-height: 400px">
                
            </simplebar>
        </div>
    </div>
    </b-col>
</template>
<script>
import simplebar from 'simplebar-vue';
export default {
    components : { simplebar },
    data(){
        return {
            currentUrl: window.location.origin,
        }
    },
}
</script>