<template>
<div class="card bg-light-subtle shadow-none border">
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-alarm-fill text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Activity Logs</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">Activity logs are records that document the actions and events occurring within a system or application over a period of time.</p>
            </div>
        </div>
    </div>
    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 346px); overflow: auto;">
        <div class="table-responsive table-card">
            <table class="table align-middle table-striped table-centered mb-0">
                <thead class="table-primary thead-fixed">
                    <tr class="fs-11">
                        <th style="width: 4%;"></th>
                        <th>Logs</th>
                        <th style="width: 17%;" class="text-center">User</th>
                        <th style="width: 25%;" class="text-center">Date</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody class="table-white fs-12">
                    <tr v-for="(list,index) in lists" v-bind:key="index" class="fs-12">
                        <td>
                            {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.
                        </td>
                        <td>
                        <span class="text-capitalize">{{ list.event }}</span> {{list.log_name}}
                        </td>
                        <td class="text-center">{{list.causer.profile.fullname}} </td>
                        <td class="text-center">{{formatDate(list.created_at)}}</td>
                        <td>
                            <b-button @click="openLog(list)" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                <i class="ri-eye-fill align-bottom"></i>
                            </b-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <Pagination class="ms-2 me-2 mt-n1" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Log ref="log"/>
</div>
</template>
<script>
import Log from './Modals/Log.vue';
import simplebar from "simplebar-vue";
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, simplebar, Log },
    data(){
        return {
            lists: [],
            meta: {},
            links: {},
            accordions: {
                1: false,
                2: false
            }
        }
    },
    created(){
        this.fetch();
    },
    methods : {
        fetch(page_url){
            page_url = page_url || '/profile';
            return axios.get(page_url,{
                params : {
                    option: 'activity-logs',
                    count: ((window.innerHeight-490)/50),
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;      
            });
        },
        openLog(data){
            this.$refs.log.show(data);
        },
        formatDate(dateString) {
            const date = new Date(dateString)

            return date.toLocaleString('en-PH', {
                year: 'numeric',
                month: 'long',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true,
            })
        }
    }
}
</script>