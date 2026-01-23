<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 550px;" hide-footer header-class="p-3 bg-light" title="Request Date" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form>
            <BRow>
                <BCol lg="12" class="mt-n2">
                    <div class="d-flex border border-dashed rounded p-3">
                        <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                            <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                <i class="ri-calendar-2-fill"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="mb-1 text-muted fs-12">No. of working days applied for :</p>
                            <h6 class="text-truncate fw-semibold fs-13 mb-0">{{totalDays}} <span>{{ form.dates.length > 1 ? 'days' : 'day' }}</span></h6>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12" style="max-height: 250px; overflow: auto;" class="mb-3" id="my-modal-content2"> 
                    <div v-if="check" class="mt-3">
                        <div v-for="(date, index) in form.dates" :key="index" class="mb-2">
                            <div class="input-group mb-1">
                                <span class="input-group-text"> <i class="ri-calendar-line search-icon"></i></span>
                                <input type="text" :value="formatDateWithWeekday(date.date)" placeholder="Search Employee" class="form-control" readonly>
                                <Multiselect class="white" style="width: 40%;" :options="['Whole Day','AM','PM']" v-model="date.timeOfDay" 
                                :searchable="true" 
                                :allow-empty="false"  
                                :can-clear="false"
                                :append-to-body="true"
                                 append-to="#my-modal-content2"
                                @update:modelValue="submit"
                                placeholder="Select Status" />
                            </div>
                        </div>
                    </div>
                </BCol>
            </BRow>
        </form>
        <!-- <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Update</b-button>
        </template> -->
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
export default {
    components: { Multiselect },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                dates: [],
                option: 'leave'
            }),
            check: false,
            showModal: false
        }
    },
    computed: {
        totalDays() {
            return this.form.dates.reduce((sum, d) => {
            if (d.timeOfDay === 'AM' || d.timeOfDay === 'PM') {
                return sum + 0.5
            }
            if (d.timeOfDay === 'Whole Day') {
                return sum + 1
            }
            return sum
            }, 0)
        }
    },
    methods: { 
        show(data){
            this.form.dates = data;
            this.check = true;
            this.showModal = true;
        },
        submit(){
            this.$emit('update',this.form.dates);
            // this.hide();
        },
        formatDateWithWeekday(dateString) {
            const date = new Date(dateString);
            const day = date.getDate();
            const month = date.toLocaleString('en-US', { month: 'short' });
            const year = date.getFullYear();
            const weekday = date.toLocaleString('en-US', { weekday: 'long' });
            return `${month} ${day}, ${year} (${weekday})`;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>