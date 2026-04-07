<template>
    <!-- style="--vz-modal-width: 600px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="View DTR" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-2">
                    <InputLabel for="name" value="Station" :message="form.errors.year"/>
                    <Multiselect :options="stations" v-model="form.station" label="name" :allow-empty="false" :searchable="true" placeholder="Select Station" />
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="Month" :message="form.errors.year"/>
                    <Multiselect :options="months" v-model="form.month" label="name" :allow-empty="false" :searchable="true" placeholder="Select Month" />
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="name" value="Year" :message="form.errors.year"/>
                    <TextInput id="name" v-model="form.year" type="text" class="form-control" :placeholder="form.year" @input="handleInput('year')" :light="true"/>
                </BCol>
            </BRow>
        </form>
          <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>View PDF</b-button>
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
    props: ['stations'],
    components: { Multiselect, TextInput, InputLabel },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                month: null,
                station: null,
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
            window.open('/dtrs?option=print_bulk&station='+this.form.station+'&month='+this.form.month+'&year='+this.form.year);
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