<template>
    <div class="row g-3" style="height: calc(100vh - 424px);">
        <div class="col-lg-3">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2rem;width:2rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-0">
                                    <i class="ri-information-fill text-primary fs-18"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Session Details</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-11">Basic Information of the session</p>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom border-bottom">
                    <p class="mb-0 text-primary fs-11 fw-semibold">Session Status</p>
                </div>
                <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(-570px + 100vh); overflow: auto;">
                    <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                        <li class="list-group-item px-0 mb-n2">
                           <span :class="'badge fs-12 '+selected.status.bg">{{selected.status.name}}</span>
                        </li>
                    </ul>
                    <hr class="text-muted">
                    <p class="ms-3 mb-0 text-primary fs-11 fw-semibold">Session Information</p>
                    <hr class="text-muted mb-2">
                    <ul class="list-group list-group-flush mb-n4 mt-n3 p-3">
                        <li class="list-group-item px-0 mb-n2">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs">
                                    <span class="avatar-title bg-light p-1 rounded-circle">
                                        <i class="text-primary" :class="(selected.managers.length > 1) ? 'ri-team-fill' : 'ri-user-3-fill'"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="fs-11 mb-0 text-muted">Session Manager</p>
                                    <h6 class="mb-0 fs-12">
                                        <div v-for="(manager, index) in selected.managers" :key="index">
                                            {{ manager.name }}
                                        </div>
                                    </h6>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs"><span
                                        class="avatar-title bg-light p-1 rounded-circle"><i
                                            class="ri-map-pin-fill text-primary"></i></span></div>
                                <div class="flex-grow-1 ms-2">
                                    <p class="fs-11 mb-0 text-muted">Venue</p>
                                    <h6 class="mb-0 fs-12">{{ selected.venue.name }}, {{ selected.venue.establishment }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <hr class="text-muted">
                    <p class="ms-3 mb-0 text-primary fs-11 fw-semibold">Session Manager/s</p>
                    <hr class="text-muted mb-2">
                    
                
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="row g-3">
                <div class="col-md-6">
                   <div class="card border shadow-none bg-light-subtle" style="cursor: pointer;">
                        <div class="card-body" @click="openPrintParticipants">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-0">
                                            <i class="ri-group-2-fill text-primary fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Participants Attendance</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-11">Basic Information of the session</p>
                                </div>
                                <div class="flex-shrink-0 text-end mt-1">
                                    <i class="ri-download-cloud-fill fs-18 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                   <div class="card border shadow-none bg-light-subtle" style="cursor: pointer;">
                        <div class="card-body" @click="openPrintCsf">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-0">
                                            <i class="ri-question-fill text-primary fs-18"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Questions Asked</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-11">Basic Information of the session</p>
                                </div>
                                <div class="flex-shrink-0 text-end mt-1">
                                    <i class="ri-download-cloud-fill fs-18 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-n4">
                <div class="col-xxl-6 col-sm-6 project-card">
                    <div class="card border shadow-none">
                        <div class="card-body">
                            <div class="p-3 mt-n3 mx-n3 bg-danger-subtle rounded-top">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 fs-13">Participants</h5>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="d-flex gap-1 align-items-center my-n2">
                                            <button type="button"
                                                class="btn avatar-xs p-0 favourite-btn material-shadow-none active">
                                                <span class="avatar-title bg-transparent fs-15">
                                                    <i class="ri-star-fill"></i>
                                                </span>
                                            </button>
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-link text-muted p-1 mt-n1 py-0 text-decoration-none fs-15 material-shadow-none"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-more-horizontal icon-sm">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="19" cy="12" r="1"></circle>
                                                        <circle cx="5" cy="12" r="1"></circle>
                                                    </svg>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="apps-projects-overview.html"><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                                    <a class="dropdown-item" href="apps-projects-create.html"><i
                                                            class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#removeProjectModal"><i
                                                            class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                        Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="flex-shrink-0">
                                    <div class="donut-chart mx-auto" :style="{background: `conic-gradient(#0ab39c ${overallParticipants}%, #e9ebec 0)`}">
                                        <div class="donut-inner">
                                            {{ overallParticipants.toFixed(0) }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase text-truncate fw-semibold fs-10 text-muted mb-1" style="margin-top: -5px;">Attendance Monitoring</p>
                                    <h4 class="mb-0 fs-12">{{ this.selected.attendees.length }} / {{ this.selected.participants.length }}</h4>
                                    <small class="text-muted fs-10">List of participants attended</small>
                                </div>
                            </div>
                            <hr class="text-muted mb-n2"/>
                            <div class="py-3">
                                 <apexchart
                                type="bar"
                                height="200"
                                :options="chartOptions"
                                :series="series"
                               />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-6 col-sm-6 project-card">
                    <div class="card border shadow-none">
                        <div class="card-body">
                            <div class="p-3 mt-n3 mx-n3 bg-warning-subtle rounded-top">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 fs-13">Questions</h5>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="d-flex gap-1 align-items-center my-n2">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
                                <div class="flex-shrink-0">
                                    <div class="donut-chart mx-auto" :style="{background: `conic-gradient(#0ab39c ${questionStats.percentage}%, #e9ebec 0)`}">
                                        <div class="donut-inner">
                                            {{ questionStats.percentage }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase text-truncate fw-semibold fs-10 text-muted mb-1" style="margin-top: -5px;">Question Monitoring</p>
                                    <h4 class="mb-0 fs-12">{{ this.answeredCount }} / {{ this.selected.questions.length }}</h4>
                                    <small class="text-muted fs-10">List of questions asked and answered</small>
                                </div>
                            </div>
                            <hr class="text-muted mb-n2"/>
                            <div class="py-3">
                                 <apexchart
                                type="bar"
                                height="200"
                                :options="{
                                    ...chartOptions,
                                    xaxis: {
                                        categories: ['Total', 'Answered', 'Unanswered']
                                    }
                                }"
                                :series="questionSeries"
                               />
                            </div>

                        </div>
                    </div>
                </div>

                
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2rem;width:2rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-0">
                                    <i class="ri-information-fill text-primary fs-18"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 mt-0 fs-13"><span class="text-body">Session Details</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-11">Basic Information of the session</p>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom border-bottom">
                    <p class="mb-0 text-primary fs-11 fw-semibold">Session Status</p>
                </div>
                <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(-570px + 100vh); overflow: auto;">
                    <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                        <li class="list-group-item px-0 mb-n2">
                           <span :class="'badge fs-12 '+selected.status.bg">{{selected.status.name}}</span>
                        </li>
                    </ul>
                    <hr class="text-muted">
                    <p class="ms-3 mb-0 text-primary fs-11 fw-semibold">Session Venue</p>
                    <hr class="text-muted mb-2">
                    <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3">
                        <li class="list-group-item px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs"><span
                                        class="avatar-title bg-light p-1 rounded-circle"><i
                                            class="ri-government-fill text-primary"></i></span></div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 fs-12">{{ selected.venue.name }}, {{ selected.venue.establishment }}</h6>
                                    <p class="fs-11 mb-0 text-muted">Establishment</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs"><span
                                        class="avatar-title bg-light p-1 rounded-circle"><i
                                            class="ri-map-pin-fill text-primary"></i></span></div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 fs-12">{{ selected.venue.address }}</h6>
                                    <p class="fs-11 mb-0 text-muted">Address</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <hr class="text-muted">
                    <p class="ms-3 mb-0 text-primary fs-11 fw-semibold">Session Manager/s</p>
                    <hr class="text-muted mb-2">
                    <ul class="list-group list-group-flush border-dashed mb-n4 mt-n3 p-3" style="cursor: pointer;">
                        <li class="list-group-item px-0" v-for="(list,index) in selected.managers" v-bind:key="index">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs"><span
                                        class="avatar-title bg-light p-1 rounded-circle"><i
                                            class="ri-hand-coin-fill text-primary"></i></span></div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 fs-12">{{ list.name }}</h6>
                                    <p class="fs-11 mb-0 text-muted">{{ list.type }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                
                </div>
            </div>
        </div>
 
    </div>
</template>
<script>
    export default {
        props: ['selected'],
        data() {
            return {
                chartOptions: {
                    chart: {
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            distributed: true
                        }
                    },
                    colors: [
                        '#0d6efd', // Blue
                        '#ffc107', // Yellow
                        '#198754'  // Green
                    ],
                    xaxis: {
                        categories: ['Capacity', 'Registered', 'Attended']
                    },
                    legend: {
                        show: false
                    }
                }
            }
        },
        computed: {
            series() {
                const participants = this.selected.participants || [];
                const attendees = this.selected.attendees || [];
                return [{
                    name: 'Count',
                    data: [
                        this.selected.detail.capacity ?? 0,
                        participants.length,
                        attendees.length
                    ]
                }];
            },
            questionSeries() {
                return [
                    {
                        name: 'Count',
                        data: [
                            this.selected.questions.length,
                            this.answeredCount,
                            this.unansweredCount
                        ]
                    }
                ];
            },
            overallParticipants() {
                const total = this.selected.participants.length;
                const attended = this.selected.attendees.length;

                return total > 0
                    ? (attended / total) * 100
                    : 0;
            },
            answeredCount() {
                return (this.selected.questions || []).filter(q => q.is_answered == 1).length;
            },
            unansweredCount() {
                return (this.selected.questions || []).filter(q => q.is_answered != 1).length;
            },
            questionStats() {
                const questions = this.selected.questions || [];
                const answered = questions.filter(q => q.is_answered == 1).length;
                const unanswered = questions.length - answered;

                return {
                    total: questions.length,
                    answered,
                    unanswered,
                    percentage: questions.length
                        ? ((answered / questions.length) * 100).toFixed(0)
                        : 0
                };
            }
        },
        methods: {
            openPrintParticipants(){
                window.open('/sessions?option=attendance&type=session&id='+this.selected.id);
            },
            openPrintCsf(){
                window.open('/sessions?option=csf&type=session&id='+this.selected.id);
            },  
        }
    }

</script>
<style scoped>
.donut-chart {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    position: relative;
}

.donut-inner {
    position: absolute;
    inset: 10px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 10px;
}</style>