<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-light"
        title="Add Accomplishment Report" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3 p-2 mb-n4">
                <BCol lg="12" class="mb-4 mt-1" v-if="owner">
                    <div class="table-responsive bg-white border rounded">
                        <table class="table align-middle table-bordered table-centered mb-0">
                            <thead class="table-light">
                                <tr class="fs-12 text-center">
                                    <th style="width: 50%;">Target Deliverables</th>
                                    <th style="width: 50%;">Actual Accomplishments</th>
                                    <th style="width: 5%;">
                                        <b-button variant="primary" @click="addTarget">
                                            <i class="ri-add-circle-fill"></i>
                                        </b-button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in targets" :key="index" class="text-center">
                                    <td>
                                        <input type="text" class="form-control form-control mt-0 mb-0" v-model="item.target" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control  mt-0 mb-0" v-model="item.accomplishment"/>
                                    </td>
                                    <td class="text-center align-middle">
                                        <b-button variant="outline-danger" class="mt-0 mb-0" @click="removeTarget(index)" :disabled="targets.length == 1">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </b-button>
                                    </td>
                                </tr>
                                <tr v-if="!targets.length">
                                    <td colspan="3" class="text-center text-muted py-3 fs-12">
                                        No targets or accomplishments added yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </BCol>
            </BRow>
        </form>

        <template #footer>
            <b-button @click="hide" variant="light" block>Close</b-button>
            <b-button @click="save" variant="primary" block>Save</b-button>
        </template>
    </b-modal>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
    export default {
        data() {
            return {
                form: useForm({
                    id: null,
                    targets: null,
                    option: 'overtime'
                }),
                id: null,
                showModal: false,
                owner: true,
                targets: [] // [{ target: '', accomplishment: '' }]
            };
        },
        methods: {
            show(id, targets) {
                this.form.id = id;
                this.targets =
                    typeof targets === "string" ?
                    JSON.parse(targets || "[]") :
                    targets || [];
                // Ensure at least one row is present
                if (!this.targets.length) {
                    this.targets.push({
                        target: "",
                        accomplishment: ""
                    });
                }
                this.showModal = true;
            },

            hide() {
                this.showModal = false;
            },

            addTarget() {
                this.targets.push({
                    target: "",
                    accomplishment: ""
                });
            },

            removeTarget(index) {
                this.targets.splice(index, 1);
            },

            save() {
                this.form.targets = this.targets;
                this.form.put('/requests/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.$emit("saveTargets", {
                            id: this.id,
                            targets: this.targets
                        });
                        this.form.clearErrors();
                        this.form.reset();
                        this.hide();
                    },
                });
            }
        }
    };

</script>
