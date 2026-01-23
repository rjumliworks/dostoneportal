<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-light" title="Add Document" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 p-2">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="name" value="Document Type" :message="form.errors.type_id"/>
                    <Multiselect
                        v-model="form.type_id" 
                        :options="attachments"
                        label="name"
                         @input="handleInput('type_id')"
                        placeholder="Select type"
                    />
                </BCol>
                <BCol lg="12" class="mt-0 mb-0">
                    <hr class="text-muted"/>
                </BCol>
                <template v-if="form.type_id == 176">
                    <BCol lg="6" class="mt-n2">
                        <InputLabel value="Persons Contacted"/>
                        <TextInput v-model="tar.person" type="text" class="form-control" placeholder="Please enter st.,road" @input="handleInput('address')" :light="true" />
                    </BCol>
                    <BCol lg="6" class="mt-n2">
                        <InputLabel value="Designation, Office/Agency Address"/>
                        <TextInput v-model="tar.designation" type="text" class="form-control" placeholder="Please enter st.,road" @input="handleInput('address')" :light="true" />
                    </BCol>
                    <BCol lg="12" class="mt-0">
                        <InputLabel for="name" value="Output / Impact /Result" :message="form.errors.ouput"/>
                        <div class="position-relative">
                            <textarea
                                id="attribute" v-model="tar.output" rows="1"
                                class="form-control" placeholder="Please enter details"
                                style="background-color: #f5f6f7; padding-bottom: 28px;" 
                            ></textarea>

                            <span class="position-absolute" style="top: -18px; font-size: 11px; right: 8px; background-color: white; cursor: pointer; color: #0d6efd; font-weight: 500;" @click="improveText" :class="{ 'text-muted': loading }">
                                <span v-if="loading">Improving...</span>
                                <span v-else>Improve with AI</span>
                            </span>
                        </div>
                    </BCol>
                    <BCol lg="12" class="mt-0 mb-0">
                        <hr class="text-muted"/>
                    </BCol>
                    <BCol lg="3" class="mt-n2">
                        <InputLabel value="Traveling Expenses"/>
                        <Amount @amount="setAmount('expenses', $event)" :readonly="false"/>
                    </BCol>
                    <BCol lg="3" class="mt-n2">
                        <InputLabel value="Less Amount"/>
                        <Amount @amount="setAmount('less', $event)" :readonly="false"/>
                    </BCol>
                    <BCol lg="3" class="mt-n2">
                        <InputLabel value="Others"/>
                        <Amount @amount="setAmount('others', $event)" :readonly="false"/>
                    </BCol>
                    <BCol lg="3" class="mt-n2">
                        <InputLabel value="Total"/>
                        <Amount :value="totalAmount" :readonly="true"/>
                    </BCol>
                    <BCol lg="12" class="mt-n1 mb-0">
                        <hr class="text-muted"/>
                    </BCol>
                    <BCol lg="12" class="mt-n2">
                        <InputLabel for="name" value="Proposed Follow-up Activities" :message="form.errors.ouput"/>
                        <div class="position-relative">
                            <textarea
                                id="attribute" v-model="tar.output" rows="1"
                                class="form-control" placeholder="Please enter details"
                                style="background-color: #f5f6f7; padding-bottom: 28px;" 
                            ></textarea>

                            <span class="position-absolute" style="top: -18px; font-size: 11px; right: 8px; background-color: white; cursor: pointer; color: #0d6efd; font-weight: 500;" @click="improveText" :class="{ 'text-muted': loading }">
                                <span v-if="loading">Improving...</span>
                                <span v-else>Improve with AI</span>
                            </span>
                        </div>
                    </BCol>
                </template>
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
import Multiselect from "@vueform/multiselect";
import Amount from '@/Shared/Components/Forms/Amount.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, Amount },
    props: ['attachments'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                type_id: null,
                status_id: 42,
                request_id: null,
                data: null,
                option: 'document'
            }),
            tar: {
                person: null,
                designation: null,
                output: null,
                expenditures: {
                    expenses: 0,
                    less: 0,
                    others: 0
                },
                followup: null
            },
            loading: false,
            showModal: false
        }
    },
    computed: {
        totalAmount() {
            const { expenses, less, others } = this.tar.expenditures;
            return (Number(expenses) || 0) + (Number(less) || 0) + (Number(others) || 0);
        }
    },
    watch: {
        "form.type_id"(newVal){
            switch(newVal){
                case '176':
                    this.form.data = this.tar;
                break;
            }
        },
    },
    methods: { 
        setAmount(type, val) {
            const numericVal = parseFloat(val.replace(/[^0-9.-]+/g, '')) || 0;
            this.tar.expenditures[type] = numericVal;
        },
        show(){
            // this.form.request_id = id;
            this.showModal = true;
        },
        async improveText() {
            this.loading = true;
            axios.post('/improve', {
                purpose: this.tar.output, 
            })
            .then(res => {
                this.tar.output = res.data.improved.trim();
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                this.loading = false;
            });
        },
        submit(){
            this.form.post('/requests',{
                preserveScroll: true,
                // forceFormData: true, 
                onSuccess: (response) => {
                    this.form.clearErrors();
                    this.form.reset();
                    this.hide();
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