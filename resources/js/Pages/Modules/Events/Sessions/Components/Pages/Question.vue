<template>
    <div class="card-body bg-white border-bottom shadow-none">
        <b-row class="mt-n3 mb-2 ms-n4 me-n4">
            <b-col lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input type="text"  placeholder="Search Venue" class="form-control" style="width: 30%;">
                    <span @click="openPrint()" class="input-group-text" v-b-tooltip.hover title="Print" style="cursor: pointer;"> 
                        <i class="ri-printer-fill search-icon"></i>
                    </span>
                    <b-button type="button" variant="primary" @click="openCreate">
                        <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                    </b-button>
                </div>
            </b-col>
        </b-row>
    </div>
    <div class="table-responsive table-card" style="height: calc(100vh - 460px);">
        <table class="table table-nowrap align-middle mb-0">
           
            <thead class="bg-primary text-white">
                <tr class="fs-10">
                    <th style="width: 3%;"></th>
                    <th style="width: 15%;">Name</th>
                    <th class="">Question</th>
                    <th style="width: 8%;" class="text-center">Status</th>
                    <th style="width: 15%;" class="text-center">Date</th>
                </tr>
            </thead>
            <tbody v-if="questions.length > 0">
                <tr v-for="(list,index) in questions" v-bind:key="index" class="fs-12">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <h5 class="fs-12 mb-0 fw-semibold text-primary">{{(list.participant) ? list.participant.name : list.name}}</h5>
                    </td>
                    <td>{{list.question}}</td>
                    <td class="text-center">
                        <span v-if="list.is_answered" class="badge bg-success">Answered</span>
                        <span v-else class="badge bg-danger">Not Answered</span>
                    </td>
                    <td class="text-center">{{list.created_at}}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center text-muted">No records found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: ['questions'],
}
</script>