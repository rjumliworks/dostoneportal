<template>
    <div>
        <h5 class="fs-14 text-primary mb-3">Other Information</h5>
        <div class="row mb-4">
            <div class="col-md-4" v-for="section in sections" :key="section.type">
                <h6 class="mb-1 fs-13 text-body">{{ section.label }}</h6>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" :placeholder="'Add '+section.label.toLowerCase()" v-model="section.value" @keyup.enter="add(section)">
                    <b-button variant="primary" type="button" @click="add(section)"><i class="ri-add-line"></i></b-button>
                </div>
                <b-list-group>
                    <BListGroupItem v-for="item in items(section.type)" :key="item.id" class="d-flex align-items-center justify-content-between">
                        <span class="fs-13">{{ item.value }}</span>
                        <a href="#" class="text-danger" @click.prevent="removeOther(item)"><i class="ri-close-line"></i></a>
                    </BListGroupItem>
                    <BListGroupItem v-if="items(section.type).length === 0" class="text-muted fs-12">None added yet.</BListGroupItem>
                </b-list-group>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fs-14 text-primary mb-0">References</h5>
            <b-button variant="primary" size="sm" type="button" @click="$refs.modal.show()"><i class="ri-add-circle-fill align-bottom me-1"></i> Add</b-button>
        </div>
        <div class="table-responsive table-card">
            <table class="table align-middle table-striped table-centered mb-0">
                <thead class="table-primary thead-fixed">
                    <tr class="fs-11"><th>Name</th><th>Address</th><th>Contact</th><th style="width: 8%;"></th></tr>
                </thead>
                <tbody class="fs-12" v-if="references.length > 0">
                    <tr v-for="row in references" :key="row.id">
                        <td>{{ row.name }}</td>
                        <td>{{ row.address }}</td>
                        <td>{{ row.contact }}</td>
                        <td class="text-end">
                            <b-button @click="$refs.modal.edit(row)" variant="soft-warning" size="sm" class="me-1" type="button"><i class="ri-pencil-fill align-bottom"></i></b-button>
                            <b-button @click="removeReference(row)" variant="soft-danger" size="sm" type="button"><i class="ri-delete-bin-fill align-bottom"></i></b-button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr><td colspan="4" class="text-center text-muted fs-12 py-3">No references added yet — you can skip this and add it later.</td></tr>
                </tbody>
            </table>
        </div>
        <Modal ref="modal" @success="$emit('refresh')"/>
    </div>
</template>
<script>
import { router, useForm } from '@inertiajs/vue3';
import Modal from '@/Pages/Auth/Profile/Pages/Modals/Reference.vue';
export default {
    components: { Modal },
    props: ['data'],
    data(){
        return {
            sections: [
                { type: 'skill', label: 'Special Skills & Hobbies', value: null },
                { type: 'distinction', label: 'Non-Academic Distinctions / Recognition', value: null },
                { type: 'organization', label: 'Membership in Association / Organization', value: null },
            ]
        }
    },
    computed: {
        otherInformation(){ return this.data.otherInformation || []; },
        references(){ return this.data.references || []; },
    },
    methods: {
        items(type){
            return this.otherInformation.filter(l => l.type === type);
        },
        add(section){
            if (!section.value) return;
            const form = useForm({ type: section.type, value: section.value, option: 'other_information' });
            form.post('/profile/pds', {
                preserveScroll: true,
                onSuccess: () => { section.value = null; this.$emit('refresh'); },
            });
        },
        removeOther(item){
            if (!confirm('Remove this entry?')) return;
            router.delete('/profile/pds/'+item.id, {
                data: { option: 'other_information' },
                preserveScroll: true,
                onSuccess: () => this.$emit('refresh'),
            });
        },
        removeReference(row){
            if (!confirm('Remove this reference?')) return;
            router.delete('/profile/pds/'+row.id, {
                data: { option: 'reference' },
                preserveScroll: true,
                onSuccess: () => this.$emit('refresh'),
            });
        },
        proceed(){
            this.$emit('saved');
        }
    }
}
</script>
