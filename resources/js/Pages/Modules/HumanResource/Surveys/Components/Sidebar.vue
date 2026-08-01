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
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Employment Status Rating</p>
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
                            <h6 class="mt-2 fs-11">{{list.count}} <span class="text-muted fw-normal">({{list.respondents}}/{{ list.eligible }})</span></h6>
                        </div>
                    </div>

                    <div v-if="list.not_submitted && list.not_submitted.length" class="d-flex align-items-center justify-content-between mt-2 ps-1">
                        <span class="text-danger fs-10 text-uppercase fw-semibold">Not submitted</span>
                        <div class="avatar-group d-flex align-items-center" style="cursor: pointer;" @click.stop="openNotSubmitted(list)">
                            <a
                                v-for="user in list.not_submitted.slice(0, 6)"
                                :key="user.id"
                                href="javascript:void(0);"
                                class="avatar-group-item material-shadow"
                                :title="user.name"
                                v-b-tooltip.hover
                            >
                                <div class="avatar-xxs">
                                    <img
                                        v-if="user.avatar"
                                        :src="user.avatar"
                                        class="avatar-img rounded-circle"
                                    />
                                    <div v-else class="avatar-img rounded-circle bg-danger">
                                        {{ user.name.charAt(0) }}
                                    </div>
                                </div>
                            </a>
                            <a
                                class="avatar-group-item material-shadow"
                                v-b-tooltip.hover
                                title="Show all"
                            >
                                <div class="avatar-xxs">
                                    <span class="avatar-title rounded-circle bg-danger text-white fs-10">
                                        {{ list.not_submitted.length }}
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="mt-auto p-3">
                <button type="button" @click="openPrint()" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#composemodal"><i class="ri-printer-fill me-2"></i>Print Morale Survey</button>
            </div>
        </div>

        <NotSubmitted ref="notSubmittedModal" />
    </div>
</template>
<script>
import NotSubmitted from './Modals/NotSubmitted.vue';
export default {
    components: { NotSubmitted },
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
    },
    methods: {
        openPrint(){
            window.open('/surveys?option=print&id='+this.survey.reference);
        },
        openNotSubmitted(list){
            this.$refs.notSubmittedModal.show(list.name, list.not_submitted);
        },
    }
}
</script>
<style scoped>
.avatar-xxs {
    width: 24px;
    height: 24px;
    flex: 0 0 24px;
    position: relative;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 50%;
}
</style>