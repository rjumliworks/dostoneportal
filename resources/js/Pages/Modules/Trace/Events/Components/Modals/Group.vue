<template>
    <!-- style="--vz-modal-width: 750px;" -->
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Select Group" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 p-2 mb-2">
                <div class="col-sm-12">
                    <div data-bs-toggle="collapse" data-bs-target="#paymentmethodCollapse.show" aria-expanded="false"
                        aria-controls="paymentmethodCollapse">
                        <div class="form-check card-radio">
                            <input id="paymentMethod01" name="paymentMethod" v-model="selectedType" value="all" type="radio" class="form-check-input">
                            <label class="form-check-label" for="paymentMethod01">
                                <span class="fs-16 text-muted me-2"><i class="ri-team-fill align-bottom"></i></span>
                                <span class="fs-14 text-wrap">All Employees</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div data-bs-toggle="collapse" data-bs-target="#paymentmethodCollapse" aria-expanded="true"
                        aria-controls="paymentmethodCollapse">
                        <div class="form-check card-radio">
                            <input id="paymentMethod02" name="paymentMethod" v-model="selectedType" value="group" type="radio" class="form-check-input">
                            <label class="form-check-label" for="paymentMethod02">
                                <span class="fs-16 text-muted me-2"><i class="ri-group-2-fill align-bottom"></i></span>
                                <span class="fs-14 text-wrap">Select Group of Employees</span>
                            </label>
                        </div>
                    </div>
                </div>
            </BRow>
        </form> 
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="continueToForm()" variant="primary" block>Save</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            selectedType: null
        }
    },
    methods: { 
        show(){
            this.showModal = true;
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