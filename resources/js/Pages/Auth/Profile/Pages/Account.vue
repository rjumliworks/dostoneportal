<template>
<div class="card bg-light-subtle shadow-none border">
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-bank-card-fill text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Contributions & Account Numbers</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">Government ID and contribution numbers on file.</p>
            </div>
        </div>
    </div>
    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 291px); overflow: auto;">
        <div class="row g-3" v-if="accounts.length > 0">
            <div class="col-md-4 col-6" v-for="account in accounts" :key="account.name">
                <div class="d-flex border border-dashed rounded p-3">
                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-bank-card-2-fill"></i></div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fs-12">{{ account.name }} :</p>
                        <h6 v-if="account.number" class="text-truncate fs-12 mb-0">{{ account.number }}</h6>
                        <h6 v-else class="text-truncate text-muted fs-12 mb-0">Not set</h6>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="text-center text-muted fs-12 py-5">No account numbers on file yet.</div>
    </div>
</div>
</template>
<script>
export default {
    props: ['information'],
    computed: {
        accounts(){
            return this.information?.accounts || [];
        }
    }
}
</script>
