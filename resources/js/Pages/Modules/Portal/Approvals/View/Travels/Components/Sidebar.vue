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
                <div class="col-md-12" v-if="information.events && information.events.length > 1">
                    <simplebar style="max-height: 170px; overflow-x: hidden;">
                        <div class="d-flex border border-dashed bg-light-subtle rounded p-3" v-for="(event, index) in information.events" :key="event.id" :class="{ 'mt-2': index > 0 }">
                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-2-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 text-muted fs-12">Event :</p>
                                <h6 class="fw-semibold fs-12 mb-1"> {{ event.title }} </h6>
                                <div class="d-flex flex-wrap align-items-center gap-2 fs-11 text-muted">
                                    <span v-if="event.start"><i class="ri-calendar-event-fill align-bottom me-1"></i>{{ event.start }}<span v-if="event.end && event.end !== event.start"> - {{ event.end }}</span></span>
                                    <span v-if="event.location?.name"><i class="ri-map-pin-fill align-bottom me-1"></i>{{ event.location.name }}</span>
                                </div>
                            </div>
                        </div>
                    </simplebar>
                </div>
                <div class="col-md-12">
                    <div class="d-flex border border-dashed bg-light-subtle rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-file-text-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Purpose :</p>
                            <h6 class="fw-semibold fs-12 mb-1"> {{ information.purpose }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                 <div class="col-md-4">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                <i v-if="information.mode.name == 'Public Conveyance'" class="ri-flight-takeoff-fill"></i>
                                <i v-if="information.mode.name == 'Official Vehicle'" class="ri-car-fill"></i>
                                <i v-if="information.mode.name == 'Vehicle Rental'" class="ri-bus-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Mode of Travel :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ information.mode.name }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                <i class="ri-hand-coin-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Expense Type :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ information.expense.name }} : <span v-for="(list,index) in information.expenses" v-bind:key="index" class="me-1 badge bg-success border text-white fw-normal fs-10">{{ list.name }}</span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="text-muted mt-0"/>
            <div class="align-items-center d-flex">
                <p class="ms-3 mb-0 text-primary fs-12 fw-semibold flex-grow-1">Assigned {{$page.props.user.data.signatory.designationable.assigned.others}} Employees for this Travel</p>
                <div class="flex-shrink-0 pe-3">
                    <p class="ms-3 mb-0 text-primary fs-12 fw-semibold flex-grow-1">Other Division Employees</p>
                </div>
            </div>
            <hr class="text-muted mb-0"/>
            <div class="d-flex justify-content-between">
                <div class="avatar-group p-3 ms-2">
                    <div class="avatar-group-item material-shadow"
                        v-for="(list, index) of matchingTags"
                        :key="index">
                        <a href="javascript: void(0);"
                        class="d-inline-block"
                        v-b-tooltip.hover="{title: list.name, placement: 'top', customClass: 'my-tooltip-class'}">
                            <img :src="list.avatar" alt="" class="rounded-circle avatar-xs">
                        </a>
                    </div>
                </div>

                <div class="avatar-group p-3 ms-2">
                    <div class="avatar-group-item material-shadow"
                        v-for="(list, index) of nonMatchingTags"
                        :key="index">
                        <a href="javascript: void(0);"
                        class="d-inline-block"
                        v-b-tooltip.hover="{title: list.name, placement: 'top', customClass: 'my-tooltip-class'}">
                            <img :src="list.avatar" alt="" class="rounded-circle avatar-xs">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import simplebar from "simplebar-vue";
export default {
    components: { simplebar },
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
        },
        matchingTags() {
            return this.information.tags.filter(tag => tag.division === this.information.division.id);
        },
        nonMatchingTags() {
            return this.information.tags.filter(tag => tag.division !== this.information.division.id);
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