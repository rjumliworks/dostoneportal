<template>
    <b-modal
        style="--vz-modal-width: 600px;"
        v-model="showModal"
        header-class="p-3 bg-light"
        title="Add Employee"
        class="v-modal-custom"
        modal-class="zoomIn"
        centered
        no-close-on-backdrop
    >
        <form class="customform">
            <BRow class="g-3 p-1 mb-2">
                <!-- All Regular Employees -->
                <div class="col-sm-12" v-if="!selected || selected === 'all'">
                    <div class="form-check card-radio">
                        <input
                            id="paymentMethod01"
                            name="paymentMethod"
                            v-model="selected"
                            value="all"
                            type="radio"
                            @click="toggleSelect('all')"
                            class="form-check-input"
                        />
                        <label class="form-check-label" for="paymentMethod01">
                            <span class="fs-15 text-muted me-2">
                                <i class="ri-team-fill text-success align-bottom"></i>
                            </span>
                            <span class="fs-13 text-wrap">All Regular Employees</span>
                        </label>
                    </div>

                    <div v-if="selected === 'all'" class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show material-shadow mt-4 fs-12 mb-n2" role="alert">
                        <i class="ri-check-double-line label-icon"></i>
                        All <strong>regular employees</strong> will be automatically included in this payroll
                    </div>
                </div>

                <!-- Custom Regular Employees -->
                <div class="col-sm-12" v-if="!selected || selected === 'custom'">
                    <div class="form-check card-radio">
                        <input
                            id="paymentMethod02"
                            name="paymentMethod"
                            v-model="selected"
                            value="custom"
                            type="radio"
                            @click="toggleSelect('custom')"
                            class="form-check-input"
                        />
                        <label class="form-check-label" for="paymentMethod02">
                            <span class="fs-15 text-muted me-2">
                                <i class="ri-user-add-fill text-secondary align-bottom"></i>
                            </span>
                            <span class="fs-13 text-wrap">Custom Regular Employees</span>
                        </label>
                    </div>
                    <div v-if="selected === 'custom'" class="alert alert-secondary alert-dismissible alert-label-icon label-arrow fade show material-shadow mt-4 fs-12 mb-n2" role="alert">
                        <i class="ri-check-double-line label-icon"></i>
                        Only the selected <strong>regular employees</strong> will be added to this payroll
                    </div>
                </div>

                <!-- Except Regular Employees -->
                <div class="col-sm-12" v-if="!selected || selected === 'except'">
                    <div class="form-check card-radio">
                        <input
                            id="paymentMethod03"
                            name="paymentMethod"
                            v-model="selected"
                            value="except"
                            type="radio"
                            @click="toggleSelect('except')"
                            class="form-check-input"
                        />
                        <label class="form-check-label" for="paymentMethod03">
                            <span class="fs-15 text-muted me-2">
                                <i class="ri-user-unfollow-fill text-danger align-bottom"></i>
                            </span>
                            <span class="fs-13 text-wrap">Except Regular Employees</span>
                        </label>
                    </div>
                    <div v-if="selected === 'except'" class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show material-shadow mt-4 fs-12 mb-n2" role="alert">
                        <i class="ri-check-double-line label-icon"></i>
                        This is the list of <strong>regular employees</strong> who will <strong>not</strong> be included in this payroll; all others will be automatically added.
                    </div>
                </div>

                <template v-if="selected === 'custom' || selected === 'except'">
                    
                    <BCol lg="12" class="mt-2">
                        <hr class="text-muted"/>
                    </BCol>
                    <BCol lg="12" class="mt-n1">
                        <form class="app-search d-none d-md-block mb-n3" style="margin-top: -13px;">
                            <div class="position-relative">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search Employee"
                                    autocomplete="off"
                                    id="search-options"
                                />
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span
                                   
                                    class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                                    id="search-close-options"
                                ></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                                <SimpleBar data-simplebar>
                                    <div class="notification-list">
                                        <b-link
                                            @click="chooseUser(list)"
                                            v-for="(list, index) of names"
                                            :key="index"
                                            class="d-flex dropdown-item notify-item py-2"
                                        >
                                            <img :src="list.avatar" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                                            <div class="flex-1">
                                                <h6 class="m-0">{{ list.name }}</h6>
                                                <span class="fs-11 mb-0 text-muted">{{ list.position }}</span>
                                            </div>
                                        </b-link>
                                    </div>
                                </SimpleBar>
                            </div>
                        </form>
                    </BCol>
                    <BCol lg="12">
                        <div class="card bg-light-subtle shadow-none border">
                            <div class="card-header bg-light-subtle">
                                <div class="d-flex mb-n3">
                                    <div class="flex-shrink-0 me-3">
                                        <div style="height:2rem;width:2rem;">
                                            <span class="avatar-title bg-primary-subtle rounded">
                                                <i class="ri-team-fill text-primary fs-16"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0 fs-12"><span class="text-body">{{ users.length }} selected regular employees</span></h5>
                                        <p v-if="selected == 'all'" class="text-muted text-truncate-two-lines fs-11">All regular employees will be included in this payroll.</p>
                                        <p v-else-if="selected == 'custom'"class="text-muted text-truncate-two-lines fs-11">Only the selected regular employees will be added to this payroll.</p>
                                        <p v-else-if="selected == 'except'"class="text-muted text-truncate-two-lines fs-11">These regular employees will not be included in this payroll; the rest will be added automatically.</p>
                                    </div>
                                
                                </div>
                            </div>
                            <div class="card-body bg-white rounded-bottom">
                                <div class="table-responsive table-card" style="max-height: calc(100vh - 665px); overflow: auto;">
                                    <table class="table align-middle table-centered mb-0">
                                        <thead class="table-light thead-fixed">
                                            <tr class="fs-11">
                                                <th style="width: 10%;"></th>
                                                <th >Name</th>
                                                <th style="width: 6%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-white fs-12">
                                            <tr v-for="(list,index) in users" v-bind:key="index">
                                                <td class="text-center"> 
                                                    <div class="avatar-xs chat-user-img online">
                                                        <img :src="list.avatar" alt="" class="avatar-xs rounded-circle">
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="fs-12 mb-0 text-primary">{{list.name}}</h5>
                                                    <p class="fs-11 text-muted mb-0">{{list.position}}</p>
                                                </td>
                                                
                                                <td class="text-end">
                                                    <b-button @click="openRemove(list,index)" variant="soft-danger" class="me-1" v-b-tooltip.hover title="Remove" size="sm">
                                                        <i class="ri-delete-bin-6-fill align-bottom"></i>
                                                    </b-button>
                                                   
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </BCol>
                </template>
            </BRow>
        </form>

        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button
                v-if="selected"
                @click="submit('ok')"
                variant="primary"
                :disabled="form.processing"
                block
            >
                Submit
            </b-button>
        </template>
    </b-modal>
</template>


<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
export default {
    props: ['is_regular','id'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: this.id,
                type: null,
                users: [],
                is_regular: this.is_regular,
                option: 'payroll'
            }),
            selected: null,
            users: [],
            names: [],
            keyword: null,
            showModal: false
        }
    },
    watch: {
        selected(val){
            if(val == 'custom' || val == 'except'){
              this.$nextTick(() => {
                    this.isCustomDropdown();
                });
            }else{
                this.users = [];
            }
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        checkSearchStr: _.debounce(function (string) {
            this.keyword = string;
            this.search();
        }, 500),
        search(){
            axios.get('/payroll', {
                params: {
                    keyword: this.keyword,
                    is_regular: this.is_regular,
                    cutoff_id: this.id,
                    ids: this.users.map(user => user.value),
                    option: 'search'
                }
            })
            .then(response => {
                if(response){ 
                    this.names = response.data; 
                }
            })
            .catch(err => console.log(err));
        },
        submit(){
            this.form.users = this.users.map(user => user.value);
            this.form.type = this.selected;
            this.form.post('/payroll',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        toggleSelect(value) {
            this.selected = this.selected === value ? null : value;
        },
        chooseUser(data){
            const exists = this.users.some(user => user.value === data.value);
            if (exists) return;
            this.users.unshift(data);
            this.keyword = null;
            document.getElementById("search-options").value = "";
            document.getElementById("search-options").focus();
            this.names = [];
        }, 
        openRemove(user, index) {
            this.users.splice(index, 1);
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.users = [];
            this.names = [];
            this.selected = null;
            this.showModal = false;
        },
        isCustomDropdown() {
            var searchOptions = document.getElementById("search-close-options");
            var dropdown = document.getElementById("search-dropdown");
            var searchInput = document.getElementById("search-options");

            searchInput.addEventListener("focus", () => {
                var inputLength = searchInput.value.length;
                if (inputLength > 0) {
                    dropdown.classList.add("show");
                    searchOptions.classList.remove("d-none");
                } else {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });

            searchInput.addEventListener("keyup", () => {
                var inputLength = searchInput.value.length;
                if (inputLength > 0) {
                    dropdown.classList.add("show");
                    searchOptions.classList.remove("d-none");
                    this.checkSearchStr(searchInput.value);
                } else {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });

            searchOptions.addEventListener("click", () => {
                searchInput.value = "";
                dropdown.classList.remove("show");
                searchOptions.classList.add("d-none");
            });

            document.body.addEventListener("click", (e) => {
                if (e.target.getAttribute("id") !== "search-options") {
                    dropdown.classList.remove("show");
                    searchOptions.classList.add("d-none");
                }
            });
        }
    }
}
</script>
<style scoped>
    .dropdown-menu-lg {
        width: 95%;
    }
</style>