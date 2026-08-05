<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" :title="title" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <p class="text-muted fs-12">DTRs not yet completed, excluded from the tardiness/undertime totals.</p>
        <div class="table-responsive" style="max-height: 420px; overflow: auto;">
            <table class="table table-bordered table-centered align-middle mb-1">
                <thead class="bg-primary fs-12 thead-fixed">
                    <tr class="text-white">
                        <th class="text-center" style="width: 8%;">#</th>
                        <th class="text-center">Date</th>
                    </tr>
                </thead>
                <tbody class="fs-13">
                    <tr v-for="(list,index) in lists" v-bind:key="index">
                        <td class="text-center">{{ index + 1 }}</td>
                        <td class="text-center">{{ formatDate(list.date) }}</td>
                    </tr>
                    <tr v-if="!lists.length">
                        <td colspan="2" class="text-center text-muted">No incomplete DTRs.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            showModal: false,
            lists: [],
            name: null,
        }
    },
    computed: {
        title(){
            return this.name ? `Incomplete DTRs - ${this.name}` : 'Incomplete DTRs';
        }
    },
    methods: {
        show(lists, name){
            this.lists = lists || [];
            this.name = name;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        },
        formatDate(date){
            if(!date) return '-';
            return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' });
        }
    }
}
</script>
