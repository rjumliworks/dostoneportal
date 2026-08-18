<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Guard Rotation' : 'Add Guard to Rotation'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <BRow class="g-3 mt-n1">
                        <BCol lg="12" class="mt-1">
                            <InputLabel value="Guard" :message="form.errors.user_id"/>
                            <Multiselect :options="guards" :searchable="true" label="name" v-model="form.user_id" placeholder="Select Guard" @input="handleInput('user_id')"/>
                        </BCol>
                        <BCol lg="12" class="mt-n1">
                            <InputLabel value="Rotation Order" :message="form.errors.order"/>
                            <TextInput v-model="form.order" type="number" min="1" class="form-control" placeholder="e.g. 1" @input="handleInput('order')" :light="true" />
                        </BCol>
                        <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                        <BCol lg="12" style="margin-top: 13px; margin-bottom: -10px;">
                            <div class="d-flex position-relative">
                                <div class="flex-shrink-0 fs-12" :class="(form.errors.is_active) ? 'text-danger' : ''">
                                    Include in the weekly rotation?
                                </div>
                                <div class="flex-grow-1 ms-2"></div>
                                <div class="flex-shrink-0">
                                    <div class="d-inline-block">
                                        <div class="custom-control custom-radio mb-3 ms-4">
                                            <input type="radio" id="rotationActiveYes" class="custom-control-input me-2" @input="handleInput('is_active')" :value="true" v-model="form.is_active">
                                            <label class="custom-control-label fs-12 fw-normal" for="rotationActiveYes">Yes</label>
                                        </div>
                                    </div>
                                    <div class="d-inline-block">
                                        <div class="custom-control custom-radio mb-3 ms-4">
                                            <input type="radio" id="rotationActiveNo" class="custom-control-input me-2" @input="handleInput('is_active')" :value="false" v-model="form.is_active">
                                            <label class="custom-control-label fs-12 fw-normal" for="rotationActiveNo">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </BCol>
                        <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                    </BRow>
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
export default {
    components: {InputLabel, TextInput, Multiselect },
    props: ['guards'],
    data(){
        return {
            form: useForm({
                id: null,
                user_id: null,
                order: null,
                is_active: true,
            }),
            showModal: false,
            editable: false
        }
    },
    methods: {
        show(){
            this.form.reset();
            this.form.is_active = true;
            this.editable = false;
            this.showModal = true;
        },
        edit(data){
            this.form.id = data.id;
            this.form.user_id = data.user_id;
            this.form.order = data.order;
            this.form.is_active = (data.is_active) ? true : false;
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put(`/shift-rotations/${this.form.id}`,{
                    preserveScroll: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.hide();
                    },
                });
            }else{
                this.form.post('/shift-rotations',{
                    preserveScroll: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.hide();
                    },
                });
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>
