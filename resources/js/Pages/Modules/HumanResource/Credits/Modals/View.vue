<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 850px;" header-class="p-3 bg-light" title="View Leave Credits" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" v-if="selected">
            <BRow>
               <div class="col-md-12">
                    <div>
                        <h4 class="fw-semibold fs-16 mb-1 text-uppercase">{{ selected.profile.name }}</h4>
                        <div class="hstack gap-3 flex-wrap fs-12">
                            <div><span class="text-muted">Date :</span>s</div>
                            <div class="vr" style="width: 1px;"></div>
                            <div><span class="text-muted">Date Created :</span> {{selected.created_at}}</div>
                        </div>
                    </div>
               </div>
                <div class="col-md-12 mb-n3">
                    <hr class="text-muted"/>
                </div>
                <div class="col-md-12 mt-3 mb-n3">
                    <div class="card bg-light-subtle shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height:2rem;width:2rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="ri-alarm-fill text-primary fs-20"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 mt-n1 fs-12"><span class="text-body">Leave Types</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-11">Tracks attendance times and logs any changes for accurate records.</p>
                                </div>
                            
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <div class="table-responsive table-card">
                                <table class="table  align-middle mb-1">
                                    <thead class="bg-primary fs-11 thead-fixed">
                                        <tr class="text-white">
                                            <th class="text-center" style="width: 6%;">#</th>
                                            <th>Leave</th>
                                            <th class="text-center" style="width: 15%;">Earned</th>
                                            <th class="text-center" style="width: 15%;">Used</th>
                                            <th class="text-center" style="width: 15%;">Carried Over</th>
                                            <th class="text-center" style="width: 15%;">Balance</th>
                                            <th  style="width: 10%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-12">
                                        <tr v-for="(list,index) in selected.credits" v-bind:key="index">
                                            <td class="text-center">{{ index+1 }}</td>
                                            <td>{{list.leave.name}}</td>
                                            <td class="text-center text-muted">{{list.earned}}</td>
                                            <td class="text-center text-muted">{{list.used}}</td>
                                            <td class="text-center text-muted">{{list.carried_over}}</td>
                                            <td class="text-center fw-semibold">{{list.balance}}</td>
                                            <td class="text-center">
                                                <b-button v-if="selected.am_in_at" @click="openEdit(selected.id,selected.am_in_at,'Time In (am)')" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                                    <i class="ri-pencil-fill align-bottom"></i>
                                                </b-button>
                                                <b-button v-else @click="openTime(selected.id,'Time In (am)')" variant="soft-info" v-b-tooltip.hover title="Set" size="sm">
                                                    <i class="ri-add-circle-fill align-bottom"></i>
                                                </b-button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </BRow>
        </form>
          <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            type: null,
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>