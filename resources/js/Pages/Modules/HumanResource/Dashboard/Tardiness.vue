<template>
    <Head title="Tardiness"/>
    <PageHeader title="Tardiness &amp; Undertime" pageTitle="Report" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">
                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-time-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">Monthly Summary Report on Tardiness and Undertime</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Minutes and occurrences of tardiness/undertime for {{ month }} {{ year }}.</p>
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
                        <b-col class="text-lg-end mb-1">
                            <span class="badge bg-warning-subtle text-warning border border-warning fs-11 me-1">6-9 occurrences</span>
                            <span class="badge bg-danger-subtle text-danger border border-danger fs-11">10+ occurrences</span>
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
                                    <th class="text-center" style="width: 13%;">Undertime Minutes</th>
                                    <th class="text-center" style="width: 13%;">Tardy Minutes</th>
                                    <th class="text-center" style="width: 17%;">Total Summary of Undertime/Tardy in Minutes</th>
                                    <th class="text-center" style="width: 16%;">Total Occurrences Undertime/Tardy</th>
                                    <th class="text-center" style="width: 10%;">Incomplete</th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-13">
                                <template v-for="group in lists" v-bind:key="group.division">
                                    <tr class="bg-primary-subtle">
                                        <td colspan="7" class="fw-semibold">{{ group.division }}</td>
                                    </tr>
                                    <tr v-for="(user,index) in group.users" v-bind:key="user.user_id" :class="rowClass(user.occurrences)">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td>{{ user.name }}</td>
                                        <td class="text-center">{{ user.undertime }}</td>
                                        <td class="text-center">{{ user.tardiness }}</td>
                                        <td class="text-center">{{ user.total }}</td>
                                        <td class="text-center">{{ user.occurrences }}</td>
                                        <td class="text-center">
                                            <span v-if="user.incomplete_count > 0" @click="openIncomplete(user)" class="badge bg-secondary-subtle text-secondary border border-secondary" style="cursor: pointer;" v-b-tooltip.hover title="View incomplete DTRs">
                                                {{ user.incomplete_count }}
                                            </span>
                                            <span v-else class="text-muted">0</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </BRow>
    <Incomplete ref="incomplete"/>
</template>
<script>
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Incomplete from './Modals/Incomplete.vue';
export default {
    components: { PageHeader, Multiselect, Incomplete },
    props: ['years','divisions'],
    data(){
        return {
            lists: [],
            incompleteList: [],
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
            axios.get('/tardiness',{
                params : {
                    month: this.month,
                    year: this.year,
                    option: 'list'
                }
            })
            .then(response => {
                this.lists = response.data.groups;
                this.incompleteList = response.data.incomplete;
            })
            .catch(err => console.log(err));
        },
        rowClass(occurrences){
            if(occurrences >= 10) return 'bg-danger-subtle';
            if(occurrences >= 6) return 'bg-warning-subtle';
            return '';
        },
        openIncomplete(user){
            const lists = this.incompleteList.filter(item => item.user_id === user.user_id);
            this.$refs.incomplete.show(lists, user.name);
        },
        print(){
            window.open('/tardiness?option=print&month='+this.month+'&year='+this.year);
        }
    }
}
</script>
