<template>
     <!-- style="--vz-modal-width: 750px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Add Itinerary" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-2">
                    <InputLabel for="name" value="Place(s) Visited"/>
                    <TextInput id="name" v-model="form.place" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="name" value="Date"/>
                    <TextInput id="name" v-model="form.date" type="date" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel for="name" value="Departure"/>
                    <TextInput id="name" v-model="form.departure" type="time" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel for="name" value="Arrival"/>
                    <TextInput id="name" v-model="form.arrival" type="time" class="form-control" :light="true"/>
                </BCol>
                 <BCol lg="12" class="mt-0">
                    <InputLabel for="name" value="Means of Transportation"/>
                    <TextInput id="name" v-model="form.mode" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <hr class="text-muted mb-3"/>
                    Allowable Expenses (In Peso)
                    <hr class="text-muted mt-3"/>
                </BCol>
                <BCol lg="4" class="mt-n2">
                    <InputLabel value="Transportation"/>
                    <Amount @amount="setAmount('transportation', $event)" :readonly="false"/>
                </BCol>
                <BCol lg="4" class="mt-n2">
                    <InputLabel value="Per Diems"/>
                    <Amount @amount="setAmount('perdiem', $event)" :readonly="false"/>
                </BCol>
                <BCol lg="4" class="mt-n2">
                    <InputLabel value="Others"/>
                    <Amount @amount="setAmount('others', $event)" :readonly="false"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import Amount from '@/Shared/Components/Forms/Amount.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Amount },
    data(){
        return {
            currentUrl: window.location.origin,
            form: {
                place: null,
                date: null,
                arrival: null,
                departure: null,
                mode: null,
                transportation: null,
                perdiem: null,
                others: null,
            },
            showModal: false
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        submit(){
            this.$emit('success',this.form);
            this.form = {
                place: null,
                date: null,
                arrival: null,
                departure: null,
                mode: null,
                transportation: null,
                perdiem: null,
                others: null,
            },
            this.showModal = false;
        },
        setAmount(type, val) {
            // const numericVal = parseFloat(val.replace(/[^0-9.-]+/g, '')) || 0;
            this.form[type] = val;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>