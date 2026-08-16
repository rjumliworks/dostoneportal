<template>
    <Head title="Whereabouts" />
    <PageHeader title="Whereabouts" pageTitle="List" />

    <BRow>
        <div class="col-md-12">
            <b-card no-body class="bg-white-subtle border shadow-none">
                <b-card-body>
                     <b-row>
                        <b-col lg>
                            <div class="input-group mb-0">
                                <input type="text" v-model="filter.keyword" placeholder="Search Employee" class="form-control" style="width: 10%;">
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.divisions" v-model="filter.division" label="others" :searchable="true" placeholder="Select Division" />
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.stations" v-model="filter.station" label="others" :searchable="true" placeholder="Select Stations" />
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.statuses" v-model="filter.status" label="name" :searchable="true" placeholder="Select Status" />
                                <Multiselect class="white" style="width: 13%;" :options="dropdowns.employment_statuses" v-model="filter.type" label="name" :searchable="true" placeholder="Select Type" />
                               <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                            </div>
                        </b-col>
                    </b-row>
                </b-card-body>
                <div class="card border-top shadow-none p-2 mb-0" no-body>
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="d-flex flex-wrap gap-1 ms-2">
                                <button
                                    v-for="letter in alphabet"
                                    :key="letter"
                                    class="btn btn-sm"
                                    :class="selectedLetter === letter ? 'btn-primary' : 'btn-light'"
                                    @click="selectedLetter = letter"
                                >
                                    {{ letter }}
                                </button>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="btn-group mt-4 mt-md-0 me-2" role="group" aria-label="Basic example">
                                <button @click="sort = 'lists'" type="button" class="btn btn-light material-shadow-none" v-b-tooltip.hover title="Personnels"><i class="ri-list-check"></i></button>
                                <button @click="sort = 'divisions'" type="button" class="btn btn-light material-shadow-none" v-b-tooltip.hover title="Division"><i class="ri-list-check-2"></i></button>
                                <button @click="sort = 'units'" type="button" class="btn btn-light material-shadow-none" v-b-tooltip.hover title="Units"><i class="ri-group-2-line"></i></button>
                                <button @click="sort = 'stations'" type="button" class="btn btn-light material-shadow-none" v-b-tooltip.hover title="Station"><i class="ri-map-pin-line"></i></button>
                                <button @click="sort = 'shifts'" type="button" class="btn btn-light material-shadow-none" v-b-tooltip.hover title="Work Schedule"><i class="ri-calendar-line"></i></button>
                                <button @click="toggleSortPosition" type="button" class="btn btn-light material-shadow-none" v-b-tooltip.hover title="Position"><i class="ri-sort-asc"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-top shadow-none p-2 mb-0" no-body>

                </div>
            </b-card>
        </div>
    </BRow>


    <div class="row g-3" style="height: calc(100vh - 370px); overflow: auto;">

        <div class="col-md-3 mb-n4" v-for="(list,index) in filteredEmployees" :key="index" v-if="sort == 'lists'">
            <div class="card ribbon-box">  
                <!-- border shadow-none -->
                <div class="card-body text-center">
                    <div class="ribbon ribbon-primary round-shape fs-10 mt-2" :class="list.organization.status.bg">{{ list.organization.status.name }}</div>
                    <div class="mb-2 mt-0">
                        <img
                            :src="list.avatarSrc"
                            loading="lazy"
                            class="rounded-circle img-thumbnail"
                            style="width:60px;height:60px;object-fit:cover;"
                            alt="Avatar"
                        >
                    </div>
                    <h5 class="mb-0 text-uppercase fw-semibold text-primary fs-12">{{ list.name }}</h5>
                    <div class="text-muted small mb-0">{{ list.organization.position.name }}</div>
                    <span class="badge text-bg-primary" style="font-size: 9px;">{{ list.organization.type.name }}</span>
                </div>

                <div class="card-body border-top border-top-dashed fs-11">
                    <div class="d-flex justify-content-between mb-0 mt-n2">
                        <span class="text-muted">Unit</span>
                        <span class="text-end text-truncate ms-3">
                            {{ list.organization.unit.name }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-0">
                        <span class="text-muted">Division</span>
                        <span class="text-end text-truncate ms-3">
                            {{ list.organization.division.name }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Station</span>
                        <span class="text-end text-truncate ms-3">
                            {{ list.organization.station.name }}
                        </span>
                    </div>
                     <div class="d-flex justify-content-between mb-n1">
                        <span class="text-muted">Work Schedule</span>
                        <span class="text-end text-truncate ms-3">
                            {{ list.organization.shift?.name ?? 'No Work Schedule' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xxl-3 col-sm-6 project-card" v-for="(list,index) in dropdowns.divisions" :key="index" v-if="sort == 'divisions'">
            <div class="card">
                <div class="card-body">
                    <div class="p-3 mt-n3 mx-n3 bg-info-subtle rounded-top">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-12 fw-semibold text-primary">{{ list.name }}</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex gap-1 align-items-center my-n2">
                                    <button type="button" class="btn avatar-xs p-0 favourite-btn material-shadow-none active">
                                        <span class="avatar-title bg-transparent fs-15">
                                            <i class="ri-star-fill"></i>
                                        </span>
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-1 mt-n1 py-0 text-decoration-none fs-15 material-shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal icon-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="apps-projects-overview.html"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                            <a class="dropdown-item" href="apps-projects-create.html"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#removeProjectModal"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-3">
                        <div class="row gy-3">
                            <div class="col-12" v-if="list.assigned">
                                <div>
                                    <p class="text-muted fs-11 mb-0">{{ list.assigned.designation.name }} :</p>
                                    <h5 class="fs-12 fw-semibold text-warning" v-if="list.assigned.is_oic">{{ list.assigned.oic.profile.fullname }}</h5>
                                    <h5 class="fs-12 fw-semibold text-info" v-else>{{ list.assigned.user.profile.fullname }}</h5>
                                </div>
                            </div>
                            <div class="col-12" v-else>
                                <div>
                                    <p class="text-muted fs-11 mb-0">Division Head :</p>
                                    <h5 class="fs-12 fw-semibold">-</h5>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1 mb-n4">
                            <div class="col-6">
                                <div v-if="list.assigned??designationable">
                                    <p class="text-muted mb-0 fs-11">Signatory :</p>
                                    <h5 class="fs-12" v-if="list.assigned.designationable.is_oic">{{ list.assigned.designationable.oic.profile.name }}</h5>
                                    <h5 class="fs-12" v-else>{{ list.assigned.designationable.user.profile.name }}</h5>
                                </div>
                                <div v-else>
                                    <p class="text-muted mb-0 fs-11">Signatory :</p>
                                    <h5 class="fs-12">-</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <p class="text-muted mb-0 fs-11">Date</p>
                                     <h5 class="fs-12" v-if="list.assigned??designationable">-</h5>
                                    <h5 class="fs-12" v-else>-</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header p-0 border-0 bg-light-subtle">
                    <div class="row g-0 text-center fs-10">
                        <div class="col-md-12">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1 fs-12">{{ filteredEmployees.filter(e => e.organization?.division?.id === list.value).length }}</h5>
                                <p class="text-muted mb-0">No. of Employees</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom mb-n4">
                    <div class="table-responsive table-card" style="height: calc(100vh - 615px); overflow: auto;">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th style="width: 5%;"></th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="employee in filteredEmployees.filter(e => e.organization?.division?.id === list.value)" :key="employee.id">
                                    <td>
                                       <img
                                            :src="employee.avatar"
                                            class="rounded-circle"    
                                            style="width:28px;height:28px;object-fit:cover;"
                                            alt="Avatar"
                                        >
                                    </td>
                                    <td>
                                       <h5 class="fs-12 mb-0 fw-semibold text-primary">{{employee.name}}</h5>
                                        <p class="fs-11 text-muted mb-0">{{employee.organization.unit.name}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="card-footer bg-transparent border-top-dashed py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="avatar-group">
                                <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" aria-label="Darline Williams" data-bs-original-title="Darline Williams">
                                    <div class="avatar-xxs">
                                        <img src="assets/images/users/avatar-2.jpg" alt="" class="rounded-circle img-fluid">
                                    </div>
                                </a>
                                <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" data-bs-original-title="Add Members">
                                    <div class="avatar-xxs">
                                        <div class="avatar-title fs-16 rounded-circle bg-light border-dashed border text-primary">
                                            +
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="text-muted">
                                <i class="ri-calendar-event-fill me-1 align-bottom"></i> 10 Jul, 2021
                            </div>
                        </div>

                    </div>

                </div> -->
            </div>
        </div>

        <div class="col-md-4 project-card" v-for="(list,index) in sortedShifts" :key="index" v-if="sort == 'shifts'">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="p-3 mt-n3 mx-n3 mb-n3 bg-info-subtle rounded-top">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-12 fw-semibold text-primary">{{ list.name }}</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex gap-1 align-items-center my-n2">
                                    <button type="button" class="btn avatar-xs p-0 favourite-btn material-shadow-none active">
                                        <span class="avatar-title bg-transparent fs-15">
                                            <i class="ri-star-fill"></i>
                                        </span>
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-1 mt-n1 py-0 text-decoration-none fs-15 material-shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal icon-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="apps-projects-overview.html"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                            <a class="dropdown-item" href="apps-projects-create.html"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#removeProjectModal"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header p-0 border-0 bg-light-subtle">
                    <div class="row g-0 text-center fs-10">
                        <div class="col-md-12">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1 fs-12">{{ employeesForShift(list).length }}</h5>
                                <p class="text-muted mb-0">No. of Employees</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card" style="height: calc(100vh - 680px); overflow: auto;">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th style="width: 5%;"></th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="employee in employeesForShift(list)" :key="employee.id">
                                    <td>
                                        <img
                                            :src="employee.avatar"
                                            class="rounded-circle"
                                            style="width:28px;height:28px;object-fit:cover;"
                                            alt="Avatar"
                                        >
                                    </td>
                                    <td>
                                       <h5 class="fs-12 mb-0 fw-semibold text-primary">{{employee.name}}</h5>
                                        <p class="fs-11 text-muted mb-0">{{employee.organization.unit.name}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 project-card" v-for="(list,index) in sortedUnits" :key="index" v-if="sort == 'units'">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="p-3 mt-n3 mx-n3 mb-n3 bg-info-subtle rounded-top">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-12 fw-semibold text-primary">{{ list.name }}</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex gap-1 align-items-center my-n2">
                                    <button type="button" class="btn avatar-xs p-0 favourite-btn material-shadow-none active">
                                        <span class="avatar-title bg-transparent fs-15">
                                            <i class="ri-star-fill"></i>
                                        </span>
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-1 mt-n1 py-0 text-decoration-none fs-15 material-shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal icon-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="apps-projects-overview.html"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                            <a class="dropdown-item" href="apps-projects-create.html"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#removeProjectModal"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header p-0 border-0 bg-light-subtle">
                    <div class="row g-0 text-center fs-10">
                        <div class="col-md-12">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1 fs-12">{{ filteredEmployees.filter(e => e.organization?.unit?.id === list.value).length }}</h5>
                                <p class="text-muted mb-0">No. of Employees</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card" style="height: calc(100vh - 680px); overflow: auto;">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th style="width: 5%;"></th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="employee in filteredEmployees.filter(e => e.organization?.unit?.id === list.value)" :key="employee.id">
                                    <td>
                                        <img
                                            :src="employee.avatar"
                                            class="rounded-circle"    
                                            style="width:28px;height:28px;object-fit:cover;"
                                            alt="Avatar"
                                        >
                                    </td>
                                    <td>
                                       <h5 class="fs-12 mb-0 fw-semibold text-primary">{{employee.name}}</h5>
                                        <p class="fs-11 text-muted mb-0">{{employee.organization.unit.name}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 project-card" v-for="(list,index) in sortedStations" :key="index" v-if="sort == 'stations'">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="p-3 mt-n3 mx-n3 mb-n3 bg-info-subtle rounded-top">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 fs-12 fw-semibold text-primary">{{ list.name }}</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex gap-1 align-items-center my-n2">
                                    <button type="button" class="btn avatar-xs p-0 favourite-btn material-shadow-none active">
                                        <span class="avatar-title bg-transparent fs-15">
                                            <i class="ri-star-fill"></i>
                                        </span>
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-1 mt-n1 py-0 text-decoration-none fs-15 material-shadow-none" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal icon-sm"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="apps-projects-overview.html"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a>
                                            <a class="dropdown-item" href="apps-projects-create.html"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#removeProjectModal"><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header p-0 border-0 bg-light-subtle">
                    <div class="row g-0 text-center fs-10">
                        <div class="col-md-12">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1 fs-12">{{ filteredEmployees.filter(e => e.organization?.station?.id === list.value).length }}</h5>
                                <p class="text-muted mb-0">No. of Employees</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom">
                    <div class="table-responsive table-card" style="height: calc(100vh - 680px); overflow: auto;">
                        <table class="table align-middle table-striped table-centered mb-0">
                            <thead class="table-light thead-fixed">
                                <tr class="fs-11">
                                    <th style="width: 5%;"></th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="employee in filteredEmployees.filter(e => e.organization?.station?.id === list.value)" :key="employee.id">
                                    <td>
                                        <img
                                            :src="employee.avatar"
                                            class="rounded-circle"    
                                            style="width:28px;height:28px;object-fit:cover;"
                                            alt="Avatar"
                                        >
                                    </td>
                                    <td>
                                       <h5 class="fs-12 mb-0 fw-semibold text-primary">{{employee.name}}</h5>
                                        <p class="fs-11 text-muted mb-0">{{employee.organization.unit.name}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import _ from "lodash";
import Multiselect from "@vueform/multiselect";
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components: {
        PageHeader,
        Multiselect
    },
    props: ['dropdowns'],
    data() {
        return {
            employees: [],
            filter: {
                keyword: null,
                type: null,
                status: null,
                division: null,
                station: null,
                unit: null
            },
            index: null,
            selectedLetter: 'All',
            sortPosition: 'asc',
            year: new Date().getFullYear(),
            alphabet: [
                'All',
                ...'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')
            ],
            defaultAvatar: "/images/users/avatar-10.jpg",
            sort: 'lists'
        };
    },
    computed: {
        sortedShifts() {
            const shifts = [...this.dropdowns.shifts, { value: 'no-shift', name: 'No Work Schedule' }];
            return shifts.sort((a, b) => {
                const countA = this.employeesForShift(a).length;
                const countB = this.employeesForShift(b).length;

                return countB - countA; // Highest to lowest
            });
        },
        sortedUnits() {
            return [...this.dropdowns.units].sort((a, b) => {
                const countA = this.filteredEmployees.filter(
                    e => e.organization?.unit?.id === a.value
                ).length;

                const countB = this.filteredEmployees.filter(
                    e => e.organization?.unit?.id === b.value
                ).length;

                return countB - countA; // Highest to lowest
            });
        },
        sortedStations() {
            return [...this.dropdowns.stations].sort((a, b) => {
                const countA = this.filteredEmployees.filter(
                    e => e.organization?.station?.id === a.value
                ).length;

                const countB = this.filteredEmployees.filter(
                    e => e.organization?.station?.id === b.value
                ).length;

                return countB - countA; // Highest to lowest
            });
        },
        filteredEmployees() {
            let list = [...this.employees];
            if (this.filter.keyword && this.filter.keyword.trim() !== '') {
                const keyword = this.filter.keyword.toLowerCase().trim();

                list = list.filter(employee =>
                    employee.name?.toLowerCase().includes(keyword)
                );
            }
            if (this.filter.division) {
                list = list.filter(employee =>
                    employee.organization?.division?.id === this.filter.division
                );
            }
            if (this.filter.type) {
                list = list.filter(employee =>
                    employee.organization?.type?.id === this.filter.type
                );
            }
            if (this.filter.station) {
                list = list.filter(employee =>
                    employee.organization?.station?.id === this.filter.station
                );
            }
            if (this.filter.status) {
                list = list.filter(employee =>
                    employee.organization?.status?.id === this.filter.status
                );
            }
            if (this.selectedLetter !== 'All') {
                list = list.filter(employee =>
                    employee.name
                        ?.toUpperCase()
                        .startsWith(this.selectedLetter)
                );
            }

            list.sort((a, b) => {
                const posA = a.organization?.position?.id ?? 999;
                const posB = b.organization?.position?.id ?? 999;
                if (this.sortPosition === 'asc') {
                    return posA - posB;
                }
                return posB - posA;
            });

            return list;
        }
    },
    created() {
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function () {
            this.fetch();
        }, 300),
        fetch(page_url) {
            page_url = page_url || "/whereabouts";
            axios.get(page_url, {
                params: {
                    option: "list"
                }
            })
            .then(response => {
                this.employees = response.data.data.map(employee => ({
                    ...employee,
                    avatarSrc: employee.avatar || this.defaultAvatar
                }));
                console.log(this.employees[0]);
            })
            .catch(err => console.log(err));

        },
        toggleSortPosition() {
            this.sort = 'lists';
            this.sortPosition = this.sortPosition === 'asc' ? 'desc' : 'asc';
        },
        employeesForShift(list) {
            if (list.value === 'no-shift') {
                return this.filteredEmployees.filter(e => !e.organization?.shift?.id);
            }
            return this.filteredEmployees.filter(e => e.organization?.shift?.id === list.value);
        }
    }
};
</script>