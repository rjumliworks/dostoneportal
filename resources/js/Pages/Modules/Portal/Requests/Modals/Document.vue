<template>
    <b-modal v-model="showModal" :style="(form.type_id == 175) ? '--vz-modal-width: 600px;' : '--vz-modal-width: 1200px;'" header-class="p-3 bg-light" title="Add Document" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 p-2">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Document Type" :message="form.errors.type_id"/>
                    <Multiselect
                        v-model="form.type_id" 
                        :options="attachments"
                        label="name"
                         @input="handleInput('type_id')"
                        placeholder="Select type"
                    />
                </BCol>
                <BCol lg="12" class="mt-0 mb-0">
                    <hr class="text-muted"/>
                </BCol>
                <template v-if="form.type_id == 176">
                    <div class="row g-2 mt-n2">
                        <div class="col-md-12">
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
                                            <h5 class="mb-0 fs-14"><span class="text-body">Itinerary of Travel</span></h5>
                                            <p class="text-muted text-truncate-two-lines fs-12">Lists</p>
                                        </div>
                                        <div class="flex-grow-0">
                                            <b-button type="button" variant="primary" @click="openIot">
                                                <i class="ri-add-circle-fill align-bottom me-1"></i> Add
                                            </b-button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body bg-white rounded-bottom">
                                    <div class="table-card">
                                        <table class="table align-middle table-bordered table-centered mb-0">
                                            <thead class="table-light align-middle">
                                                <tr class="fs-11">
                                                    <th rowspan="2" style="width: 15%;" class="text-center">Date</th>
                                                    <th rowspan="2" class="text-center">Place(s) Visited</th>
                                                    <th colspan="2" style="width: 17%;" class="text-center">Time</th>
                                                    <th rowspan="2" style="width: 12%;" class="text-center">Means of Transp'n</th>
                                                    <th colspan="4" style="width: 36%;" class="text-center">Allowable Expenses (in Peso)</th>
                                                </tr>
                                                <tr class="fs-11 text-center">
                                                    <th style="width: 7.5%;">Departure</th>
                                                    <th style="width: 7.5%;">Arrival</th>
                                                    <th style="width: 7.5%;">Transp'n</th>
                                                    <th style="width: 7.5%;">Per Diems</th>
                                                    <th style="width: 7.5%;">Others</th>
                                                    <th style="width: 7.5%;">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="iot.length">
                                                <tr class="text-center" v-for="(item, index) in iot" :key="index">
                                                    <td>{{ item.date }}</td>
                                                    <td>{{ item.place }}</td>
                                                    <td>{{ item.departure }}</td>
                                                    <td>{{ item.arrival }}</td>
                                                    <td>{{ item.mode }}</td>
                                                    <td>{{ (item.transportation ) ? item.transportation : '-'}}</td>
                                                    <td>{{ (item.perdiem ) ? item.perdiem : '-'}}</td>
                                                    <td>{{ (item.others) ? item.others : '-' }}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted"> No found on the list.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else-if="form.type_id == 177">
                    <BCol lg="6" class="mt-n2">
                        <InputLabel value="Persons Contacted"/>
                        <TextInput v-model="tar.person" type="text" class="form-control" placeholder="Please enter st.,road" @input="handleInput('address')" :light="true" />
                    </BCol>
                    <BCol lg="6" class="mt-n2">
                        <InputLabel value="Designation, Office/Agency Address"/>
                        <TextInput v-model="tar.designation" type="text" class="form-control" placeholder="Please enter st.,road" @input="handleInput('address')" :light="true" />
                    </BCol>
                    <BCol lg="12" class="mt-0">
                        <InputLabel for="name" value="Output / Impact /Result" :message="form.errors.ouput"/>
                        <div class="position-relative">
                            <textarea
                                id="attribute" v-model="tar.output" rows="1"
                                class="form-control" placeholder="Please enter details"
                                style="background-color: #f5f6f7; padding-bottom: 28px;" 
                            ></textarea>

                            <span class="position-absolute" style="top: -18px; font-size: 11px; right: 8px; background-color: white; cursor: pointer; color: #0d6efd; font-weight: 500;" @click="improveText" :class="{ 'text-muted': loading }">
                                <span v-if="loading">Improving...</span>
                                <span v-else>Improve with AI</span>
                            </span>
                        </div>
                    </BCol>
                    <BCol lg="12" class="mt-0 mb-0">
                        <hr class="text-muted"/>
                    </BCol>
                    <BCol lg="3" class="mt-n2">
                        <InputLabel value="Traveling Expenses"/>
                        <Amount @amount="setAmount('expenses', $event)" :readonly="false"/>
                    </BCol>
                    <BCol lg="3" class="mt-n2">
                        <InputLabel value="Less Amount"/>
                        <Amount @amount="setAmount('less', $event)" :readonly="false"/>
                    </BCol>
                    <BCol lg="3" class="mt-n2">
                        <InputLabel value="Others"/>
                        <Amount @amount="setAmount('others', $event)" :readonly="false"/>
                    </BCol>
                    <BCol lg="3" class="mt-n2">
                        <InputLabel value="Total"/>
                        <Amount :value="totalAmount" :readonly="true"/>
                    </BCol>
                    <BCol lg="12" class="mt-n1 mb-0">
                        <hr class="text-muted"/>
                    </BCol>
                    <BCol lg="12" class="mt-n2">
                        <InputLabel for="name" value="Proposed Follow-up Activities" :message="form.errors.ouput"/>
                        <div class="position-relative">
                            <textarea
                                id="attribute" v-model="tar.output" rows="1"
                                class="form-control" placeholder="Please enter details"
                                style="background-color: #f5f6f7; padding-bottom: 28px;" 
                            ></textarea>

                            <span class="position-absolute" style="top: -18px; font-size: 11px; right: 8px; background-color: white; cursor: pointer; color: #0d6efd; font-weight: 500;" @click="improveText" :class="{ 'text-muted': loading }">
                                <span v-if="loading">Improving...</span>
                                <span v-else>Improve with AI</span>
                            </span>
                        </div>
                    </BCol>
                </template>
                <template v-else-if="form.type_id == 178">
                    <BCol lg="12" style="margin-top: -3px; margin-bottom: 5px;">
                        <div class="custom-control custom-radio">
                            <input type="radio" id="1" class="custom-control-input me-2" value="1" v-model="ctc.condition">
                            <label class="custom-control-label fs-12 fw-normal" for="1">Strictly in accordance with the approved itenerary.</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="2" class="custom-control-input me-2" value="2" v-model="ctc.condition">
                            <label class="custom-control-label fs-12 fw-normal" for="2">Cut short as explained below. Excess payment in the amount of ___ was refunded on O.R. No. ___ dated ___ </label>
                        </div>
                         <div class="custom-control custom-radio">
                            <input type="radio" id="3" class="custom-control-input me-2" value="3" v-model="ctc.condition">
                            <label class="custom-control-label fs-12 fw-normal" for="3">Extended as explained below, additional itenerary was submitted</label>
                        </div>
                         <div class="custom-control custom-radio">
                            <input type="radio" id="4" class="custom-control-input me-2" value="4" v-model="ctc.condition">
                            <label class="custom-control-label fs-12 fw-normal" for="4">Other deviations as explained below: </label>
                        </div>
                        <hr class="text-muted mt-2"/>
                    </BCol>
                    <BCol lg="12" class="mt-n2 mb-3" v-if="ctc.condition == 2">
                        <div class="row">
                            <div class="col-md-4">
                                <InputLabel value="Amount"/>
                                <Amount @amount="setAmount('expenses', $event)" :readonly="false"/>
                            </div>
                            <div class="col-md-4">
                                <InputLabel value="O.R. No."/>
                                <TextInput v-model="ctc.or" type="text" class="form-control" placeholder="Please enter st.,road" @input="handleInput('address')" :light="true" />
                            </div>
                            <div class="col-md-4">
                                <InputLabel value="Date"/>
                                <TextInput v-model="ctc.date" type="date" class="form-control" placeholder="Please enter st.,road" @input="handleInput('address')" :light="true" />
                            </div>
                        </div>
                    </BCol>
                    <BCol lg="12" class="mt-n2">
                        <InputLabel for="name" value="Explanation / Justification" :message="form.errors.ouput"/>
                        <div class="position-relative">
                            <textarea
                                id="attribute" v-model="tar.output" rows="1"
                                class="form-control" placeholder="Please enter details"
                                style="background-color: #f5f6f7; padding-bottom: 28px;" 
                            ></textarea>

                            <span class="position-absolute" style="top: -18px; font-size: 11px; right: 8px; background-color: white; cursor: pointer; color: #0d6efd; font-weight: 500;" @click="improveText" :class="{ 'text-muted': loading }">
                                <span v-if="loading">Improving...</span>
                                <span v-else>Improve with AI</span>
                            </span>
                        </div>
                    </BCol>
                </template>
                <template v-else-if="form.type_id == 179">
                    <div class="row g-2 mt-n2">
                        <template v-for="(item, index) in cerr.lists" :key="index">
                            <div class="col-md-6 mb-n1">
                                <TextInput v-model="item.particulars" type="text" class="form-control" placeholder="Enter particulars" :light="true"/>
                            </div>
                            <div class="col-md-3 mb-n1">
                                <Amount :modelValue="item.amount" @amount="item.amount = $event" :readonly="false"/>
                            </div>
                            <div class="col-md-3 mb-n1">
                                <div class="d-flex">
                                    <div style="width: 100%;">
                                        <TextInput v-model="item.date" type="date" class="form-control" :light="true"/>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <b-button @click="removeCerr(index)" variant="soft-danger" class="waves-effect waves-light ms-1 me-n1"><i class="ri-delete-bin-fill"></i></b-button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="row p-2">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary mt-2" type="button" @click="addCerr">
                                + Add Row
                            </button>
                        </div>
                    </div>
                    <!-- Add button -->
                </template>
            </BRow>
        </form> 
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
    <Iot @success="pushIot" ref="iot"/>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import Iot from './Iot.vue';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, Amount, Iot },
    props: ['attachments'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                type_id: null,
                status_id: 42,
                request_id: null,
                content: null,
                option: 'document'
            }),
            tar: {
                person: null,
                designation: null,
                output: null,
                expenditures: {
                    expenses: 0,
                    less: 0,
                    others: 0
                },
                followup: null
            },
            ctc: {
                amount: null,
                or: null,
                date: null,
                condition: null,
                explanation: null
            },
            cerr : {
                lists: [
                    {
                        particulars: null,
                        amount: null,
                        date: null,
                    }
                ]
            },
            iot: [
                // {
                //     date :null,
                //     place: null,
                //     departure: null,
                //     arrival: null,
                //     means_of_transpo: null,
                //     fee_transpo: null,
                //     per_diem: null,
                //     other: null,
                //     total: null
                // }
            ],
            loading: false,
            showModal: false
        }
    },
    computed: {
        totalAmount() {
            const { expenses, less, others } = this.tar.expenditures;
            return (Number(expenses) || 0) + (Number(less) || 0) + (Number(others) || 0);
        }
    },
    methods: { 
        setAmount(type, val) {
            const numericVal = parseFloat(val.replace(/[^0-9.-]+/g, '')) || 0;
            this.tar.expenditures[type] = numericVal;
        },
        show(key){
            this.form.request_id = key;
            this.showModal = true;
        },
        async improveText() {
            this.loading = true;
            axios.post('/improve', {
                purpose: this.tar.output, 
            })
            .then(res => {
                this.tar.output = res.data.improved.trim();
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                this.loading = false;
            });
        },
        submit(){
            switch(this.form.type_id){
                case 179:
                    this.form.content = this.cerr;
                break;
                case 178:
                    this.form.content = this.ctc;
                break;
                case 177:
                    this.form.content = this.tar;
                break;
                case 176:
                    this.form.content = this.iot;
                break;
            }

            this.form.put('/requests/update',{
                preserveScroll: true,
                // forceFormData: true, 
                onSuccess: (response) => {
                    this.form.clearErrors();
                    this.form.reset();
                    this.hide();
                },
            });
        },
        openIot(){
            this.$refs.iot.show();
        },
        pushIot(data){
            console.log(data);
            this.iot.push(data);
        },
        addCerr() {
            this.cerr.lists.push({
                particulars: '',
                amount: null,
                date: ''
            })
        },
        removeCerr(index) {
            this.cerr.lists.splice(index, 1)
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>