<template>
    <Head title="Whereabouts" />
    <PageHeader title="Whereabouts" pageTitle="List" />

    <BRow>

        <div class="col-md-12">
            <b-card no-body class="bg-white-subtle border shadow-none">
                <b-card-body>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-lg-row flex-column">
                                <div class="flex-grow-1">
                                    <h4 class="fs-14 mb-0">Summary View</h4>
                                    <p class="text-muted mb-0">
                                        Here's what's happening with the laboratory for month of
                                        {{ month }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </b-card-body>
            </b-card>
        </div>

        <div
            class="row job-list-row"
            id="candidate-list"
            style="height: calc(100vh - 320px); overflow: auto;"
        >

            <div
                class="col-xxl-3 col-md-6"
                v-for="(list,index) in employees"
                :key="index"
            >

                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <img
                                        :src="list.avatarSrc"
                                        loading="lazy"
                                        class="member-img img-fluid d-block rounded"
                                        alt="Avatar"
                                    >
                                </div>
                            </div>

                            <div class="flex-grow-1 ms-3">
                                <h5 class="fs-12 mb-1 text-primary">
                                    {{ list.name }}
                                </h5>

                                <p class="text-muted fs-11 mb-0">
                                    {{ list.organization.position.name }}
                                </p>

                                <p class="text-muted fs-11 mb-0">
                                    {{ list.organization.division.name }}
                                </p>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </BRow>
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

    data() {
        return {
            employees: [],
            month: new Date().toLocaleString("default", {
                month: "long"
            }),
            months: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ],
            icons: [
                "ri-flight-takeoff-fill",
                "ri-car-fill",
                "ri-calendar-2-fill"
            ],
            index: null,
            year: new Date().getFullYear(),

            // Change this to your own placeholder image
            defaultAvatar: "/assets/images/users/avatar-10.jpg"
        };
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
                    avatarSrc: this.defaultAvatar
                }));

                this.loadAvatars();

            })
            .catch(err => console.log(err));

        },

        loadAvatars() {

            this.employees.forEach(employee => {

                if (!employee.avatar) return;

                const img = new Image();

                img.onload = () => {
                    employee.avatarSrc = employee.avatar;
                };

                img.onerror = () => {
                    // Keep placeholder
                };

                img.src = employee.avatar;

            });

        }

    }

};
</script>