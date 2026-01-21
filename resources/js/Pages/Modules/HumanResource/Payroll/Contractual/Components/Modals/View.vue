<template>
    <!-- style="--vz-modal-width: 600px;" -->
    <b-modal v-model="showModal" style="--vz-modal-width: 680px;" header-class="p-3 bg-light" title="View Employee" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <BRow v-if="selected">
            <div class="col-md-12">
                <div>
                    <h4 class="fw-semibold text-primary text-uppercase fs-15 mb-1">{{ selected.name }}</h4>
                    <div class="hstack gap-3 flex-wrap fs-12">
                        <div><span class="text-muted">Status :</span> {{selected.type}}</div>
                        <div class="vr" style="width: 1px;"></div>
                        <div><span class="text-muted">Position :</span> {{selected.position}}</div>
                        <div class="vr" style="width: 1px;"></div>
                        <div><span class="text-muted">Salary :</span> {{selected.salary}}</div>
                    </div>
                </div>
            </div>  
            <div class="col-md-12 mb-n3">
                <hr class="text-muted"/>
            </div>
            <div class="col-md-12">
                <div class="row g-3 mt-0">
                    <div class="col-sm-4">
                        <div class="p-1 border border-dashed rounded">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-2-fill"></i></div>
                                </div>
                                <div class="flex-grow-1" v-if="selected.type == 'Plantilla'">
                                    <p class="text-muted mb-0 fs-12">1st Quincena :</p>
                                    <h5 class="mb-0 fs-12">{{ selected.first }}</h5>
                                </div>
                                <div class="flex-grow-1" v-else>
                                    <p class="text-muted mb-0 fs-12">{{type}} Quicena :</p>
                                    <h5 class="mb-0 fs-12">{{ selected.first }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-1 border border-dashed rounded">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-calendar-2-fill"></i></div>
                                </div>
                                <div class="flex-grow-1" v-if="selected.type == 'Plantilla'">
                                    <p class="text-muted mb-0 fs-12">2nd Quicena :</p>
                                    <h5 class="mb-0 fs-12">{{ selected.second }}</h5>
                                </div>
                                <div class="flex-grow-1" v-else>
                                    <p class="text-muted mb-0 fs-12">Tardiness :</p>
                                    <h5 class="mb-0 fs-12">{{ selected.tardiness }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-1 border border-dashed rounded">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2">
                                    <div class="avatar-title rounded bg-transparent text-primary fs-20"><i class="ri-hand-coin-fill"></i></div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-0 fs-12">Net Pay:</p>
                                    <h5 class="mb-0 fs-12">{{ selected.netpay }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-n3">
                <hr class="text-muted"/>
            </div>
            <div class="col-md-12 mt-3 mb-n4">
                <div class="card bg-light-subtle shadow-none border">
                    <div class="card-header bg-light-subtle">
                        <div class="d-flex mb-n3">
                            <div class="flex-shrink-0 me-3">
                                <div style="height:2rem;width:2rem;">
                                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                        <i class="ri-indeterminate-circle-fill text-danger fs-20"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0 mt-n1 fs-12"><span class="text-body">Deductions Overview</span></h5>
                                <p class="text-muted text-truncate-two-lines fs-11">Displays salary deductions with attendance and adjustment logs for accurate tracking.</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex flex-wrap gap-2 mt-n1" v-if="status == 'Draft'">
                                    <span @click="openDeduction()" v-b-tooltip.hover title="Add Deduction" style="cursor: pointer;"> 
                                        <i class="ri-add-circle-fill text-primary fs-24 search-icon"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-white rounded-bottom">
                        <div class="table-responsive table-card" style="max-height: 250px; overflow: auto;">
                            <table class="table align-middle mb-0">
                                <thead class="bg-primary fs-11 thead-fixed">
                                    <tr class="text-white">
                                        <th class="text-center" style="width: 8%;">#</th>
                                        <th>Name</th>
                                        <th class="text-center" style="width: 25%;">Type</th>
                                        <th class="text-center" style="width: 25%;">Amount</th>
                                        <th v-if="status == 'Draft'" style="width: 7%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="fs-11">
                                    <tr  v-for="(list,index) in selected.deductions" v-bind:key="index" :class="{ 'bg-danger-subtle': list.is_loan }">
                                        <td class="text-center">{{ index+1 }}</td>
                                        <td>{{list.name}}</td>
                                        <td class="text-center">
                                            {{(list.is_loan) ? 'Loan' : (list.is_contribution) ? 'Contribution' : '-'}}
                                        </td>
                                        <td class="text-center">{{list.amount}}</td>
                                        <td class="text-center" v-if="status == 'Draft'">
                                            <b-button @click="openEdit(list,index)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                                <i class="ri-pencil-fill align-bottom"></i>
                                            </b-button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="fs-12 tfoot-fixed">
                                    <tr class="bg-light">
                                        <th></th>
                                        <th colspan="2">Total Deduction</th>
                                        <th class="text-center">
                                            {{ formatMoney(totalDeductions) }}
                                        </th>
                                        <th v-if="status == 'Draft'"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-3" v-if="subtype == 'delete'">
                <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show material-shadow" role="alert">
                    <i class="ri-error-warning-line label-icon"></i>
                    <strong>Warning</strong> – Are you sure you want to remove the employee from the payroll?
                </div>
            </div>
        </BRow>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button v-if="subtype == 'delete'" @click="submit()" variant="danger" block>Remove</b-button>
        </template>
    </b-modal>
    <Deduction @remove="removeList" @update="updateList" :mydeductions="mydeductions" :deductions="deductions" ref="deduction"/>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Deduction from './Deduction.vue';
export default {
    components: { Deduction },
    props: ['type','deductions','status'],
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            showModal: false,
            index: null,
            subtype: null,
            form: useForm({
                id: null,
                option: 'remove'
            }),
            mydeductions: []
        }
    },
    computed: {
        totalDeductions() {
            if (!this.selected || !this.selected.deductions) return 0;

            return this.selected.deductions.reduce((sum, item) => {
                const cleanedAmount = String(item.amount)
                    .replace(/[₱,]/g, '')
                    .trim();

                return sum + parseFloat(cleanedAmount || 0);
            }, 0).toFixed(2);
        }
    },
    methods: { 
        show(data,type){
            this.subtype = type;
            this.selected = data;
            this.mydeductions = data.mydeductions;
            this.showModal = true;
        },
        openDeduction(){
            this.$refs.deduction.show(this.selected.id,this.selected.type,this.selected.deductions);
        },
        openEdit(list,index){
            this.index = index;
            this.$refs.deduction.edit(this.selected.id,this.selected.type,list);
        },
        updateList(data){
            this.selected = data;
        },
        submit(){
            this.form.id = this.selected.id;
            this.form.post('/payroll',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        removeList(data){
            this.selected = data;
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>