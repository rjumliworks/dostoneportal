<template>
    <!-- style="--vz-modal-width: 600px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="View DTR" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" v-if="!selected">
                   <form class="app-search d-none d-md-block mb-n3 mt-n2">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Search Employee" autocomplete="off" id="search-options" />
                            <span class="mdi mdi-magnify search-widget-icon"></span>
                            <span @click="clear()" class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" id="search-close-options"></span>
                        </div>
                        <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                            <SimpleBar data-simplebar >
                                <div class="notification-list">
                                    <b-link @click="chooseUser(list)" v-for="(list, index) of names" :key="index" class="d-flex dropdown-item notify-item py-2">
                                        <img :src="currentUrl+'/images/avatars/avatar.jpg'" class="me-3 rounded-circle avatar-xs" alt="user-pic" />
                                        <div class="flex-1">
                                            <h6 class="m-0">{{ list.name}}</h6>
                                            <span class="fs-11 mb-0 text-muted">{{list.position}}</span>
                                        </div>
                                    </b-link>
                                </div>
                            </SimpleBar>
                        </div>
                    </form>
                     <hr class="text-muted"/>
                </BCol>
                <BCol lg="12" v-else>
                    <ul class="list-unstyled mb-0 vstack gap-3">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img :src="currentUrl+'/images/avatars/avatar.jpg'" alt="" class="avatar-sm rounded material-shadow">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="fs-14 mb-0">{{selected.name}}</h6>
                                    <p class="text-muted mb-0">{{selected.position}}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <hr class="text-muted"/>
                </BCol>
                <BCol lg="6" class="mt-n1">
                    <InputLabel for="name" value="Month" :message="form.errors.year"/>
                    <Multiselect :options="months" v-model="form.month" label="name" :allow-empty="false" :searchable="true" placeholder="Select Month" />
                </BCol>
                <BCol lg="6" class="mt-n1">
                    <InputLabel for="name" value="Year" :message="form.errors.year"/>
                    <TextInput id="name" v-model="form.year" type="text" class="form-control" :placeholder="form.year" @input="handleInput('year')" :light="true"/>
                </BCol>
                <BCol lg="12">
                    <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show material-shadow fs-12" role="alert">
                        <i class="ri-alert-line label-icon"></i><strong>Print Option</strong> -
                        Please open the PDF in Adobe Acrobat Reader, select A4 paper size, and choose 'Actual Size' in the print settings before printing
                    </div>
                </BCol>
            </BRow>
        </form>
          <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Generate</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, TextInput, InputLabel },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                month: null,
                year: new Date().getFullYear(),
                option: 'dtr'
            }),
            months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            names: [],
            selected: null,
            keyword: null,
            showModal: false
        }
    },
    mounted() {
        this.isCustomDropdown();
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
            axios.get('/search', {
                params: {
                    keyword: this.keyword,
                    option: 'users'
                }
            })
            .then(response => {
                if(response){ 
                    this.scholar = {};
                    this.names = response.data; 
                }
            })
            .catch(err => console.log(err));
        },
        chooseUser(data){
            this.selected = data;
        }, 
        submit(){
            window.open('/dtrs?option=print&id='+this.selected.value+'&month='+this.form.month+'&year='+this.form.year);
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
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>
<style scoped>
    .dropdown-menu-lg {
        width: 95%;
    }
</style>