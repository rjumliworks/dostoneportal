<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-pin-distance-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Travel Order Details</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Detailed overview of the Travel Order request</p>
                </div>
            </div>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 342px); overflow-x: hidden; overflow-y: auto;">
            <div class="row g-3 p-3">
                <div class="col-md-12">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-file-text-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Purpose :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ information.purpose }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-map-pin-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Destination :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ information.location.name }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-todo-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Travel Date(s) :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{formatDateRange(information.start, information.end)}} </h6>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="text-muted mt-0"/>
                <div class="align-items-center d-flex">
                    <p class="ms-3 mb-0 text-primary fs-12 fw-semibold flex-grow-1">Assigned Employees for this Travel</p>
                    <div class="flex-shrink-0 pe-3">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <label for="navbarscrollspy-showcode" class="form-label text-muted">
                                <span v-if="!showName">Show names</span>
                                <span v-else>Hide names</span>
                            </label>
                            <input class="form-check-input code-switcher" v-model="showName" type="checkbox" id="navbarscrollspy-showcode">
                        </div>
                    </div>
                </div>
            <hr class="text-muted mb-0"/>
            <div class="avatar-group p-3 ms-2" v-if="!showName">
                <div class="avatar-group-item material-shadow"  v-for="(list, index) of information.tags" :key="index">
                    <a href="javascript: void(0);" class="d-inline-block" 
                    v-b-tooltip.hover="{title: list.name,placement: 'top',customClass: 'my-tooltip-class'}">
                        <img :src="list.avatar" alt="" class="rounded-circle avatar-xs">
                    </a>
                </div>
            </div>
            <div class="border border-dashed rounded p-2 m-3" v-else>
                <div v-for="(row, rowIndex) in chunkedTags" :key="rowIndex" class="mb-1">
                    <ul class="list-unstyled fs-12 mb-0 d-flex">
                        <li class="py-0 me-3 d-flex align-items-center" style="min-width: 160px;" v-for="(list, index) in row" :key="index">
                            <i class="mdi mdi-circle-medium me-1 text-muted"></i> {{ list.name }}
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="text-muted mt-0"/>
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Other Details</p>
            <hr class="text-muted mb-0"/>
            <div class="row g-3 p-3">
                <div class="col-md-6">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                <i class="ri-hand-coin-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Vehicle :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ information.vehicle.name }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                <i class="ri-alarm-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Departure Time :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ information.time }} </h6>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['information'],
    data(){
        return {
            showName: false
        }
    },
    computed: {
        chunkedTags() {
            const tags = this.information.tags || [];
            let chunkSize = 3;

            if (tags.length >= 6) chunkSize = 2;  
            else if (tags.length >= 4) chunkSize = 2; 

            const chunks = [];
            for (let i = 0; i < tags.length; i += chunkSize) {
                chunks.push(tags.slice(i, i + chunkSize));
            }
            return chunks;
        }
    },
    methods: {
        formatDateRange(start, end) {
            const startDate = new Date(start);
            const endDate = new Date(end);

            const options = { month: 'long', day: 'numeric' };
            const startStr = startDate.toLocaleDateString('en-US', options);
            const endStr = endDate.toLocaleDateString('en-US', { day: 'numeric' });

            if (start === end) {
            return startDate.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            }

            const year = startDate.getFullYear(); 
            return `${startStr}-${endStr}, ${year}`;
        },
    }
}
</script>
<style>
    .my-tooltip-class {
        max-width: 1000px !important;  
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
</style>