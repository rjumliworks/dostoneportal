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
                    <h5 class="mb-0 fs-14"><span class="text-body">Leave Credits</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">List of leaves with available credits; other leave types are applied for and subject to approval.</p>
                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom">
            <div class="table-responsive table-card" style="height: calc(100vh - 345px); overflow: auto;">
                <table class="table align-middle table-striped table-centered mb-0">
                    <thead class="table-light thead-fixed">
                        <tr class="fs-11">
                            <th class="text-center" style="width: 6%;">#</th>
                            <th>Leave</th>
                            <th class="text-center" style="width: 13%;">Earned</th>
                            <th class="text-center" style="width: 13%;">Used</th>
                            <th class="text-center" style="width: 13%;">Carried Over</th>
                            <th class="text-center" style="width: 13%;">Balance</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-white fs-12">
                        <tr v-for="(list,index) in credits" v-bind:key="index" :class="{
                                'bg-info-subtle': selectedRow === index
                            }">
                            <td class="text-center">{{ index+1 }}</td>
                            <td>{{list.leave.name}}</td>
                            <td class="text-center text-muted">{{list.earned}}</td>
                            <td class="text-center text-muted">{{list.used}}</td>
                            <td class="text-center text-muted">{{list.carried_over}}</td>
                            <td class="text-center fw-semibold">{{list.balance}}</td>
                            <td class="text-center">
                                <b-button @click="openView(list,index)" variant="success" v-b-tooltip.hover title="View" size="sm">
                                    <i class="ri-eye-fill align-bottom"></i>
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['credits'],
    data(){
        return {
            showName: false,
            selectedRow: null
        }
    },
    methods: { 
        openView(data,index){
            this.selectedRow = index;
            this.$emit('openhistory',data);
        },
    }
}
</script>