<template>
    <Head title="Profile" />
    <PageHeader title="Signatory Profile" pageTitle="User" />
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card bg-light-subtle shadow-none border">
                
                <div class="card-body bg-white rounded-bottom rounded-top p-4" style="height: calc(100vh - 223px); overflow: auto;">
                   <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto mb-3">
                            <img v-if="selected.data.user" :src="selected.data.user.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                            <img v-else-if="selected.data.is_oic" :src="selected.data.oic.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                            <img v-else :src="selected.data.avatar" class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input" @change="previewImage"/>
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h5 v-if="selected.data.user" class="text-primary fw-semibold fs-16 mb-1">{{ selected.data.user.name }}</h5>
                        <h5 v-else-if="selected.data.is_oic" class="text-warning fw-semibold fs-16 mb-1">{{ selected.data.oic.name }}</h5>
                        <h5 v-else class="fs-16 mb-1">Not Assigned</h5>
                        <p v-if="!selected.data.is_oic" class="mb-n1">{{ selected.data.designation }}</p>
                        <p v-else class="mb-n1">OIC - {{ selected.data.designation }}</p>
                        <p class="text-muted fs-12 mb-0">({{ selected.data.assigned.name }})</p>
                    </div>
                    <hr class="text-muted">
                    <div class="d-grid gap-1" >
                        <button @click="openAssignment" class="btn btn-primary" type="button">Set Designation</button>
                        <button @click="openSignatory" class="btn btn-primary" type="button">Assign Signatory</button>
                    </div>
                    <hr class="text-muted">                
                
                    <div v-for="(list,index) in selected.data.schedules" v-bind:key="index" class="bg-light-subtle mb-3">
                        <div class="d-flex border border-dashed rounded p-3">
                            <div class="flex-shrink-0 avatar-sm align-self-center me-3">
                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                    <img :src="list.user.avatar" alt="" class="img-thumbnail rounded-circle shadow-none">
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 mt-0 text-muted fs-11">Officer-in-Charge :</p>
                                <h6 class="fw-semibold  text-uppercase text-info fs-13 mb-0">{{ list.user.name }}</h6>
                                <p class="fs-11 text-muted mb-0">{{  list.start_at }} - {{ list.end_at }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
      
        <div class="col-md-9">
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
                            <h5 class="mb-0 fs-14"><span class="text-body">Schedules</span></h5>
                            <p class="text-muted text-truncate-two-lines fs-12">This is your personal hub where you can manage your account details, preferences, and interactions within our platform.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 345px); overflow: auto;">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th style="width: 3%;"></th>
                                    <th>Name</th>
                                    <th style="width: 12%;" class="text-center">Role</th>
                                    <th style="width: 20%;" class="text-center">Date</th>
                                    <th style="width: 10%;" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-white fs-12">
                                <tr v-for="(list,index) in lists" v-bind:key="index" :class="{
                                    'bg-success-subtle': list.is_ongoing == 1 && list.is_completed == 0,
                                }">
                                    <td class="text-center">
                                        <div class="avatar-xs">
                                            <img :src="list.user?.avatar" class="rounded-circle avatar-xs" style="object-fit:cover;" alt="Avatar">
                                        </div>
                                    </td>
                                    <td>
                                        <h5 class="fs-13 mb-0 fw-semibold text-primary text-uppercase">{{ list.user?.name }}</h5>
                                    </td>
                                    <td class="text-center">
                                        <span :class="list.is_designated ? 'badge bg-primary-subtle text-primary' : 'badge bg-info-subtle text-info'">
                                            {{ list.is_designated ? 'Appointee' : 'OIC' }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ list.start_at }} - {{ list.end_at ?? 'Present' }}</td>
                                    <td class="text-center">
                                        <span v-if="list.is_completed" class="badge bg-secondary-subtle text-secondary">Completed</span>
                                        <span v-else-if="list.is_ongoing" class="badge bg-success-subtle text-success">Ongoing</span>
                                        <span v-else class="badge bg-warning-subtle text-warning">Upcoming</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                 <div class="card-footer">
                    <Pagination class="ms-2 me-2 mt-n1" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
                </div>
            </div>
        </div>
    </div>
    <Signatory @success="reload" ref="signatory"/>
    <Assignment @success="reload" ref="assignment"/>
</template>
<script>
import { useForm, router } from "@inertiajs/vue3"
import Signatory from './Modals/Signatory.vue';
import Assignment from './Modals/Assignment.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Signatory, Assignment },
    props: ['selected'],
    data() {
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            activeTab: 1,
            form: useForm({
                image: null,
            }),
        };
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(page_url){
            page_url = page_url || '/signatories';
            axios.get(page_url,{
                params : {
                    count: 10,
                    option: 'list',
                    signatory_id: this.selected.data.signatory_id
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
        openSignatory(){
            this.$refs.signatory.show(this.selected.data);
        },
        openAssignment(){
            this.$refs.assignment.show(this.selected.data);
        },
        reload(){
            router.reload({ only: ['selected'] });
            this.fetch();
        }
    }
}
</script>