<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-parent-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Overall Employees</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Summary of the employee status</p>
                </div>
              
            </div>
        </div>
        <div class="card-body bg-white border-bottom">
          
            <div class="row g-0">
                <ul class="list-group list-group-flush border-dashed mb-0 mt-n2">
                    <li class="list-group-item px-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0 avatar-xs">
                                <span class="avatar-title bg-light p-1 rounded-circle">
                                    <i class="ri-user-follow-fill fs-18 text-success"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-0 fs-12">Present Today</h6>
                                <p class="fs-11 mb-0 text-muted">Employee reported to work and completed the scheduled workday.</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <h6 class="mb-1">2</h6>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="d-flex">
                            <div class="flex-shrink-0 avatar-xs">
                                <span class="avatar-title bg-light p-1 rounded-circle">
                                    <i class="ri-user-unfollow-fill fs-18 text-danger"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-0 fs-12">Absent Today</h6>
                                <p class="fs-11 mb-0 text-muted">Employee did not report to work for the scheduled workday.</p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <h6 class="mb-1">2</h6>
                            </div>
                        </div>
                    </li>
                </ul>
                <hr class="text-muted mt-2 mb-2"/>
                <ul class="list-group list-group-flush border-dashed mb-0">
                    <li class="list-group-item px-0" v-for="(list,index) in employee.statuses" v-bind:key="index">
                        <div class="d-flex">
                            <div class="flex-shrink-0 avatar-xs">
                                <span class="avatar-title bg-light p-1 rounded-circle">
                                    <i :class="icons[index]+' '+colors[index]"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-0 fs-12">{{list.name}} </h6>
                                <p class="fs-11 mb-0 text-muted">{{ desriptions[index] }} <span class="fs-10 text-muted"> ({{ list.percentage }})</span></p>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <h6 class="mt-2">{{list.count}} </h6>
                                <!-- <p class="text-success fs-12 mb-0">$19,405.12</p> -->
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
               
        </div>
        <div class="card-body">
            
        </div>
    </div>
</template>
<script>
export default {
    props: ['employee'],
    data(){
        return {
            year: null,
            desriptions: [
                'Rating for permanent employees',
                'Rating for contract-based staffs',
                'Rating for job order personnels',
                'Rating for agency-hired personnel'
            ],
            icons: [
                'ri-account-circle-fill fs-18','ri-emotion-fill fs-18','ri-user-3-fill fs-18','ri-building-fill fs-18'
            ],
            colors: [
                'text-success','text-primary','text-warning','text-danger'
            ],
        }
    },
    methods: {
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>