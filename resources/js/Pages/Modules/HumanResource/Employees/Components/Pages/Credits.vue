<template>
    <div class="table-responsive table-card">
        <simplebar data-simplebar style="height: calc(100vh - 429px);">
            <table class="table table-nowrap align-middle mb-0">
                <thead class="table-light thead-fixed">
                    <tr class="fs-11">
                        <th style="width: 4%;" class="text-center">#</th>
                        <th>Leave</th>
                        <th style="width: 13%;" class="text-center">Earned</th>
                        <th style="width: 13%;" class="text-center">Used</th>
                        <th style="width: 13%;" class="text-center">Carried Over</th>
                        <th style="width: 13%;" class="text-center">Balance</th>
                        <th style="width: 7%;"></th>
                    </tr>
                </thead>
                <tbody v-if="lists.length > 0">
                    <tr class="fs-12" v-for="(list,index) in lists" v-bind:key="index">
                        <td class="text-center">{{ index+1 }}</td>
                        <td>
                            <h5 class="fs-13 mb-0 fw-semibold text-primary" v-if="list.leave">{{list.leave.name}}</h5>
                        </td>
                        <td class="text-center text-muted">{{ list.earned }}</td>
                        <td class="text-center text-muted">{{ list.used }}</td>
                        <td class="text-center text-muted">{{ list.carried_over }}</td>
                        <td class="text-center fw-semibold">{{ list.balance }}</td>
                        <td class="text-end">
                            <b-button @click="openHistory(list)" variant="soft-success" v-b-tooltip.hover title="View History" size="sm">
                                <i class="ri-eye-fill align-bottom"></i>
                            </b-button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No records found.</td>
                    </tr>
                </tbody>
            </table>
        </simplebar>
    </div>
    <History ref="history"/>
</template>
<script>
import simplebar from "simplebar-vue";
import History from './Modals/CreditLogs.vue';
export default {
    components: { simplebar, History },
    props: ['id','dropdowns','lists'],
    methods: {
        openHistory(credit){
            this.$refs.history.show(credit);
        }
    }
}
</script>
