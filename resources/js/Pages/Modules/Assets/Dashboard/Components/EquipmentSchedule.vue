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
        <div class="card-body bg-white rounded-bottom">
            <div class="table-responsive table-card">
                <simplebar data-simplebar style="height: 420px;">
                    <table class="table table-bordered table-nowrap align-middle mb-0 schedule-table">
                        <thead class="table-light thead-fixed">
                            <tr class="fs-11 text-center">
                                <th style="width: 11%;">Code No.</th>
                                <th>Equipment Name</th>
                                <th v-for="m in months" v-bind:key="m" style="width: 4.5%;">{{ m }}</th>
                            </tr>
                        </thead>
                        <tbody class="fs-12">
                            <tr v-for="(list,index) in displayList" v-bind:key="index" :id="`schedule-row-${list.id}`" :class="{ 'table-warning': list.id === highlightedId }">
                                <td class="text-center text-primary fw-semibold">{{ list.code }}</td>
                                <td>{{ list.name }} <span v-if="list.type" class="text-muted">({{ list.type }})</span></td>
                                <td v-for="n in 12" v-bind:key="n" class="text-center" :class="{ scheduled: list.maintenance_schedule.includes(n) }"></td>
                            </tr>
                            <tr v-if="!displayList.length">
                                <td :colspan="14" class="text-center text-muted py-4">No equipment found</td>
                            </tr>
                        </tbody>
                    </table>
                </simplebar>
            </div>
        </div>
    </div>
</template>
<script>
import simplebar from "simplebar-vue";
export default {
    components: { simplebar },
    props: ['lists'],
    data(){
        return {
            year: new Date().getFullYear(),
            months: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            search: null,
            selectedType: null,
            highlightedId: null,
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
<style scoped>
.schedule-table td.scheduled {
    background-image: repeating-linear-gradient(45deg, transparent, transparent 4px, #4a7dbd 4px, #4a7dbd 5px);
}
</style>
