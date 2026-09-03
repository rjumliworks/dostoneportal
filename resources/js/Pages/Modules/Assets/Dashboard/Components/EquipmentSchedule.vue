<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-calendar-2-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Equipment Maintenance Schedule</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">YEAR: <span class="fw-semibold text-primary">{{ year }}</span></p>
                </div>
                <div class="flex-shrink-0">
                    <a :href="`/equipments-schedule/print?year=${year}`" target="_blank" class="btn btn-primary" title="Print / View as PDF">
                        <i class="ri-printer-fill align-bottom me-1"></i> Print
                    </a>
                </div>
            </div>
        </div>
        <div class="bg-white border-bottom">
            <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"><i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="search" placeholder="Search Equipment by Name" class="form-control" style="width: 50%;">
                        <select v-model="selectedType" class="form-select" style="width: 17%;">
                            <option :value="null">All Types</option>
                            <option v-for="type in types" v-bind:key="type" :value="type">{{ type }}</option>
                        </select>
                    </div>
                </b-col>
            </b-row>
        </div>
        <div class="bg-info-subtle border-bottom px-3 py-2" v-if="selectedIds.length">
            <div class="d-flex align-items-center gap-2">
                <span class="fs-12 fw-semibold">{{ selectedIds.length }} equipment selected</span>
                <b-button variant="primary" size="sm" @click="openScheduleModal">
                    <i class="ri-calendar-check-fill align-bottom me-1"></i> Set Schedule
                </b-button>
                <b-button variant="light" size="sm" @click="clearSelection">Cancel</b-button>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom">
            <div class="table-responsive table-card">
                <simplebar data-simplebar style="height: 500px;">
                    <table class="table table-bordered table-nowrap align-middle mb-0 schedule-table">
                        <thead class="table-light thead-fixed">
                            <tr class="fs-11 text-center">
                                <th style="width: 3%;">
                                    <input type="checkbox" class="form-check-input" :checked="allSelected" @change="toggleSelectAll">
                                </th>
                                <th style="width: 11%;">Code No.</th>
                                <th>Equipment Name</th>
                                <th v-for="m in months" v-bind:key="m" style="width: 4.5%;">{{ m }}</th>
                            </tr>
                        </thead>
                        <tbody class="fs-12">
                            <tr v-for="(list,index) in displayList" v-bind:key="index" :id="`schedule-row-${list.id}`" :class="{ 'table-warning': list.id === highlightedId }">
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input" v-model="selectedIds" :value="list.id">
                                </td>
                                <td class="text-center text-primary fw-semibold">{{ list.code }}</td>
                                <td @click="openRecordModal(list)" style="cursor: pointer;">
                                    <a href="javascript:void(0)" @click="openRecordModal(list)" class="text-primary fw-semibold" v-b-tooltip.hover title="Add Maintenance Record">{{ list.name }}</a>
                                    <span v-if="list.type" class="text-muted"> ({{ list.type }})</span>
                                </td>
                                <td v-for="n in 12" v-bind:key="n" class="text-center">
                                    <i v-if="list.completed && list.completed.includes(n)" class="ri-checkbox-circle-fill fs-20 text-success"></i>
                                    <i v-else-if="list.maintenance_schedule.includes(n)" class="ri-close-circle-fill fs-20" :class="isOverdue(n) ? 'text-danger' : 'text-warning'"></i>
                                </td>
                            </tr>
                            <tr v-if="!displayList.length">
                                <td :colspan="15" class="text-center text-muted py-4">No equipment found</td>
                            </tr>
                        </tbody>
                    </table>
                </simplebar>
            </div>
        </div>
         <div class="card-footer"></div>
    </div>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Set Maintenance Schedule" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <p class="fs-12 text-muted">Applying to <strong>{{ selectedIds.length }}</strong> selected equipment. This replaces their current schedule.</p>
        <InputLabel value="Scheduled Maintenance Months"/>
        <Multiselect mode="tags" :options="monthOptions" :searchable="false" :close-on-select="false" label="name" v-model="bulkMonths" placeholder="Select months"/>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button @click="applyBulkSchedule" variant="primary" :disabled="applying" block>Apply</b-button>
        </template>
    </b-modal>
    <Record :dropdowns="dropdowns" :maintainable="selectedEquipment" maintainable-type="equipment" ref="recordModal" @update="onRecordCreated"/>
</template>
<script>
import simplebar from "simplebar-vue";
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import Record from '@/Shared/Components/Maintenance/Modals/Record.vue';
import { router } from '@inertiajs/vue3';
export default {
    components: { simplebar, Multiselect, InputLabel, Record },
    props: ['lists','dropdowns'],
    data(){
        return {
            year: new Date().getFullYear(),
            months: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            monthOptions: [
                { value: 1, name: 'January' }, { value: 2, name: 'February' }, { value: 3, name: 'March' },
                { value: 4, name: 'April' }, { value: 5, name: 'May' }, { value: 6, name: 'June' },
                { value: 7, name: 'July' }, { value: 8, name: 'August' }, { value: 9, name: 'September' },
                { value: 10, name: 'October' }, { value: 11, name: 'November' }, { value: 12, name: 'December' },
            ],
            search: null,
            selectedType: null,
            highlightedId: null,
            selectedIds: [],
            bulkMonths: [],
            applying: false,
            showModal: false,
            selectedEquipment: null,
        }
    },
    computed: {
        types(){
            return [...new Set(this.lists.map(list => list.type).filter(Boolean))].sort();
        },
        displayList(){
            const items = [...this.lists].sort((a,b) => a.code.localeCompare(b.code));
            if(!this.selectedType){
                return items;
            }
            return items.filter(list => list.type === this.selectedType);
        },
        allSelected(){
            return this.displayList.length > 0 && this.displayList.every(list => this.selectedIds.includes(list.id));
        }
    },
    methods: {
        isOverdue(month){
            return month < (new Date().getMonth() + 1);
        },
        openRecordModal(list){
            this.selectedEquipment = list;
            this.$refs.recordModal.show();
        },
        onRecordCreated(payload){
            const record = payload.record;
            if(!record || !this.selectedEquipment){ return; }
            const target = this.lists.find(list => list.id === this.selectedEquipment.id);
            if(!target){ return; }
            const recordDate = new Date(record.date);
            if(recordDate.getFullYear() === this.year && record.status?.name === 'Completed'){
                const month = recordDate.getMonth() + 1;
                if(!target.completed){ target.completed = []; }
                if(!target.completed.includes(month)){ target.completed.push(month); }
            }
        },
        toggleSelectAll(){
            const visibleIds = this.displayList.map(list => list.id);
            if(this.allSelected){
                this.selectedIds = this.selectedIds.filter(id => !visibleIds.includes(id));
            }else{
                this.selectedIds = [...new Set([...this.selectedIds, ...visibleIds])];
            }
        },
        clearSelection(){
            this.selectedIds = [];
            this.bulkMonths = [];
            this.showModal = false;
        },
        openScheduleModal(){
            this.bulkMonths = [];
            this.showModal = true;
        },
        applyBulkSchedule(){
            if(!this.selectedIds.length){ return; }
            this.applying = true;
            router.put('/equipments-schedule/bulk',{
                equipment_ids: this.selectedIds,
                months: this.bulkMonths,
            },{
                preserveScroll: true,
                preserveState: true,
                onSuccess: (page) => {
                    const updated = page.props.flash.data || [];
                    updated.forEach(item => {
                        const target = this.lists.find(list => list.id === item.id);
                        if(target){ target.maintenance_schedule = item.maintenance_schedule; }
                    });
                    this.showModal = false;
                    this.clearSelection();
                },
                onFinish: () => { this.applying = false; },
            });
        }
    },
    watch: {
        search(keyword){
            if(!keyword){
                this.highlightedId = null;
                return;
            }
            const match = this.displayList.find(list => list.name.toLowerCase().includes(keyword.toLowerCase()));
            this.highlightedId = match ? match.id : null;
            if(match){
                this.$nextTick(() => {
                    document.getElementById(`schedule-row-${match.id}`)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
        }
    }
}
</script>
