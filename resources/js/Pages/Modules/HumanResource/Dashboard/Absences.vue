<template>
    <Head title="Absences"/>
    <PageHeader title="Absences" pageTitle="Report" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-close-circle-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">Number of Absences</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Monthly absences for {{ month }} {{ year }}.</p>
                        </div>
                    </div>
                </div>
                <div class="car-body bg-white border-bottom shadow-none">
                    <b-row class="mb-2 ms-1 me-1 align-items-center" style="margin-top: 12px;">
                        <b-col lg="auto">
                            <div class="input-group mb-1">
                                <Multiselect class="white" style="width: 170px;" :options="months" v-model="month" :searchable="true" :canClear="false" placeholder="Select Month" />
                                <input type="text" v-model="year" class="form-control" style="width: 90px;">
                                <span @click="print()" class="input-group-text" v-b-tooltip.hover title="Print" style="cursor: pointer;">
                                    <i class="ri-printer-line search-icon"></i>
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card" style="height: calc(100vh - 320px); overflow: auto;">
                        <table class="table table-bordered table-centered align-middle mb-1">
                            <thead class="bg-primary fs-12 thead-fixed">
                                <tr class="text-white">
                                    <th class="text-center" style="width: 4%;">#</th>
                                    <th>Name</th>
                                    <th class="text-center" style="width: 20%;">Absences</th>
                                    <th class="text-center" style="width: 20%;">Total</th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-13">
                                <template v-for="group in lists" v-bind:key="group.division">
                                    <tr class="bg-primary-subtle">
                                        <td colspan="4" class="fw-semibold">{{ group.division }}</td>
                                    </tr>
                                    <tr v-for="(user,index) in group.users" v-bind:key="user.user_id">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td>{{ user.name }}</td>
                                        <td class="text-center">{{ user.absences }}</td>
                                        <td class="text-center">{{ user.total }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BRow>
</template>
<script>
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Multiselect },
    props: ['years','divisions'],
    data(){
        return {
            lists: [],
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            month: new Date().toLocaleString('default', { month: 'long' }),
            year: new Date().getFullYear(),
        }
    },
    watch: {
        month(){
            this.fetch();
        },
        year(){
            this.fetch();
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(){
            axios.get('/absences',{
                params : {
                    month: this.month,
                    year: this.year,
                    option: 'list'
                }
            })
            .then(response => {
                this.lists = response.data;
            })
            .catch(err => console.log(err));
        },
        print(){
            window.open('/absences?option=print&month='+this.month+'&year='+this.year);
        }
    }
}
</script>
