<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" :title="(editable) ? 'Update Equipment' : 'Add Equipment'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>

        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Name" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="e.g. Mini Computer (TOS-ZCHRD1)" @input="handleInput('name')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="type" value="Type" :message="form.errors.type_id"/>
                    <Multiselect :options="dropdowns.types" :searchable="true" label="name" v-model="form.type_id" placeholder="Select Type" @input="handleInput('type_id')"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="status" value="Status" :message="form.errors.status_id"/>
                    <Multiselect :options="dropdowns.statuses" :searchable="true" label="name" v-model="form.status_id" placeholder="Select Status" @input="handleInput('status_id')"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="station" value="Station" :message="form.errors.station_id"/>
                    <Multiselect :options="dropdowns.stations" :searchable="true" label="name" v-model="form.station_id" placeholder="Select Station" @input="handleInput('station_id')"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="old_code" value="Old Code (if migrated)" :message="form.errors.old_code"/>
                    <TextInput id="old_code" v-model="form.old_code" type="text" class="form-control" placeholder="Please enter old code" @input="handleInput('old_code')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="acquired_at" value="Date Acquired" :message="form.errors.acquired_at"/>
                    <TextInput id="acquired_at" v-model="form.acquired_at" type="date" class="form-control" @input="handleInput('acquired_at')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="brand" value="Brand" :message="form.errors.brand"/>
                    <TextInput id="brand" v-model="form.brand" type="text" class="form-control" placeholder="Please enter brand" @input="handleInput('brand')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="model" value="Model" :message="form.errors.model"/>
                    <TextInput id="model" v-model="form.model" type="text" class="form-control" placeholder="Please enter model" @input="handleInput('model')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="price" value="Price" :message="form.errors.price"/>
                    <TextInput id="price" v-model="form.price" type="number" step="0.01" class="form-control" placeholder="0.00" @input="handleInput('price')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="maintenance_plan" value="Maintenance Plan" :message="form.errors.maintenance_plan"/>
                    <TextInput id="maintenance_plan" v-model="form.maintenance_plan" type="text" class="form-control" placeholder="Please enter maintenance plan" @input="handleInput('maintenance_plan')" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="maintenance_due" value="Maintenance Due" :message="form.errors.maintenance_due"/>
                    <TextInput id="maintenance_due" v-model="form.maintenance_due" type="date" class="form-control" @input="handleInput('maintenance_due')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <InputLabel for="specification" value="Specification / Description"/>
                    <div v-for="(spec,index) in form.specification" v-bind:key="index" class="d-flex align-items-center gap-2 mb-2">
                        <TextInput v-model="form.specification[index]" type="text" class="form-control" placeholder="e.g. Intel Core i5-1240P @ 1.70GHz" :light="true"/>
                        <button type="button" class="btn btn-sm btn-soft-danger" @click="removeSpec(index)">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-soft-primary" @click="addSpec">
                        <i class="ri-add-line align-bottom me-1"></i> Add Line
                    </button>
                </BCol>
                <BCol lg="12" class="mt-2">
                    <InputLabel for="remarks" value="Remarks" :message="form.errors.remarks"/>
                    <Textarea v-model="form.remarks" :light="true" @input="handleInput('remarks')"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit()" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, Textarea },
    props: ['dropdowns'],
    data(){
        return {
            id: null,
            editable: false,
            form: useForm({
                name: null,
                old_code: null,
                type_id: null,
                status_id: null,
                station_id: null,
                maintenance_plan: null,
                maintenance_due: null,
                remarks: null,
                acquired_at: null,
                brand: null,
                model: null,
                price: null,
                specification: [],
            }),
            showModal: false
        }
    },
    methods: {
        show(){
            this.editable = false;
            this.id = null;
            this.showModal = true;
        },
        edit(data){
            this.editable = true;
            this.id = data.id;

            this.form.name = data.name;
            this.form.old_code = data.old_code;
            this.form.type_id = data.type_id;
            this.form.status_id = data.status_id;
            this.form.station_id = data.station_id;
            this.form.maintenance_plan = data.maintenance_plan;
            this.form.maintenance_due = data.maintenance_due;
            this.form.remarks = data.remarks;
            this.form.acquired_at = data.acquired_at;
            this.form.brand = data.detail?.brand ?? null;
            this.form.model = data.detail?.model ?? null;
            this.form.price = data.detail?.price ?? null;
            this.form.specification = data.detail?.specification ? [...data.detail.specification] : [];

            this.showModal = true;
        },
        addSpec(){
            this.form.specification.push(null);
        },
        removeSpec(index){
            this.form.specification.splice(index,1);
        },
        submit(){
            if(this.editable){
                this.form.put(`/equipments/${this.id}`,{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.hide();
                    },
                });
            }else{
                this.form.post('/equipments',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit('update',true);
                        this.hide();
                    },
                });
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.clearErrors();
            this.form.reset();
            this.form.specification = [];
            this.editable = false;
            this.id = null;
            this.showModal = false;
        }
    }
}
</script>
