<template>
    <div class="card bg-light-subtle shadow-none border mt-n2">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-calendar-event-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Upcoming Equipment Maintenance</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">Equipment maintenance due date</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom">
            <div class="table-responsive table-card" v-if="lists.length">
                <simplebar data-simplebar style="height: 240px;">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th>Equipment</th>
                                <th style="width: 15%;" class="text-center">Maintenance Due</th>
                                <th style="width: 6%;"></th>
                            </tr>
                        </thead>
                        <tbody class="fs-12">
                            <tr v-for="(list,index) in lists" v-bind:key="index">
                                <td>
                                    <h5 class="fs-13 mb-0 fw-semibold text-primary">{{ list.code }}</h5>
                                    <p class="fs-12 text-muted mb-0">{{ list.name }}</p>
                                </td>
                                <td class="text-center">
                                    <span v-if="list.is_overdue" class="badge bg-danger-subtle text-danger ms-1">Overdue</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </simplebar>
            </div>
            <p v-else class="text-muted text-center mb-0">No equipment has a maintenance due date set.</p>
        </div>
    </div>
</template>
<script>
import simplebar from "simplebar-vue";
export default {
    components: { simplebar },
    props: ['lists']
}
</script>
