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
export default {
    components: { TextInput, InputLabel },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            form: useForm({
                id: null,
                remarks: null,
                from_time: null,
                to_time: null,
                type: null,
                option: 'dtr'
            }),
            showModal: false
        }
    },
    methods: { 
        show(id,data,type){
            this.selected = null;
            this.selected = data;
            this.form.type = type;
            this.form.id = id;
            this.form.from_time = data.time;
            this.showModal = true;
        },
        submit(){
            this.form.put('/visitors/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    console.log(response.props.flash.data);
                    this.$emit('update',response.props.flash.data)
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