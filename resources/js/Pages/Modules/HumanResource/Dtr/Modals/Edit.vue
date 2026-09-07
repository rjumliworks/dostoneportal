<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Update Date Time Record" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform" v-if="selected">
            <BRow class="g-3">
                <div class="col-md-6">
                    <InputLabel for="name" value="Old Time" />
                    <TextInput id="name" v-model="selected.time" type="text" class="form-control" readonly="" :light="true"/>
                </div>
                <div class="col-md-6">
                    <InputLabel for="name" value="New Time" />
                    <TextInput id="name" v-model="form.to_time" type="time" class="form-control" placeholder="Please enter time" @input="handleInput('to_time')" :light="true"/>
                </div>
                <div class="col-md-12 mt-0">
                    <InputLabel for="name" value="Time Slot" />
                    <select v-model="form.to_type" class="form-select" @change="handleInput('to_type')">
                        <option v-for="option in typeOptions" :key="option.value" :value="option.value">{{ option.label }}</option>
                    </select>
                    <p v-if="willSwap" class="fs-11 text-warning mt-1 mb-0">
                        <i class="ri-error-warning-line align-bottom"></i> {{ form.to_type }} already has a record for this day — the two will be swapped.
                    </p>
                    <p v-else-if="willMove" class="fs-11 text-muted mt-1 mb-0">
                        <i class="ri-information-line align-bottom"></i> This record will be moved from {{ form.type }} to {{ form.to_type }}.
                    </p>
                </div>
                <div class="col-md-12 mt-0">
                    <InputLabel for="name" value="Remarks" />
                    <TextInput id="name" v-model="form.remarks" type="text" class="form-control" placeholder="Please enter remarks" @input="handleInput('remarks')" :light="true"/>
                </div>
                <div class="col-md-12 mt-0 mb-n3" v-if="selected.changes.length > 0">
                    <hr class="text-muted"/>
                </div>
                <div class="col-md-12" v-if="selected.changes.length > 0">
                    <p class="fs-12 mb-2">Changes on the {{ form.type }} :</p>
                    <ul>
                        <li class="text-muted fs-11" v-for="(list,index) in selected.changes" v-bind:key="index">{{ list }}</li>
                    </ul>
                </div>
            </BRow>
        </form>
          <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';

const TYPE_TO_COLUMN = {
    'Time In (am)': 'am_in_at',
    'Time Out (am)': 'am_out_at',
    'Time In (pm)': 'pm_in_at',
    'Time Out (pm)': 'pm_out_at',
};
const ALL_TYPES = Object.keys(TYPE_TO_COLUMN);

export default {
    components: { TextInput, InputLabel },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            record: null,
            form: useForm({
                id: null,
                remarks: null,
                from_time: null,
                to_time: null,
                type: null,
                from_type: null,
                to_type: null,
                option: 'dtr'
            }),
            showModal: false
        }
    },
    computed: {
        typeOptions(){
            return ALL_TYPES.map(type => ({
                value: type,
                label: type === this.form.type ? `${type} — keep here` : type
            }));
        },
        willMove(){
            return !!this.form.to_type && this.form.to_type !== this.form.type;
        },
        willSwap(){
            if(!this.willMove || !this.record){
                return false;
            }
            return !!this.record[TYPE_TO_COLUMN[this.form.to_type]];
        }
    },
    methods: {
        show(id,data,type,record){
            this.selected = null;
            this.selected = data;
            this.record = record || null;
            this.form.type = type;
            this.form.to_type = type;
            this.form.id = id;
            this.form.from_time = data.time;
            this.showModal = true;
        },
        submit(){
            this.form.option = this.willMove ? 'swap' : 'dtr';
            this.form.from_type = this.form.type;
            this.form.put('/dtrs/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',response.props.flash.data.data)
                    this.hide();
                },
            });
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.showModal = false;
        }
    }
}
</script>
