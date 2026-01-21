<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-survey-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Survey Details</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Summary of the survey answers</p>
                </div>
            </div>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 340px); overflow-x: hidden; overflow-y: auto;">
            <div class="row align-items-center p-3 mb-n3">
                <div class="col-12">
                    <h6 class="text-primary text-uppercase fw-semibold text-truncate fs-12 mb-3">Overall Ratings</h6>
                    <h4 class="fs- mb-0">{{counts.rating}}</h4>
                    <div class="progress mb-2 progress-sm mt-2">
                        <div class="progress-bar bg-success" role="progressbar" :style="'width: '+(counts.answered/counts.active)*100+'%'" :aria-valuenow="(counts.answered/counts.active)*100"
                        aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="text-muted fs-12 d-block text-truncate"><b>{{counts.answered}}</b> out of <b>{{counts.active}}</b> employees have answered the survey.</span>
                </div>
            </div>
            <hr class="text-muted"/>
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Employement Status Rating</p>
            <hr class="text-muted mb-2"/>
            <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                <li class="list-group-item px-0" v-for="(list,index) in counts.statuses" v-bind:key="index" style="cursor: pointer;">
                    <div class="d-flex">
                        <div class="flex-shrink-0 avatar-xs">
                            <span class="avatar-title bg-light p-1 rounded-circle">
                                <i :class="icons[index]+ ' text-primary fs-16'"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{list.name}}</h6>
                            <p class="fs-11 mb-0 text-muted">{{ desriptions[index] }}</p>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <h6 class="mt-2 fs-11">{{list.count}}</h6>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    props: ['survey','counts'],
    data(){
        return {
            desriptions: [
                'Rating for permanent employees',
                'Rating for contract-based staffs',
                'Rating for job order personnels'
            ],
            icons: [
                'ri-account-circle-fill','ri-emotion-fill','ri-user-3-fill','ri-government-fill'
            ]
        }
    }
}
</script>