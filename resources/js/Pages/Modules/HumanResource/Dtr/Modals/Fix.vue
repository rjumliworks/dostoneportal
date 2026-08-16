<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Fix / Recheck DTR Records" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-1">
                    <InputLabel value="Fix By"/>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" v-model="form.mode" value="range" id="fixModeRange">
                            <label class="form-check-label" for="fixModeRange">Date Range</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" v-model="form.mode" value="month" id="fixModeMonth">
                            <label class="form-check-label" for="fixModeMonth">Month</label>
                        </div>
                    </div>
                </BCol>
                <template v-if="form.mode === 'range'">
                    <BCol lg="6">
                        <InputLabel value="From Date" :message="form.errors.from"/>
                        <input type="date" v-model="form.from" class="form-control" @input="handleInput('from')">
                    </BCol>
                    <BCol lg="6">
                        <InputLabel value="To Date" :message="form.errors.to"/>
                        <input type="date" v-model="form.to" class="form-control" @input="handleInput('to')">
                    </BCol>
                </template>
                <template v-else>
                    <BCol lg="6">
                        <InputLabel value="Month" :message="form.errors.month"/>
                        <Multiselect :options="months" v-model="form.month" label="name" :allow-empty="false" :searchable="true" placeholder="Select Month" />
                    </BCol>
                    <BCol lg="6">
                        <InputLabel value="Year" :message="form.errors.year"/>
                        <TextInput id="year" v-model="form.year" type="text" class="form-control" :placeholder="form.year" @input="handleInput('year')" :light="true"/>
                    </BCol>
                </template>
                <BCol lg="12">
                    <InputLabel value="Station"/>
                    <Multiselect :options="stations" v-model="form.station" label="others" :searchable="true" placeholder="All Stations" />
                </BCol>
                <BCol lg="12">
                    <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show material-shadow fs-12" role="alert">
                        <i class="ri-alert-line label-icon"></i><strong>Fix / Recheck</strong> -
                        This will recompute tardiness and undertime for every DTR record within the selected range using the current shift and holiday schedule. Use this after a holiday date has changed to roll back records that were auto-adjusted.
                    </div>
                </BCol>
                <BCol lg="12" v-if="result">
                    <div class="alert alert-success fs-12 mb-0">{{ result }}</div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button @click="submit()" variant="warning" :disabled="form.processing" block>
                <i class="bx bx-refresh align-bottom"></i> Fix Records
            </b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    props: ['stations'],
    components: { Multiselect, TextInput, InputLabel },
    data(){
        return {
            form: useForm({
                mode: 'range',
                from: null,
                to: null,
                month: null,
                year: new Date().getFullYear(),
                station: null,
                option: 'bulk_recheck'
            }),
            months: [
                { value: 1, name: 'January' },
                { value: 2, name: 'February' },
                { value: 3, name: 'March' },
                { value: 4, name: 'April' },
                { value: 5, name: 'May' },
                { value: 6, name: 'June' },
                { value: 7, name: 'July' },
                { value: 8, name: 'August' },
                { value: 9, name: 'September' },
                { value: 10, name: 'October' },
                { value: 11, name: 'November' },
                { value: 12, name: 'December' }
            ],
            result: null,
            showModal: false
        }
    },
    methods: {
        show(){
            this.result = null;
            this.showModal = true;
        },
        submit(){
            this.result = null;
            if(this.form.mode === 'range'){
                this.form.month = null;
                this.form.year = null;
            }else{
                this.form.from = null;
                this.form.to = null;
            }
            this.form.put('/dtrs/update', {
                preserveScroll: true,
                onSuccess: (response) => {
                    this.result = response.props.flash.info;
                    this.$emit('update');
                },
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
