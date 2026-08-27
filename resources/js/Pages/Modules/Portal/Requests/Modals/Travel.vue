<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-light" title="File Travel Order" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" @submit.prevent>
            <div id="travel-progress-bar" class="progress-nav mb-4 position-relative">
                <div class="progress step-progress progress-back" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" :style="`width: ${progressbarvalue}%;`"></div>
                </div>

                <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
                    <li class="nav-item" role="presentation">
                        <div class="step-wrapper">
                            <b-button variant="link" type="button" class="nav-link rounded-pill fs-12" :class="{ active: activeTab == 1, done: activeTab > 1 }" @click="toggleTab(1, 0)">1</b-button>
                            <div class="step-title">Select Event</div>
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">
                        <div class="step-wrapper">
                            <b-button variant="link" type="button" class="nav-link rounded-pill fs-12" :class="{ active: activeTab == 2, done: activeTab > 2 }" @click="toggleTab(2, 25)">2</b-button>
                            <div class="step-title">Travel Details</div>
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">
                        <div class="step-wrapper">
                            <b-button variant="link" type="button" class="nav-link rounded-pill fs-12" :class="{ active: activeTab == 3, done: activeTab > 3 }" @click="toggleTab(3, 50)">3</b-button>
                            <div class="step-title">Expenses</div>
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">
                        <div class="step-wrapper">
                            <b-button variant="link" type="button" class="nav-link rounded-pill fs-12" :class="{ active: activeTab == 4, done: activeTab > 4 }" @click="toggleTab(4, 75)">4</b-button>
                            <div class="step-title">Employees</div>
                        </div>
                    </li>
                    <li class="nav-item" role="presentation">
                        <div class="step-wrapper">
                            <b-button variant="link" type="button" class="nav-link rounded-pill fs-12" :class="{ active: activeTab == 5, done: activeTab > 5 }" @click="toggleTab(5, 100)">5</b-button>
                            <div class="step-title">Confirmation</div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade" :class="activeTab == 1 && 'show active'">
                    <BRow class="g-3">
                        <BCol lg="12" v-if="!selectedEvent">
                            <InputLabel value="Event" :message="form.errors.event_id"/>
                            <Multiselect
                                v-model="selectedEvent"
                                :options="eventResults"
                                mode="single"
                                object
                                :searchable="true"
                                :loading="eventLoading"
                                label="name"
                                @search-change="checkEventSearchStr"
                                placeholder="Search by event title"
                            />
                            <p class="fs-11 text-muted mt-2">Search and select the event this travel is related to.</p>
                        </BCol>
                        <BCol lg="12" v-else>
                            <div class="border border-dashed bg-light-subtle rounded p-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="fw-semibold fs-14 text-primary mb-1">{{ selectedEvent.name }}</h5>
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            <span v-if="selectedEvent.type" class="badge bg-primary-subtle text-primary fs-10">{{ selectedEvent.type.name }}</span>
                                            <span v-if="selectedEvent.audience" class="badge bg-info-subtle text-info fs-10">{{ selectedEvent.audience.name }}</span>
                                            <span v-if="selectedEvent.mode" class="badge bg-secondary-subtle text-secondary fs-10">{{ selectedEvent.mode.name }}</span>
                                            <span v-if="selectedEvent.is_host" class="badge bg-success-subtle text-success fs-10">Host</span>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <b-button @click="resetEvent" type="button" variant="light" size="sm" v-b-tooltip.hover title="Change Event">
                                            <i class="ri-close-line"></i>
                                        </b-button>
                                    </div>
                                </div>
                                <!-- <hr class="text-muted mb-2 mt-2"/> -->
                                <div class="border border-dashed rounded p-3 mt-2">
                                <p class="mb-1 fs-12 text-muted">
                                    <i class="ri-map-pin-fill align-bottom me-1"></i>
                                    {{ selectedEvent.location?.name || 'No location set for this event' }}<span v-if="selectedEvent.location?.address">, {{ selectedEvent.location.address }}</span>
                                </p>
                                <p v-if="eventDates.length" v-for="(d, i) in eventDates" :key="i" class="mb-0 fs-12 text-muted">
                                    <i class="ri-calendar-event-fill align-bottom me-1"></i>
                                    {{ d.start }}<span v-if="d.end && d.end !== d.start"> - {{ d.end }}</span><span v-if="d.time"> &middot; {{ d.time }}</span>
                                </p>
                                <p v-else class="mb-0 fs-12 text-muted">
                                    <i class="ri-calendar-event-fill align-bottom me-1"></i>No date set for this event
                                </p>
                                </div>
                                <!-- <hr class="mb-n2 mt-2 text-muted"/> -->
                                <div class="mt-3">
                                    <InputLabel value="Purpose" :message="form.errors.purpose"/>
                                    <Textarea v-model="form.purpose" class="travel-purpose" style="height: 70px;" placeholder="Please enter the purpose of this travel" @input="handleInput('purpose')"/>
                                </div>
                            </div>
                           
                        </BCol>
                    </BRow>
                </div>

                <div class="tab-pane fade" :class="activeTab == 2 && 'show active'">
                    <div class="mb-n2 mt-0">
                        <h5 class="fs-12 mb-0 text-primary fw-semibold"><i class="ri-suitcase-line me-1"></i>Travel Details</h5>
                        <p class="fs-10 text-muted">Provide the essential information for this travel request.</p>
                    </div>
                    <div class="border border-dashed bg-light-subtle rounded p-3">
                    <BRow class="g-3 mt-n4">
                        <BCol md="6">
                            <label>Travel Date <span v-if="form.errors.date" class="text-danger" style="font-size: 9px;">({{ form.errors.date }})</span></label>
                            <div>
                                <flat-pickr ref="datepicker"
                                placeholder="Select date"
                                v-model="form.date"
                                :config="config"
                                @input="handleInput('date')"
                                class="form-control flatpickr-input" id="calendar">
                                </flat-pickr>
                            </div>
                        </BCol>

                        <BCol md="6">
                            <InputLabel for="name" value="Departure Time" :message="form.errors.time"/>
                            <TextInput id="name" v-model="form.time" type="time" class="form-control" placeholder="Please enter time" @input="handleInput('time')"/>
                        </BCol>

                        <BCol md="6" class="mt-0">
                            <InputLabel for="name" value="Remarks" :message="form.errors.remarks"/>
                            <Textarea id="name" v-model="form.remarks" style="height: 70px;" placeholder="Please enter remarks" @input="handleInput('remarks')"/>
                        </BCol>
                        <BCol md="6" class="mt-0 mb-2">
                            <InputLabel value="Attachment"/>
                            <file-pond class="travel-attachment" name="pdf" ref="pond" allow-multiple="false" max-files="1" accepted-file-types="application/pdf"
                            label-idle='Drag &amp; Drop your PDF or <span class="filepond--label-action">Browse</span>'
                            :allow-process="false" @addfile="handleAddFile"/>
                        </BCol>

                        <BCol :lg="(form.mode_id == 150) ? 4 : (form.mode_id == 151 ? 6 : 12)" class="mt-0">
                            <InputLabel for="name" value="Mode of Travel" :message="form.errors.mode_id"/>
                            <Multiselect
                                v-model="form.mode_id"
                                :options="dropdowns.modes"
                                label="name"
                                @input="handleInput('mode_id')"
                                placeholder="Select type"
                            />
                        </BCol>
                        <BCol v-if="form.mode_id == 151" lg="6" class="mt-0">
                            <InputLabel for="name" value="Transportation" :message="form.errors.transpo_id"/>
                            <Multiselect
                                v-model="form.transpo_id"
                                :options="dropdowns.transportations"
                                label="name"
                                @input="handleInput('transpo_id')"
                                placeholder="Select"
                            />
                        </BCol>
                        <BCol v-if="form.mode_id == 150" lg="4" class="mt-0">
                            <InputLabel for="name" value="Vehicle" :message="form.errors.vehicle"/>
                            <Multiselect
                                v-model="form.vehicle"
                                :options="vehicles"
                                label="name"
                                object
                                @input="handleInput('vehicle_id')"
                                placeholder="Select Vehicle"
                            />
                        </BCol>
                        <BCol v-if="form.mode_id == 150" lg="4" class="mt-0">
                            <InputLabel for="name" value="Driver" :message="form.errors.driver_id"/>
                            <Multiselect
                                v-model="form.driver_id"
                                :options="drivers"
                                label="name"
                                @input="handleInput('driver_id')"
                                placeholder="Select Driver"
                            />
                        </BCol>
                    </BRow>
                    </div>
                </div>

                <div class="tab-pane fade" :class="activeTab == 3 && 'show active'">
                    <div class="mb-n2 mt-0">
                        <h5 class="fs-12 mb-0 text-primary fw-semibold"><i class="ri-wallet-3-fill me-1"></i>Travel Expenses</h5>
                        <p class="fs-10 text-muted">Please check the expenses that apply to this travel request. <span class="text-danger">{{ form.errors.expenses }}</span></p>
                    </div>
                    <div class="border border-dashed bg-light-subtle rounded p-3">
                        <BRow class="g-3 mt-n4">
                            <BCol lg="12">
                                <InputLabel for="name" value="Travel Expense" :message="form.errors.expense_id"/>
                                <Multiselect
                                    v-model="form.expense_id"
                                    :options="dropdowns.expenses"
                                    label="name"
                                    @input="handleInput('expense_id')"
                                    placeholder="Select type"
                                />
                            </BCol>
                            <BCol lg="12">
                                <hr class="text-muted mt-1 mb-0"/>
                            </BCol>
                            <BCol lg="12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio mb-1">
                                            <input type="checkbox" id="customRadio1" class="form-check-input me-2" :value="1" v-model="form.expenses">
                                            <label class="custom-control-label fw-normal fs-12" for="customRadio1">Accommodation <span class="text-muted">(Actual)</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio mb-1">
                                            <input type="checkbox" id="customRadio2" class="form-check-input me-2" :value="2" v-model="form.expenses">
                                            <label class="custom-control-label fw-normal fs-12" for="customRadio2">Accommodation <span class="text-muted">(Per Diem)</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio">
                                            <input type="checkbox" id="customRadio3" class="form-check-input me-2" :value="3" v-model="form.expenses">
                                            <label class="custom-control-label fw-normal fs-12" for="customRadio3">Incidental Expenses</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio">
                                            <input type="checkbox" id="customRadio4" class="form-check-input me-2" :value="4" v-model="form.expenses">
                                            <label class="custom-control-label fw-normal fs-12" for="customRadio4">Meals</label>
                                        </div>
                                    </div>
                                </div>
                            </BCol>
                        </BRow>
                    </div>
                </div>

                <div class="tab-pane fade" :class="activeTab == 4 && 'show active'">
                    <div class="mb-n2 mt-0">
                        <h5 class="fs-12 mb-0 text-primary fw-semibold"><i class="ri-team-fill me-1"></i>Employees</h5>
                        <p class="fs-10 text-muted">Select all employees who are part of this travel.</p>
                    </div>
                    <div class="border border-dashed bg-light-subtle rounded p-3">
                        <BRow class="g-3">
                            <BCol lg="12" class="mt-2">
                                <InputLabel for="role" value="Employees" :message="form.errors.tags"/>
                                <Multiselect
                                    v-model="form.tags"
                                    :options="employees"
                                    mode="tags"
                                    @search-change="checkSearchStr"
                                    :multiple="true"
                                    :searchable="true"
                                    :loading="isLoading"
                                    label="name"
                                    object
                                    @input="handleInput('tags')"
                                    :preserve-search="true"
                                    :filter-results="false"
                                    placeholder="Select Employee"
                                    ref="multiselect2"
                                    />
                                <p class="fs-11 text-muted mt-2">Select all employees who are part of this travel. Availability is checked when you submit.</p>
                            </BCol>
                        </BRow>
                    </div>
                </div>

                <div class="tab-pane fade" :class="activeTab == 5 && 'show active'">
                    <template v-if="submitErrors.length || conflicts.length">
                        <div class="mb-2 mt-n2 text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-20">
                                    <i class="ri-error-warning-fill"></i>
                                </span>
                            </div>
                            <h5 class="fs-13 mb-1 fw-semibold">Please Fix the Following</h5>
                            <p class="fs-11 text-muted mb-0">Your travel request could not be submitted.</p>
                        </div>
                        <div class="border border-dashed border-danger-subtle bg-danger-subtle rounded p-3">
                            <ul v-if="submitErrors.length" class="mb-0 ps-3 fs-12">
                                <li v-for="(err, i) in submitErrors" :key="'e'+i" class="mb-1">
                                    {{ err.message }} <b-button variant="link" type="button" size="sm" class="p-0 fs-11 text-decoration-none align-baseline" @click="toggleTab(err.tab, err.progress)">Fix</b-button>
                                </li>
                            </ul>
                            <div v-if="conflicts.length" :class="submitErrors.length ? 'mt-2 pt-2 border-top border-danger-subtle' : ''">
                                <p class="fs-12 fw-semibold mb-1 text-danger">Employee schedule conflicts</p>
                                <ul class="mb-1 ps-3 fs-12">
                                    <li v-for="(c, i) in conflicts" :key="'c'+i">
                                        <strong>{{ c.name }}</strong> already has travel order <strong>{{ c.code }}</strong> on {{ c.start }}<span v-if="c.end && c.end !== c.start"> - {{ c.end }}</span>.
                                    </li>
                                </ul>
                                <b-button variant="link" type="button" size="sm" class="p-0 fs-11 text-decoration-none" @click="toggleTab(4, 75)">Update Employees</b-button>
                            </div>
                        </div>
                    </template>
                    <div v-else class="border border-dashed bg-light-subtle rounded p-3 review-summary">

                        <div class="d-flex align-items-start justify-content-between mt-n2">
                            <div class="overflow-hidden">
                                <span class="text-muted fs-10 fw-semibold">EVENT</span>
                                <h6 class="fs-12 fw-semibold mb-1 text-truncate">{{ selectedEvent?.name || '—' }}</h6>
                                <div class="d-flex flex-wrap align-items-center gap-2 fs-10 text-muted">
                                    <!-- <span v-if="selectedEvent?.type" class="badge bg-primary-subtle text-primary fs-10">{{ selectedEvent.type.name }}</span> -->
                                    <span v-if="selectedEvent"><i class="ri-map-pin-fill align-bottom me-1"></i>{{ selectedEvent.location?.name || 'No location set' }}</span>
                                    <span v-if="eventDates.length"><i class="ri-calendar-event-fill align-bottom me-1"></i>{{ eventDates[0].start }}<span v-if="eventDates[0].end && eventDates[0].end !== eventDates[0].start"> - {{ eventDates[0].end }}</span></span>
                                </div>
                                <div class="mt-1">
                                    <div class="fs-10 text-muted">Purpose</div>
                                    <div class="fs-11" :title="form.purpose">{{ form.purpose || '—' }}</div>
                                </div>
                            </div>
                            <b-button variant="link" type="button" size="sm" class="p-0 fs-10 text-decoration-none flex-shrink-0 ms-2" @click="toggleTab(1, 0)">Edit</b-button>
                        </div>

                        <hr class="my-2"/>

                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-10 fw-semibold">TRAVEL DETAILS</span>
                            <b-button variant="link" type="button" size="sm" class="p-0 fs-10 text-decoration-none" @click="toggleTab(2, 25)">Edit</b-button>
                        </div>
                        <BRow class="g-2">
                            <BCol md="3" cols="6">
                                <div class="fs-10 text-muted">Date</div>
                                <div class="fs-11">{{ form.date || '—' }}</div>
                            </BCol>
                            <BCol md="3" cols="6">
                                <div class="fs-10 text-muted">Time</div>
                                <div class="fs-11">{{ form.time || '—' }}</div>
                            </BCol>
                            <BCol md="3" cols="6">
                                <div class="fs-10 text-muted">Mode of Travel</div>
                                <div class="fs-11">{{ selectedMode?.name || '—' }}</div>
                            </BCol>
                            <BCol v-if="form.mode_id == 151" md="3" cols="6">
                                <div class="fs-10 text-muted">Transportation</div>
                                <div class="fs-11">{{ selectedTransport?.name || '—' }}</div>
                            </BCol>
                            <BCol v-if="form.mode_id == 150" md="3" cols="6">
                                <div class="fs-10 text-muted">Vehicle</div>
                                <div class="fs-11">{{ form.vehicle?.name || '—' }}</div>
                            </BCol>
                            <BCol v-if="form.mode_id == 150" md="3" cols="6">
                                <div class="fs-10 text-muted">Driver</div>
                                <div class="fs-11">{{ selectedDriver?.name || '—' }}</div>
                            </BCol>
                            <BCol md="6" cols="12">
                                <div class="fs-10 text-muted">Attachment</div>
                                <div class="fs-11 text-truncate">
                                    <i v-if="form.document" class="ri-file-pdf-2-line text-danger align-middle me-1"></i>{{ form.document?.name || 'None' }}
                                </div>
                            </BCol>
                            <BCol v-if="form.remarks" md="6" cols="12">
                                <div class="fs-10 text-muted">Remarks</div>
                                <div class="fs-11 text-truncate" :title="form.remarks">{{ form.remarks }}</div>
                            </BCol>
                        </BRow>

                        <hr class="my-2"/>

                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-10 fw-semibold">EXPENSES</span>
                            <b-button variant="link" type="button" size="sm" class="p-0 fs-10 text-decoration-none" @click="toggleTab(3, 50)">Edit</b-button>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-1">
                            <span class="badge bg-primary-subtle text-primary fs-10">{{ selectedExpenseType?.name || '—' }}</span>
                            <span v-for="(label, i) in selectedExpenseLabels" :key="i" class="badge bg-light border text-dark fw-normal fs-10">{{ label }}</span>
                            <span v-if="!selectedExpenseLabels.length" class="fs-10 text-muted">No expenses selected</span>
                        </div>

                        <hr class="my-2"/>

                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted fs-10 fw-semibold">EMPLOYEES ({{ form.tags.length }})</span>
                            <b-button variant="link" type="button" size="sm" class="p-0 fs-10 text-decoration-none" @click="toggleTab(4, 75)">Edit</b-button>
                        </div>
                        <div v-if="form.tags.length" class="fs-11">{{ employeeNames.join(', ') }}</div>
                        <span v-else class="fs-10 text-muted">No employees selected</span>
                    </div>
                </div>
            </div>
        </form>
        <template v-slot:footer>
            <template v-if="activeTab == 1">
                <b-button @click="hide" type="button" variant="light">Cancel</b-button>
                <b-button @click="toggleTab(2, 25)" type="button" variant="primary" :disabled="!step1Valid">Next</b-button>
            </template>
            <template v-else-if="activeTab == 2">
                <b-button @click="toggleTab(1, 0)" type="button" variant="link" class="text-decoration-none">Back</b-button>
                <b-button @click="toggleTab(3, 50)" type="button" variant="primary" class="ms-auto" :disabled="!step2Valid">Next</b-button>
            </template>
            <template v-else-if="activeTab == 3">
                <b-button @click="toggleTab(2, 25)" type="button" variant="link" class="text-decoration-none">Back</b-button>
                <b-button @click="toggleTab(4, 75)" type="button" variant="primary" class="ms-auto" :disabled="!step3Valid">Next</b-button>
            </template>
            <template v-else-if="activeTab == 4">
                <b-button @click="toggleTab(3, 50)" type="button" variant="link" class="text-decoration-none">Back</b-button>
                <b-button @click="toggleTab(5, 100)" type="button" variant="primary" class="ms-auto" :disabled="!step4Valid">Next</b-button>
            </template>
            <template v-else>
                <b-button @click="toggleTab(4, 75)" type="button" variant="link" class="text-decoration-none">Back</b-button>
                <b-button @click="submit()" type="button" variant="success" class="ms-auto" :disabled="form.processing || checkingConflicts">
                    <i class="ri-checkbox-circle-fill align-middle me-1"></i>Submit
                </b-button>
            </template>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import flatPickr from "vue-flatpickr-component";
import Multiselect from "@vueform/multiselect";
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
const FilePond = vueFilePond(FilePondPluginFileValidateType);
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';

const EXPENSE_LABELS = {
    1: 'Accommodation (Actual)',
    2: 'Accommodation (Per Diem)',
    3: 'Incidental Expenses',
    4: 'Meals'
};

export default {
    components: { Multiselect, InputLabel, TextInput, Textarea, flatPickr, FilePond },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                document: null,
                event_id: null,
                purpose: null,
                remarks: null,
                date: null,
                time: null,
                mode_id: null,
                expense_id: null,
                transpo_id: null,
                driver_id: null,
                vehicle: null,
                expenses: [],
                tags: [],
                option: 'travel'
            }),
            config: {
                enableTime: false,
                altInput: true,
                dateFormat: "Y-m-d",
                altFormat: "M d, Y",
                mode: "range"
            },
            employees: [],
            vehicles: [],
            drivers: [],
            isLoading: false,
            showModal: false,
            selectedEvent: null,
            eventResults: [],
            eventLoading: false,
            activeTab: 1,
            progressbarvalue: 0,
            conflicts: [],
            checkingConflicts: false,
            submitErrors: []
        }
    },
    computed: {
        eventDates() {
            return this.selectedEvent?.dates || [];
        },
        selectedExpenseType() {
            return this.dropdowns?.expenses?.find(e => e.value == this.form.expense_id);
        },
        selectedMode() {
            return this.dropdowns?.modes?.find(m => m.value == this.form.mode_id);
        },
        selectedTransport() {
            return this.dropdowns?.transportations?.find(t => t.value == this.form.transpo_id);
        },
        selectedDriver() {
            return this.drivers?.find(d => d.value == this.form.driver_id);
        },
        selectedExpenseLabels() {
            return (this.form.expenses || []).map(v => EXPENSE_LABELS[v]).filter(Boolean);
        },
        employeeNames() {
            return (this.form.tags || []).map(t => t.name);
        },
        step1Valid() {
            return !!this.selectedEvent && !!this.form.purpose;
        },
        step2Valid() {
            if (!this.form.date || !this.form.time || !this.form.mode_id) return false;
            if (this.form.mode_id == 151 && !this.form.transpo_id) return false;
            if (this.form.mode_id == 150 && (!this.form.vehicle || !this.form.driver_id)) return false;
            return true;
        },
        step3Valid() {
            return !!this.form.expense_id && !!(this.form.expenses && this.form.expenses.length);
        },
        step4Valid() {
            return !!(this.form.tags && this.form.tags.length);
        }
    },
    watch: {
        'form.expenses'(val) {
            if (val.includes(1) && val.includes(2)) {
                const last = val[val.length - 1];
                this.form.expenses = [last];
            }
        },
        'form.mode_id'(val) {
            this.handleTransportFetch();
        },
        'form.date'(val) {
            this.handleTransportFetch();
        },
        selectedEvent(val){
            this.form.event_id = val?.value ?? null;
        }
    },
    methods: {
        show(){
            this.showModal = true;
        },
        toggleTab(tab, value) {
            this.activeTab = tab;
            this.progressbarvalue = value;
            if (tab !== 5) {
                this.submitErrors = [];
                this.conflicts = [];
            }
        },
        validateForSubmit() {
            const errors = [];
            if (!this.selectedEvent) errors.push({ message: 'Please select an event.', tab: 1, progress: 0 });
            if (!this.form.purpose) errors.push({ message: 'Purpose is required.', tab: 1, progress: 0 });
            if (!this.form.date) errors.push({ message: 'Travel date is required.', tab: 2, progress: 25 });
            if (!this.form.time) errors.push({ message: 'Departure time is required.', tab: 2, progress: 25 });
            if (!this.form.mode_id) errors.push({ message: 'Mode of travel is required.', tab: 2, progress: 25 });
            if (this.form.mode_id == 151 && !this.form.transpo_id) errors.push({ message: 'Transportation is required.', tab: 2, progress: 25 });
            if (this.form.mode_id == 150 && !this.form.vehicle) errors.push({ message: 'Vehicle is required.', tab: 2, progress: 25 });
            if (this.form.mode_id == 150 && !this.form.driver_id) errors.push({ message: 'Driver is required.', tab: 2, progress: 25 });
            if (!this.form.expense_id) errors.push({ message: 'Travel expense type is required.', tab: 3, progress: 50 });
            if (!this.form.expenses || !this.form.expenses.length) errors.push({ message: 'Please select at least one applicable expense.', tab: 3, progress: 50 });
            if (!this.form.tags || !this.form.tags.length) errors.push({ message: 'Please select at least one employee.', tab: 4, progress: 75 });
            return errors;
        },
        async submit(){
            this.submitErrors = this.validateForSubmit();
            this.conflicts = [];

            if (this.form.tags.length && this.form.date) {
                this.checkingConflicts = true;
                try {
                    const { data } = await axios.post('/requests-travel-conflicts', {
                        employees: this.form.tags.map(t => t.value),
                        date: this.form.date
                    });
                    this.conflicts = data;
                } catch (err) {
                    console.log(err);
                } finally {
                    this.checkingConflicts = false;
                }
            }

            if (this.submitErrors.length || this.conflicts.length) {
                this.activeTab = 5;
                this.progressbarvalue = 100;
                return;
            }

            this.form.post('/requests',{
                preserveScroll: true,
                forceFormData: true,
                onSuccess: (response) => {
                    this.$emit('success',true);
                    this.resetAll();
                    this.hide();
                },
            });
        },
        resetEvent(){
            this.selectedEvent = null;
            this.eventResults = [];
        },
        resetAll(){
            this.form.clearErrors();
            this.form.reset();
            this.resetEvent();
            this.vehicles = [];
            this.drivers = [];
            this.conflicts = [];
            this.checkingConflicts = false;
            this.toggleTab(1, 0);
        },
        checkEventSearchStr: _.debounce(function(string) {
            this.searchEvents(string);
        }, 300),
        searchEvents(keyword){
            this.eventLoading = true;
            axios.get('/search', {
                params: {
                    option: 'events',
                    keyword: keyword
                }
            })
            .then(response => {
                this.eventResults = response.data;
            })
            .catch(err => console.log(err))
            .finally(() => { this.eventLoading = false; });
        },
        fetchVehicles(string){
            axios.get('/search',{
                params: {
                    option: 'vehicles',
                    keyword: string
                }
            })
            .then(response => {
                this.vehicles = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchDrivers(string){
            axios.get('/search',{
                params: {
                    option: 'drivers',
                    keyword: string
                }
            })
            .then(response => {
                this.drivers = response.data;
            })
            .catch(err => console.log(err));
        },
        checkSearchStr: _.debounce(function(string) {
            (string) ? this.searchUser(string) : '';
        }, 300),
        searchUser(string){
            axios.get('/search',{
                params: {
                    option: 'users',
                    keyword: string
                }
            })
            .then(response => {
                this.employees = response.data;
            })
            .catch(err => console.log(err));
        },
        handleAddFile(error, fileItem) {
            if (error) return console.error('FilePond error:', error);
            this.form.document = fileItem.file;
        },
        handleTransportFetch() {
            if (this.form.mode_id == 150 && this.form.date) {
                this.fetchVehicles(this.form.date);
                this.fetchDrivers(this.form.date);
            } else {
                this.vehicles = [];
                this.drivers = [];
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.showModal = false;
            this.resetAll();
        }
    }
}
</script>
<style scoped>
.step-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.step-title {
    margin-top: 6px;
    font-size: 11px;
    text-align: center;
    color: #6c757d;
}
.progress-back {
    margin-top: -11px;
}
.progress-bar-tab {
    position: relative;
    z-index: 2;
}
.travel-attachment :deep(.filepond--root) {
    height: 70px;
}
.travel-attachment :deep(.filepond--drop-label) {
    height: 100%;
}
.review-summary {
    max-height: 500px;
    overflow-y: auto;
}
</style>
