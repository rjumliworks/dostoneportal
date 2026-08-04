<template>
    <Head title="Dashboard"/>
    <PageHeader title="Dashboard" pageTitle="List" />
    <b-row class="g-2 mt-n2">
        <div class="col-md-12">
            <b-card no-body class="bg-white-subtle border shadow-none">
                <b-card-body>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-lg-row flex-column">
                                <div class="flex-grow-1">
                                    <h4 class="fs-14 mb-0">{{monthName}} Summary View</h4>
                                    <p class="text-muted mb-0">Here's what's happening with the laboratory for month of {{monthName}}.</p>
                                </div>
                                <div class="mt-3 mt-lg-0">
                                    <form action="javascript:void(0);">
                                        <div class="row g-3 mb-0 align-items-center">
                                            <div class="col-sm-auto">
                                                <div class="input-group">
                                                    <select v-model="month" style="width: 170px;"  class="form-select" aria-label="Default select example">
                                                        <option :value="null">All Months</option>
                                                        <option :value="list" v-for="list in months" v-bind:key="list">{{list}}</option>
                                                    </select>
                                                    <select v-model="year" style="width: 150px;"  class="form-select" aria-label="Default select example">
                                                        <option :value="null">All Years</option>
                                                        <option :value="list" v-for="list in years" v-bind:key="list">{{list}}</option>
                                                    </select>
                                                    <div class="input-group-text bg-primary border-primary text-white">
                                                        <i class="ri-calendar-2-line"></i> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-card-body>
            </b-card>
        </div>
    </b-row>
    <simplebar data-simplebar style=" height: calc(100vh - 300px); overflow-x: hidden; overflow-y: auto;">
        <BRow class="g-3">
            <BCol xl="4">
                <Employee :employee="employee"/>
            </BCol>
            <BCol xl="8">
                <Bar ref="bar"/>
            </BCol>
            <BCol xl="4" class="mt-n1">
                <Absent :absences="absences"/>
            </BCol>
            <BCol xl="4" class="mt-n1">
                <Late :lates="lates"/>
            </BCol>
        </BRow>
    </simplebar>

</template>
<script>
import simplebar from "simplebar-vue";
import Bar from './Components/Bar.vue';
import Employee from './Components/Employee.vue';
import Late from './Components/Late.vue';
import Absent from './Components/Absent.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['employee','counts','divisions','years'],
    components: { PageHeader, Employee, Bar, Absent, Late, simplebar },
    data(){
        return {
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            absences: [],
            lates: [],
            month: new Date().toLocaleString('default', { month: 'long' }),
            year: new Date().getFullYear(),
        }
    },
    created(){
        this.fetch();
    },
    computed: {
        monthName() {
            return this.month ?? 'All Months';
        },
        filters() {
            return {
                year: this.year,
                month: this.month
            };
        }
    },
    watch: {
        filters: {
            handler() {
                this.fetch();
            },
            deep: true,
        },
        year(newVal) {
            this.$refs.bar.updateYear(newVal);
        }
    },
    methods: {
        fetch(){
            axios.get('/humanresource',{
                params : {
                    month: this.month,
                    year: this.year,
                    option: 'top'
                }
            })
            .then(response => {
                this.absences = response.data.absences;
                this.lates = response.data.lates;
            })
            .catch(err => console.log(err));
        }
    }
}
</script>