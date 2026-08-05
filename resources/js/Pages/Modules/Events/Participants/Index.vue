<template>
    <Head title="Participants"/>
    <PageHeader title="Participant Management" pageTitle="List" />
    <BRow>
        <div class="col-md-12">
            <div class="card bg-light-subtle shadow-none border">

                <div class="card-header bg-light-subtle">
                    <div class="d-flex mb-n3">
                        <div class="flex-shrink-0 me-3">
                            <div style="height:2.5rem;width:2.5rem;">
                                <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                    <i class="ri-group-fill text-primary fs-24"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fs-14"><span class="text-body">List of Participants</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">Everyone who has registered across all events, with their affiliation and registration details</p>
                        </div>
                        <div class="flex-shrink-0" style="width: 45%;">

                        </div>
                    </div>
                </div>

                <div class="car-body bg-white border-bottom shadow-none">
                    <b-row class="mb-2 ms-1 me-1" style="margin-top: 12px;">
                        <b-col lg>
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                                <input type="text" v-model="filter.keyword" placeholder="Search by name or code" class="form-control" style="width: 20%;">
                                <Multiselect class="white" style="width: 20%;" :options="registrationOptions" v-model="filter.registration" label="name" :searchable="false" placeholder="Select Registration Type" />
                                <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;">
                                    <i class="bx bx-refresh search-icon"></i>
                                </span>
                            </div>
                        </b-col>
                    </b-row>
                </div>

                <div class="card bg-white border-bottom shadow-none" no-body>
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <ul class="nav nav-tabs nav-tabs-custom nav-primary fs-12" role="tablist">
                                <li class="nav-item">
                                    <BLink @click="viewType(null,null)" class="nav-link py-3 active" data-bs-toggle="tab" role="tab" aria-selected="true">
                                    <i class="ri-apps-2-line me-1 align-bottom"></i> All Participants
                                    </BLink>
                                </li>
                                <li class="nav-item" v-for="(list,index) in counts" v-bind:key="index">
                                    <BLink @click="viewType(index,list.value)" class="nav-link py-3" :class="(this.index == index) ? 'text-primary active' : ''" data-bs-toggle="tab" role="tab" aria-selected="false">
                                        <i class="ri-user-3-line me-1 align-bottom"></i>
                                        {{ list.name }}
                                        <BBadge v-if="list.count > 0" :class="(this.index == index) ? 'bg-primary text-white' : 'text-dark bg-primary-subtle'" class="align-middle ms-1">{{list.count}}</BBadge>
                                    </BLink>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card" style="margin-top: -39px; height: calc(100vh - 465px); overflow: auto;">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th style="width: 3%;"></th>
                                    <th>Name</th>
                                    <th style="width: 12%;" class="text-center">Contact No.</th>
                                    <th style="width: 15%;" class="text-center">Email</th>
                                    <th style="width: 8%;" class="text-center">Sessions</th>
                                    <th style="width: 9%;" class="text-center">Status</th>
                                    <th style="width: 15%;" class="text-center">Registered</th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-for="(list,index) in filteredLists" v-bind:key="index" @click="selectRow(index)"
                                 :class="{ 'bg-info-subtle': selectedRow === index }">
                                    <td class="text-center">
                                        <div class="avatar-xs">
                                            <img :src="list.avatar" class="rounded-circle avatar-xs" style="object-fit:cover;" alt="Avatar">
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="fs-13 mb-0 fw-semibold text-primary text-uppercase">{{list.name}}</h5>
                                        <p class="fs-12 text-muted mb-0" :class="{ 'text-capitalize': list.affiliation?.name === 'Others' }">{{ list.affiliation?.name === 'Others' ? list.others : list.affiliation?.name }}</p>
                                    </td>
                                    <td class="text-center">{{ list.mobile }}</td>
                                    <td class="text-center">{{ list.email }}</td>
                                    <td class="text-center">{{ list.sessions_count || '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge" :class="list.is_completed ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'">{{ list.is_completed ? 'Completed' : 'Pending' }}</span>
                                    </td>
                                    <td class="text-center">{{ list.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-muted ms-2 me-2 mt-n1">Showing <span class="fw-semibold">{{ filteredLists.length }}</span> of <span class="fw-semibold">{{ lists.length }}</span> Results</div>
                </div>

            </div>
        </div>
    </BRow>
</template>
<script>
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Multiselect },
    props: ['counts','dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            filter: {
                keyword: null,
                type: null,
                registration: null
            },
            registrationOptions: [
                { value: 'regular', name: 'Regular Registration (no sessions)' },
                { value: 'session', name: 'Session Registration (with sessions)' }
            ],
            index: null,
            selectedRow: null
        }
    },
    computed: {
        filteredLists(){
            let data = this.lists;

            if(this.filter.type){
                data = data.filter(list => list.type?.id === this.filter.type);
            }

            if(this.filter.registration === 'regular'){
                data = data.filter(list => !list.sessions_count);
            } else if(this.filter.registration === 'session'){
                data = data.filter(list => list.sessions_count > 0);
            }

            if(this.filter.keyword){
                const keyword = this.filter.keyword.toLowerCase();
                data = data.filter(list =>
                    list.name?.toLowerCase().includes(keyword) ||
                    list.code?.toLowerCase().includes(keyword) ||
                    list.affiliation?.name?.toLowerCase().includes(keyword) ||
                    list.others?.toLowerCase().includes(keyword)
                );
            }

            return data;
        }
    },
    created(){
       this.fetch();
    },
    methods: {
        fetch(){
            axios.get('/participants',{
                params : {
                    option: 'list'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                }
            })
            .catch(err => console.log(err));
        },
        viewType(index,type){
            this.index = index;
            this.filter.type = type;
        },
        refresh(){
            this.filter.keyword = null;
            this.filter.type = null;
            this.filter.registration = null;
            this.index = null;
            this.fetch();
        },
        selectRow(index) {
            this.selectedRow = index;
        }
    }
}
</script>
