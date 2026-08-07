<template>
<div class="card bg-light-subtle shadow-none border">
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-team-fill text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Family Background</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">Spouse, parents, and children on file.</p>
            </div>
        </div>
    </div>
    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 291px); overflow: auto;">
        <div class="row g-3">
            <div class="col-md-4 col-6">
                <div class="d-flex border border-dashed rounded p-3">
                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-user-2-fill"></i></div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fs-12">Spouse :</p>
                        <h6 v-if="fullName(spouse)" class="text-truncate fs-12 mb-0">{{ fullName(spouse) }}</h6>
                        <h6 v-else class="text-truncate text-muted fs-12 mb-0">Not set</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="d-flex border border-dashed rounded p-3">
                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="bx bx-male"></i></div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fs-12">Father :</p>
                        <h6 v-if="fullName(father)" class="text-truncate fs-12 mb-0">{{ fullName(father) }}</h6>
                        <h6 v-else class="text-truncate text-muted fs-12 mb-0">Not set</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="d-flex border border-dashed rounded p-3">
                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="bx bx-female"></i></div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fs-12">Mother :</p>
                        <h6 v-if="fullName(mother)" class="text-truncate fs-12 mb-0">{{ fullName(mother) }}</h6>
                        <h6 v-else class="text-truncate text-muted fs-12 mb-0">Not set</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-12"><hr class="text-muted mt-0 mb-0"/></div>
            <div class="col-md-12">
                <h6 class="fs-13 text-primary mb-2">Children</h6>
                <div class="table-responsive table-card" v-if="children.length > 0">
                    <table class="table align-middle table-striped table-centered mb-0">
                        <thead class="table-primary thead-fixed">
                            <tr class="fs-11"><th>Full Name</th><th class="text-center">Birthdate</th></tr>
                        </thead>
                        <tbody class="fs-12">
                            <tr v-for="(child, index) in children" :key="index">
                                <td>{{ child.name || '-' }}</td>
                                <td class="text-center">{{ child.birthdate || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="text-muted fs-12">No children on file.</div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
    props: ['information'],
    computed: {
        backgrounds(){
            return this.information?.backgrounds || {};
        },
        spouse(){
            return this.backgrounds.spouse || null;
        },
        father(){
            return this.backgrounds.parents?.father || null;
        },
        mother(){
            return this.backgrounds.parents?.mother || null;
        },
        children(){
            return this.backgrounds.children || [];
        }
    },
    methods: {
        fullName(person){
            if (!person) return null;
            return [person.firstname, person.middlename, person.lastname, person.suffix].filter(Boolean).join(' ') || null;
        }
    }
}
</script>
