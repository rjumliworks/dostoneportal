<template>
    <div class="col-md-12">
        <div class="card bg-light-subtle shadow-none border">
            <div class="card-header bg-light-subtle">
                <div class="d-flex mb-n3">
                    <div class="flex-shrink-0 me-3">
                        <div style="height:2.5rem;width:2.5rem;">
                            <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                <i class="ri-team-fill text-primary fs-24"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-0 fs-14"><span class="text-body">List of Regular Employees</span></h5>
                        <p class="text-muted text-truncate-two-lines fs-12">Displays regular staff included in this payroll cycle. Use this list to review, add, or exclude employees before processing.</p>
                    </div>
                    <div class="flex-shrink-0" style="width: 45%;">
                        <!-- <b-button class="float-end" @click="openDeduction()" variant="light" block><i class="ri-add-circle-fill me-1"></i>Add Deduction</b-button> -->
                    </div>
                </div>
            </div>
            
            <div class="card bg-white border-bottom shadow-none" no-body>
                <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                    <b-col lg>
                        <div class="input-group mb-1">
                            <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                            <input type="text" placeholder="Search Employee" class="form-control" style="width: 20%;">
                            <b-button v-if="payroll.status.name == 'Draft'" type="button" variant="primary" @click="openAdd">
                                <i class="ri-user-add-fill align-bottom me-1"></i> Add Employee
                            </b-button>
                        </div>
                    </b-col>
                </b-row>
            </div>
            <div class="card-body bg-white rounded-bottom">
                <div class="table-responsive table-card" style="margin-top: -39px; height: calc(100vh - 440px); overflow: auto;">
                    <table class="table align-middle table-striped table-centered mb-0">
                        <thead class="table-light thead-fixed">
                            <tr class="fs-11">
                                <th style="width: 3%;"></th>
                                <th>Name</th>
                                <th style="width: 11%;" class="text-center">Salary</th>
                                <th style="width: 11%;" class="text-center">Tardiness</th>
                                <th style="width: 11%;" class="text-center">Deductions</th>
                                <th style="width: 10%;" class="text-center">1st Quincena</th>
                                <th style="width: 10%;" class="text-center">2nd Quincena</th>
                                <th style="width: 13%;" class="text-center">Net Amount Due</th>
                                <th style="width: 6%;"></th>
                            </tr>
                        </thead>
                        <tbody class="table-white fs-12">
                            <tr v-for="(list,index) in sortedPayrolls" v-bind:key="index" @click="selectRow(index)" :class="{ 'bg-info-subtle': selectedRow === index }">
                                <td class="text-center">{{ index + 1 }}.</td>
                                <td>
                                    <h5 class="fs-13 mb-0 fw-semibold text-primary text-uppercase">{{list.name}}</h5>
                                    <p class="fs-12 text-muted mb-0">{{list.position}}</p>
                                </td>
                                <td class="text-center">{{ list.salary }}</td>
                                <td class="text-center">{{ list.tardiness }}</td>
                                <td class="text-center">{{ list.deduction }}</td>
                                <td class="text-center">{{ list.first}}</td>
                                <td class="text-center">{{ list.second }}</td>
                                <td class="text-center">{{ list.netpay }}</td>
                                <td class="text-end">
                                    <b-button v-if="payroll.status.name == 'Draft'" @click="openView(list,'delete')" variant="soft-danger" class="me-1" v-b-tooltip.hover title="Remove" size="sm">
                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                    </b-button>
                                    <b-button @click="openView(list,'view')" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                        <i class="ri-eye-fill align-bottom"></i>
                                    </b-button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-light text-primary tfoot-fixed fw-bold fs-12">
                            <tr>
                                <td :colspan="(payroll.cycle.is_regular) ? 6 : 4"></td>
                                <td class="text-center">Grand Total</td>
                                <td class="text-center">{{ formatMoney(payroll.total) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer">
              
            </div>
        </div>
    </div>
    <Add :is_regular="is_regular" :id="payroll.id" ref="add"/>
    <View :type="payroll.type" :deductions="dropdowns.deductions" :status="payroll.status.name" ref="view"/>
</template>
<script>
import simplebar from "simplebar-vue";
import View from './Modals/View.vue';
import Add from './Modals/Add.vue';
export default {
    components: { simplebar, View, Add },
    props: ['payroll','is_regular','dropdowns'],
    data(){
        return {
            lists: [],
            index: null,
            selectedRow: null
        }
    },
    computed: {
        userOptions() {
            return this.payroll?.payrolls?.map(user => ({
                value: user.id,
                name: user.name,
                type: user.type
            })) || [];
        },
        sortedPayrolls() {
            return this.payroll?.payrolls?.slice().sort((a, b) => {
                return a.name.localeCompare(b.name);
            }) || [];
        }
    },
    methods: {
        openVerify(){
            this.$refs.verify.show();
        },
        openAdd(){
            this.$refs.add.show();
        },
        openView(payroll,type){
            this.$refs.view.show(payroll,type);
        },
        selectRow(index) {
            this.selectedRow = index;
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>