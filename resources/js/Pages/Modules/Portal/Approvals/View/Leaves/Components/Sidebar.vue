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
                    <h5 class="mb-0 fs-14"><span class="text-body">Leave Details</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Detailed overview of the Travel Order request</p>
                </div>
            </div>
        </div>
        <div class="card bg-white rounded-bottom shadow-none mb-0" style="height: calc(100vh - 342px); overflow-x: hidden; overflow-y: auto;">
            <div class="row g-3 p-3">
                <div :class="(this.$page.props.user.data.type == 'Regular') ? 'col-md-4' : 'col-md-6'">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                               <img :src="information.tags[0].avatar" alt="" class="rounded-circle avatar-xs">
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Employee :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0">{{information.tags[0].name}}</h6>
                        </div>
                    </div>
                </div>
                <div :class="(this.$page.props.user.data.type == 'Regular') ? 'col-md-4' : 'col-md-6'">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-todo-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Leave Date(s) :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{formatDateRange(information.start, information.end)}} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" v-if="this.$page.props.user.data.type == 'Regular'">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-todo-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Credits Deducted :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{information.count}} Credits <span class="text-muted fw-normal">(Days)</span></h6>
                        </div>
                    </div>
                </div>
                <div :class="(this.$page.props.user.data.type == 'Regular') ? 'col-md-4' : 'col-md-6'">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-file-text-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Leave Type :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ information.leave.name }} </h6>
                        </div>
                    </div>
                </div>
                <div :class="(this.$page.props.user.data.type == 'Regular') ? 'col-md-4' : 'col-md-6'">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-todo-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Details :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{information.detail}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <template v-if="this.$page.props.user.data.type == 'Regular'">
                <hr class="text-muted mt-0"/>
                    <p class="ms-3 mb-0 text-primary fs-12 fw-semibold">Leave Credits</p>
                <hr class="text-muted mb-0"/>
                <div class="row g-3 p-3">
                    <div class="col-md-12">
                        <div class="table-responsive bg-white">
                            <table class="table align-middle table-bordered table-centered mb-0" v-if="requires_credits"> 
                                <thead class="table-light thead-fixed">
                                    <tr class="fs-11">
                                        <th style="width: 32%;" class="text-center">Leave Type</th>
                                        <th style="width: 17%;" class="text-center">Old Balance</th>
                                        <th style="width: 17%;" class="text-center">
                                            {{ information.status.name === 'Disapproved' ? 'Returned' : 'Deducted' }}
                                        </th>
                                        <th style="width: 17%;" class="text-center">New Balance</th>
                                        <th style="width: 17%;" class="text-center">Status</th>
                                    </tr>
                                </thead>

                                <tbody class="table-white fs-12">
                                    <tr v-for="(list, index) in information.credits" :key="index" class="text-dark">
                                        <template v-if="information.status.name !== 'Disapproved' ? !list.is_returned : list.is_returned">
                                            <td class="text-center">{{ list.credit.leave.name }}</td>
                                            <td class="text-center">{{ list.log.old_balance }}</td>
                                            <td class="text-center">{{ list.log.amount }}</td>
                                            <td class="text-center">{{ list.log.new_balance }}</td>
                                            <td class="text-center">
                                                <span v-if="information.status.name === 'Disapproved'" class="badge bg-warning">Returned</span>
                                                <span v-else-if="!list.is_borrowed" class="badge bg-success">Deducted</span>
                                                <span v-else class="badge bg-danger">Borrowed</span>
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                            <table v-else class="table align-middle table-bordered table-centered mb-0">
                                <thead class="table-light thead-fixed">
                                    <tr class="fs-11">
                                        <th style="width: 40%;" class="text-center">Leave Type</th>
                                        <th style="width: 30%;" class="text-center">Maximum Allowable Days</th>
                                        <th style="width: 30%;" class="text-center">Days Availed</th>
                                    </tr>
                                </thead>
                                <tbody class="table-white fs-12">
                                    <tr v-for="(list, index) in information.credits" :key="index" class="text-dark">
                                        <td class="text-center">{{ list.credit.leave.name }}</td>
                                        <td class="text-center">{{ list.log.old_balance }}</td>
                                        <td class="text-center">{{ list.log.amount }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </template>
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