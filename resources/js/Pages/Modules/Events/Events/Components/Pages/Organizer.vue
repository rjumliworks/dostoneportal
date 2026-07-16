<template>
    <div class="card-body bg-white border-bottom shadow-none">
        <b-row class="mt-n3 mb-2 ms-n4 me-n4">
            <b-col lg>
                <div class="input-group mb-1">
                    <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                    <input type="text"  placeholder="Search Name" class="form-control" style="width: 30%;">
                    <!-- <b-button type="button" variant="primary" @click="openCreate">
                        <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                    </b-button> -->
                </div>
            </b-col>
        </b-row>
    </div>
    <div class="table-responsive table-card" style="height: calc(100vh - 330px);">
        <table class="table table-nowrap align-middle mb-0">
           
            <thead class="bg-primary text-white">
                <tr class="fs-10">
                    <th style="width: 4%;"></th>
                    <th>Name</th>
                    <th style="width: 20%;" class="text-center">Email</th>
                    <th style="width: 30%;" class="text-center">Contact no.</th>
                    <th style="width: 7%;" class="text-center"></th>
                </tr>
            </thead>
            <tbody v-if="managers.length > 0">
                <tr v-for="(list,index) in managers" v-bind:key="index" class="fs-12">
                    <td>{{ index + 1 }}</td>
                    <td>{{list.user.profile.firstname}} {{list.user.profile.lastname}}</td>
                    <td class="text-center">{{list.user.email}}</td>
                    <td class="text-center">{{list.user.profile.contact_no}}</td> 
                    <td class="text-end">
                        <b-button variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button>
                        <b-button @click="openEdit(list)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                            <i class="ri-pencil-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center text-muted">No records found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import _ from 'lodash';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination },
    props: ['sessions'],
    computed: {
        managers() {
            const all = this.sessions.flatMap(session => session.managers);
            const seen = new Set();
            return all.filter(manager => {
                if (seen.has(manager.user.id)) return false;
                seen.add(manager.id);
                return true;
            });
        }
    },
    methods: {
        openCreate(){
            this.$refs.create.show();
        },
        openEdit(list){
            this.$refs.create.edit(list);
        }
    }
}
</script>