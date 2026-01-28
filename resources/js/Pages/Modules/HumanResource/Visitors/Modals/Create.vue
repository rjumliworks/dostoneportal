<template>
     <!-- style="--vz-modal-width: 750px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Add Visitor" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Name"  :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" @input="form.name = capitalizeWords(form.name)" type="text" class="form-control" placeholder="Please enter name" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0 mb-1">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="testname" value="Affiliation" :message="form.errors.affiliation_id"/>
                            <Multiselect @search-change="checkAffiliation" 
                            @input="handleInput('affiliation_id')"
                            :options="affiliations" label="name" :searchable="true" 
                            v-model="form.affiliation_id" 
                            placeholder="Select Affiliation" ref="multiselectA"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd('Affiliation')" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol> 
                <BCol lg="12" class="mt-0 mb-1">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="testname" value="Designation" :message="form.errors.designation_id"/>
                            <Multiselect @search-change="checkDesignation" 
                            @input="handleInput('designation_id')"
                            :options="designations" label="name" :searchable="true" 
                            v-model="form.designation_id" 
                            placeholder="Select Designation" ref="multiselectD"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd('Designation')" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol> 
                <BCol lg="12" class="mt-0 mb-1">
                    <InputLabel for="testname" value="Type" :message="form.errors.type_id"/>
                    <Multiselect
                    @input="handleInput('type_id')"
                    :options="dropdowns.types" label="name" :searchable="true" 
                    v-model="form.type_id" 
                    placeholder="Select Designation" ref="multiselect"/>
                </BCol> 
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
    <Add @selected="setName" ref="add"/>
</template>
<script>
import _ from 'lodash';
import Add from './Add.vue';
import { useForm } from '@inertiajs/vue3';
import Multiselect from '@/Shared/Components/Forms/Multiselect.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, Add },
    props:['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                name: null,
                affiliation_id: null,
                designation_id: null,
                type_id: null,
                status_id: 44,
                option: 'visitor'
            }),
            affiliations: [],
            affiliation: null,
            designations: [],
            designation: null,
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            this.form.post('/visitors',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.form.clearErrors();
                    this.form.reset();
                    this.hide();
                },
            });
        },
        checkAffiliation: _.debounce(function(string) {
            this.fetchAffiliation(string);
            this.affiliation = string;
        }, 300),
        fetchAffiliation(code){
            axios.get('/visitors',{
                params: {
                    option: 'search',
                    type: 'Affiliation',
                    keyword: code
                }
            })
            .then(response => {
                this.affiliations = response.data;
            })
            .catch(err => console.log(err));
        },
        checkDesignation: _.debounce(function(string) {
            this.fetchDesignation(string);
            this.designation = string;
        }, 300),
        fetchDesignation(code){
            axios.get('/visitors',{
                params: {
                    option: 'search',
                    type: 'Designation',
                    keyword: code
                }
            })
            .then(response => {
                this.designations = response.data;
            })
            .catch(err => console.log(err));
        },
        setName(data){
            if(data.type == 'Affiliation'){
                this.fetchAffiliation(data.name);
                this.$refs.multiselectA.emitSelectedValues(data.value);
            }else{
                this.fetchDesignation(data.name);
                this.$refs.multiselectD.emitSelectedValues(data.value);
            }
        },
        openAdd(type){
            this.type = type;
            const key = this.affiliation;
            this.$refs.add.show(type,key);
        },
        capitalizeWords(str) {
            return str
            ? str
                .toLowerCase()
                .replace(/\b\w/g, char => char.toUpperCase())
            : '';
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