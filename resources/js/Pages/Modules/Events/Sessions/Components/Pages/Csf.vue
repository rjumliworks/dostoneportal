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
                    <th>Comment</th>
                    <th class="text-center" style="width: 5%;">Rate</th>
                    <th class="text-center" style="width: 10%;">Star</th>
                    <th class="text-center" style="width: 10%;">Date</th>
                </tr>
            </thead>
            <tbody v-if="feedbacks.length > 0">
                <tr v-for="(list,index) in feedbacks" v-bind:key="index" class="fs-12">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <h5 class="fs-12 mb-0 fw-semibold text-primary">{{list.name}}</h5>
                    </td>
                    <td>{{list.comment}}</td>
                    <td class="text-center">{{list.rate}}</td>
                    <td class="text-center">
                        <div class="fs-16 align-middle text-warning">
                            <template v-for="i in 5" :key="i">
                                <i v-if="list.rate >= i" class="ri-star-fill"></i>
                                <i v-else-if="list.rate >= i - 0.5" class="ri-star-half-fill"></i>
                                <i v-else class="ri-star-line"></i>
                            </template>
                        </div>
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
    props: ['feedbacks'],
}
</script>