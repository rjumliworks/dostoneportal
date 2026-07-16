<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 550px;" header-class="p-3 bg-light"
        title="Leave Credits" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
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
                            <h6 class="text-truncate fw-semibold fs-13 mb-0">{{ count }}<span>{{ count > 1 ? "days" : "day" }}</span></h6>
                        </div>
                    </div>
                </BCol>

                <BCol lg="12" class="mt-3 mb-2" v-if="types.length > 0">
                    <div class="input-group">
                        <div class="input-group-text"><i class="ri-checkbox-circle-fill"></i></div>
                        <input type="text" class="form-control" style="width: 74%;" :value="types[0].name" readonly />
                        <input type="text" class="form-control text-center" style="width: 14%;" :value="types[0].balance" readonly />
                    </div>
                </BCol>

                <BCol lg="12" style="max-height: 250px; overflow: auto;" class="mb-3 mt-3" id="my-modal-content2">
                    <div class="input-group" v-for="(type, index) in availableOptions" :key="type.value || index">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" :value="type.value" v-model="selectedValues" @change="distributeBorrow" />
                        </div>
                        <input type="text" class="form-control" style="width: 60%;" :value="type.name" readonly />
                        <input type="text" class="form-control text-center" style="width: 12%;" :value="type.balance" readonly />

                        <input type="number" class="form-control text-center" style="width: 14%;" v-model.number="type.borrow" :max="getMaxBorrow(type)" min="0" @input="validateBorrow(type)" @keydown.prevent/>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-2 mb-2" v-if="types.length > 0">
                    <div class="input-group">
                        <div class="input-group-text"><i class="ri-checkbox-circle-fill"></i></div>
                        <input type="text" class="form-control" style="width: 60%;" value="No. of days to borrow" readonly />
                        <input type="text" class="form-control text-center" style="width: 10%;" :value="shortfall" readonly />
                        <input type="text" class="form-control text-center" style="width: 13%;" :value="totalBorrowed" readonly />
                    </div>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button @click="submit('ok')" variant="primary" block>Update</b-button>
        </template>
    </b-modal>
</template>

<script>
    import Multiselect from "@vueform/multiselect";
    export default {
        props: ["options"],
        components: {
            Multiselect
        },
        data() {
            return {
                types: [], // main types passed in via show()
                count: 0,
                showModal: false,
                localOptions: [], // local clone of the props options so we can set borrow safely
                selectedValues: [] // array of type.value for checked checkboxes
            };
        },
        computed: {
            // options that are borrowable (exclude the main `types` entries)
            availableOptions() {
                return this.localOptions.filter(opt => !this.types.some(t => t.value === opt.value));
            },
            // list of actual option objects that are selected
            selectedItems() {
                return this.localOptions.filter(opt => this.selectedValues.includes(opt.value));
            },
            // sum borrow only on selected items (clamped to shortfall)
            totalBorrowed() {
                const sum = this.selectedItems.reduce((s, t) => s + (Number(t.borrow) || 0), 0);
                return Math.min(sum, this.shortfall);
            },
            // how many days need to be borrowed from others
            shortfall() {
                const mainBalance = this.types.length > 0 ? Number(this.types[0].balance || 0) : 0;
                return Math.max(0, Number(this.count || 0) - mainBalance);
            }
        },
        watch: {
            // if count or main types change while modal open, re-run distribution
            count() {
                this.distributeBorrow();
            },
            types: {
                handler() {
                    this.distributeBorrow();
                },
                deep: true
            }
        },
        methods: {
            getMaxBorrow(type) {
                // Max a user can input = the smaller of type.balance and remaining shortfall
                const remainingShortfall = this.shortfall - (this.totalBorrowed - (type.borrow || 0))
                return Math.min(type.balance, Math.max(remainingShortfall, 0))
            },
            validateBorrow(type) {
                // Clamp borrow value so it never exceeds allowed max
                const max = this.getMaxBorrow(type)
                if (type.borrow > max) {
                type.borrow = max
                }
                if (type.borrow < 0 || isNaN(type.borrow)) {
                type.borrow = 0
                }
            },
            // call this to show the modal and prepare a safe local copy of options
            show(data, count) {
                this.count = Number(count || 0);
                this.types = (data || []).map(t => ({
                    ...t,
                    balance: Number(t.balance || 0),
                    borrow: Number(t.borrow || 0)
                }));

                const opts = (this.options && this.options[0] && this.options[0].options) || [];
                this.localOptions = opts.map(o => ({
                    ...o,
                    balance: Number(o.balance || 0),
                    borrow: Number(o.borrow || 0)
                }));

                this.selectedValues = [];
                this.showModal = true;

                // ensure fields consistent
                this.distributeBorrow();
            },
            submit() {
                // if you want to send borrow info back, merge or pick fields from localOptions/selectedItems
                // Example: emit the selected borrow mapping
                const borrowed = this.selectedItems.map(s => ({
                    value: s.value,
                    name: s.name,
                    balance: s.balance,
                    available: s.balance-s.borrow,
                    borrow: Number(s.borrow || 0)
                }));
                this.$emit("update", {
                    types: this.types,
                    borrowed
                });
                this.hide();
            },
            hide() {
                this.showModal = false;
            },

            // helper to reset borrows on available options
            resetAvailableBorrows() {
                this.availableOptions.forEach(o => {
                    o.borrow = 0;
                });
            },

            // core distribution algorithm: equal share + leftover fill, respecting balances
            distributeBorrow() {
                // reset all available borrows first (so unchecked ones show 0)
                this.resetAvailableBorrows();

                const selected = this.selectedItems;
                const shortfall = Number(this.shortfall || 0);

                if (!selected.length || shortfall === 0) return;

                // initialize borrows
                selected.forEach(s => s.borrow = 0);

                let remaining = shortfall;
                const n = selected.length;
                const equalShare = Math.floor(shortfall / n);

                // first pass: give each up to equalShare (bounded by balance)
                selected.forEach(s => {
                    const cap = Number(s.balance || 0);
                    const assign = Math.min(equalShare, cap);
                    s.borrow = assign;
                    remaining -= assign;
                });

                // second pass: distribute leftover (one-by-one) respecting caps
                let idx = 0;
                while (remaining > 0) {
                    const s = selected[idx % selected.length];
                    const cap = Number(s.balance || 0);
                    const canAdd = cap - (Number(s.borrow) || 0);
                    if (canAdd > 0) {
                        const add = Math.min(remaining, canAdd);
                        s.borrow += add;
                        remaining -= add;
                    }
                    idx++;
                    // safety break (shouldn't be needed)
                    if (idx > selected.length * 5) break;
                }

                // final clamp to guarantee totalBorrowed <= shortfall
                let sum = selected.reduce((a, b) => a + (Number(b.borrow) || 0), 0);
                if (sum > shortfall) {
                    let extra = sum - shortfall;
                    for (let j = selected.length - 1; j >= 0 && extra > 0; j--) {
                        const s = selected[j];
                        const reducible = Math.min(s.borrow, extra);
                        s.borrow -= reducible;
                        extra -= reducible;
                    }
                }
            }
        }
    };

</script>
