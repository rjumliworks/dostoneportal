<template lang="">
    <Head title="PAYROLL"/>
    <div class="auth-page-wrapper d-flex min-vh-100">
        <div class="auth-page-content">
            <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
                <div class="file-manager-content w-100 p-4 pb-0" ref="box">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap align-middle">
                            <thead class="text-white">
                                <tr class="bg-primary">
                                    <th colspan="22" class="text-center align-middle">{{ cutoff.title }}</th>
                                </tr>
                                <tr class="fs-11 bg-info">
                                    <th class="text-center align-middle" rowspan="3" style="width: 5%;">No.</th>
                                    <th class="text-center align-middle" rowspan="3" style="width: 11%;">Name</th>
                                    <!-- <th class="text-center align-middle" rowspan="2" style="width: 12%;">Position</th> -->
                                    <th class="text-center align-middle" rowspan="3" style="width: 3%;">SG</th>
                                    <th class="text-center" colspan="2"  style="width: 12%;">COMPENSATION</th>
                                    <th class="text-center" colspan="15">DEDUCTIONS</th>
                                    <th class="text-center align-middle" rowspan="3" style="width: 4%;">Net Amount Due</th>
                                    <th class="text-center align-middle" rowspan="3" style="width: 4%;">1st Quicena</th>
                                </tr>
                                <tr style="font-size: 9px;" class="bg-info">
                                    <th class="text-center align-middle" rowspan="2">Salary</th>
                                    <th class="text-center align-middle" rowspan="2">Gross</th>
                                    <th class="text-center align-middle" rowspan="2">Philhealth</th>
                                    <th class="text-center align-middle" rowspan="2">Pag-ibig I</th>
                                    <th class="text-center align-middle" rowspan="2">Pag-ibig II</th>
                                    <th class="text-center align-middle" rowspan="2">HDMF Housing Loan</th>
                                    <th class="text-center align-middle" colspan="2" style="width: 10%;">Pag-ibig Loan</th>
                                    <th class="text-center align-middle" colspan="2" style="width: 10%;">GSIS Life</th>
                                    <th class="text-center align-middle" rowspan="2">Policy Loan</th>
                                    <th class="text-center align-middle" rowspan="2">Multi-Purpose Loan</th>
                                    <th class="text-center align-middle" rowspan="2">SIKAT MDABP</th>
                                    <th class="text-center align-middle" rowspan="2">SSS Contribution</th>
                                    <th class="text-center align-middle" rowspan="2">AMAPHIL</th>
                                    <th class="text-center align-middle" rowspan="2">Withholding Tax</th>
                                    <th class="text-center align-middle" rowspan="2">Total Deduction</th>
                                </tr>
                                <tr style="font-size: 8px;" class="bg-info">
                                    <th class="text-center align-middle">MPL</th>
                                    <th class="text-center align-middle">CAL</th>
                                    <th class="text-center align-middle">CM</th>
                                    <th class="text-center align-middle">PY</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white fs-10">
                                <tr v-for="(list,index) in cutoff.payrolls" v-bind:key="index" @click="selectRow(index)" style="cursor: pointer;" :class="{ 'bg-info-subtle fw-semibold': selectedRow === index }">
                                    <td class="text-center"> {{ index+1}}</td>
                                    <td class="align-middle">
                                        <h5 class="fs-10 mb-0 fw-semibold text-primary text-uppercase">{{list.name}}</h5>
                                        <p class="fs-10 text-muted mb-0">{{list.position}}</p>
                                    </td>
                                    <td class="text-center"> {{ list.grade}}</td>
                                    <td class="text-center"> {{ list.salary}}</td>
                                    <td class="text-center"> {{ list.salary}}</td>
                                    <td class="text-center" v-for="(name, i) in deductionHeaders" :key="'deduction-' + i">
                                       {{ (parseFloat(list.deductions[name]) !== 0) ? formatMoney(list.deductions[name]) : '-' }}
                                    </td>
                                    <td class="text-center text-danger"> {{ list.deduction}}</td>
                                    <td class="text-center fw-semibold"> {{ list.netpay}}</td>
                                    <td class="text-center text-primary"> {{ formatMoney(list.first)}}</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light tfoot-fixed fw-bold fs-10">
                                <tr>
                                    <td colspan="3">Grand Total</td>
                                    <td class="text-center">{{ formatMoney(cutoff.totals.salary.toFixed(2)) }}</td>
                                    <td class="text-center">{{ formatMoney(cutoff.totals.salary.toFixed(2))}}</td>
                                    <td class="text-center" v-for="(name, i) in deductionHeaders" :key="'footer-' + i">
                                        {{ formatMoney((cutoff.totals.deductions[name] || 0).toFixed(2)) }}
                                    </td>
                                    <td class="text-center text-danger">{{ formatMoney(cutoff.totals.deduction.toFixed(2)) }}</td>
                                    <td class="text-center">{{ formatMoney(cutoff.totals.net.toFixed(2)) }}</td>
                                    <td class="text-center">{{ formatMoney(cutoff.totals.first.toFixed(2)) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    layout: null,
    props: ['cutoff','deductionHeaders'],
    data(){
        return {
            selectedRow: null
        }
    },
    methods: {
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        selectRow(index) {
            this.selectedRow = (this.selectedRow == index) ? null : index;
        }
    }
}
</script>
<style scoped>
.auth-page-wrapper .auth-page-content {
    padding-bottom: 0px;
  width: 100%;
  overflow: hidden;
  background-color: #f3f3f9;
}
.file-manager-sidebar {
  min-width: 24%;
  max-width: 24%;
  height: calc(100vh - 92px);
}

</style>
