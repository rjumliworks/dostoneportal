<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 850px;" header-class="p-3 bg-light" :title="selected ? selected.leave.name + ' History' : 'Leave Credit History'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-if="selected">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Earned :</p>
                            <h6 class="fw-semibold fs-12 mb-0"> {{ selected.earned }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Used :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ selected.used }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Carried Over :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ selected.carried_over }} </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-0 text-muted fs-12">Balance :</p>
                            <h6 class="text-truncate fw-semibold fs-12 mb-0"> {{ selected.balance }} </h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive table-card">
                <table class="table align-middle table-striped table-centered mb-0">
                    <thead class="table-light thead-fixed">
                        <tr class="fs-11">
                            <th class="text-center" style="width: 6%;">#</th>
                            <th class="text-center" style="width: 15%;">Credits</th>
                            <th class="text-center" style="width: 17%;">Old Balance</th>
                            <th class="text-center" style="width: 17%;">New Balance</th>
                            <th style="width: 30%;">Date</th>
                            <th class="text-center" style="width: 15%;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="fs-12" v-if="lists.length > 0">
                        <tr v-for="(list,index) in lists" v-bind:key="index">
                            <td class="text-center">{{ index+1 }}</td>
                            <td class="text-center">{{ list.amount }}</td>
                            <td class="text-center">{{ list.old_balance }}</td>
                            <td class="text-center">{{ list.new_balance }}</td>
                            <td>{{ list.created_at }}</td>
                            <td class="text-center">
                                <span v-if="list.type && list.type.name == 'Earn'" class="badge bg-success">Earned</span>
                                <span v-else class="badge bg-danger">Deducted</span>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No history found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Pagination class="mt-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
        </template>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination },
    data(){
        return {
            selected: null,
            lists: [],
            meta: {},
            links: {},
            showModal: false
        }
    },
    methods: {
        show(credit){
            this.selected = credit;
            this.showModal = true;
            this.fetch();
        },
        fetch(page_url){
            page_url = page_url || '/credits';
            axios.get(page_url,{
                params: {
                    id: this.selected.id,
                    count: 10,
                    option: 'logs'
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        hide(){
            this.showModal = false;
            this.selected = null;
            this.lists = [];
        }
    }
}
</script>
