<template>
    <div>
        <h5 class="fs-14 text-primary mb-3">Other Information</h5>
        <div class="row" style="height: calc(100vh - 480px); overflow: auto;">
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
    </div>
</template>
<script>
import { router, useForm } from '@inertiajs/vue3';
export default {
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
        proceed(){
            this.$emit('saved');
        }
    }
}
</script>
