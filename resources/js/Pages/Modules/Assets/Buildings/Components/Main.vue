<template>
    <div class="card bg-light-subtle shadow-none border">
        <div class="card-header bg-light-subtle">
            <div class="d-flex mb-n3">
                <div class="flex-shrink-0 me-3">
                    <div style="height:2.5rem;width:2.5rem;">
                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                            <i class="ri-tools-fill text-primary fs-24"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="mb-0 fs-14"><span class="text-body">Building Management Tabs</span></h5>
                    <p class="text-muted text-truncate-two-lines fs-12">More Information for the building</p>
                </div>
            </div>
        </div>

        <div class="card bg-white rounded-bottom shadow-none mb-0">
            <div class="step-arrow-nav mt-0">
                <ul class="nav nav-pills nav-justified custom-nav" role="tablist">
                    <li class="nav-item" role="presentation" v-for="(menu, index) in menus" v-bind:key="index">
                        <button class="nav-link fs-12 p-3" :class="(index == 0) ? 'active' : ''"
                            :id="slug(menu)+'-tab'" data-bs-toggle="pill" :data-bs-target="'#'+slug(menu)"
                            type="button" role="tab" :aria-controls="slug(menu)" aria-selected="true">
                            {{menu}}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body bg-white rounded-bottom">
            <div class="tab-content">
                <div class="tab-pane" :class="(index == 0) ? 'show active' : ''" :id="slug(menu)" role="tabpanel" :aria-labelledby="slug(menu)+'-tab'" v-for="(menu, index) in menus" v-bind:key="index">

                    <div class="carousel-container">
                        <div class="carousel-content">
                            <transition mode="out-in">
                                <div :key="index" class="tab-content">
                                    <RecordsTable :dropdowns="dropdowns" :maintainable="building" maintainable-type="building" @create="openRecord()" @edit="openRecord" v-if="menu == 'Maintenance Record'" />
                                    <RequestsTable :dropdowns="dropdowns" :maintainable="building" @create="openRequest()" @edit="openRequest" @fulfill="fulfillRequest" v-if="menu == 'Request for Maintenance'" />
                                </div>
                            </transition>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <Record :dropdowns="dropdowns" :maintainable="building" maintainable-type="building" ref="record" @update="onRecordUpdate"/>
    <Request :dropdowns="dropdowns" :maintainable="building" maintainable-type="building" ref="request" @update="onRequestUpdate"/>
</template>
<script>
import RecordsTable from '@/Shared/Components/Maintenance/RecordsTable.vue';
import RequestsTable from '@/Shared/Components/Maintenance/RequestsTable.vue';
import Record from '@/Shared/Components/Maintenance/Modals/Record.vue';
import Request from '@/Shared/Components/Maintenance/Modals/Request.vue';
export default {
    components: { RecordsTable, RequestsTable, Record, Request },
    props: ['building','dropdowns'],
    data(){
        return {
            menus: [
                'Maintenance Record','Request for Maintenance'
            ],
            index: null,
        }
    },
    methods: {
        slug(menu){
            return menu.toLowerCase().replace(/[^a-z0-9]+/g,'-');
        },
        openRecord(record = null){
            this.$refs.record.show(record);
        },
        openRequest(request = null){
            this.$refs.request.show(request);
        },
        fulfillRequest(request){
            this.$refs.record.show(null, request);
        },
        onRecordUpdate(payload){
            const index = this.building.records.findIndex(r => r.id === payload.record.id);
            if(index > -1){
                this.building.records.splice(index,1,payload.record);
            }else{
                this.building.records.unshift(payload.record);
            }

            if(payload.request){
                const requestIndex = this.building.maintenance_requests.findIndex(r => r.id === payload.request.id);
                if(requestIndex > -1){
                    this.building.maintenance_requests.splice(requestIndex,1,payload.request);
                }
            }
        },
        onRequestUpdate(request){
            const index = this.building.maintenance_requests.findIndex(r => r.id === request.id);
            if(index > -1){
                this.building.maintenance_requests.splice(index,1,request);
            }else{
                this.building.maintenance_requests.unshift(request);
            }
        }
    }
}
</script>
