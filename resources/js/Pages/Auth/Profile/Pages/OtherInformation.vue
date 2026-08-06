<template>
<div class="card bg-light-subtle shadow-none border">
    <div class="card-header bg-light-subtle">
        <div class="d-flex mb-n3">
            <div class="flex-shrink-0 me-3">
                <div style="height:2.5rem;width:2.5rem;">
                    <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                        <i class="ri-star-fill text-primary fs-24"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0 fs-14"><span class="text-body">Other Information</span></h5>
                <p class="text-muted text-truncate-two-lines fs-12">Special skills & hobbies, non-academic distinctions, and association memberships.</p>
            </div>
        </div>
    </div>
    <div class="card-body bg-white rounded-bottom" style="height: calc(100vh - 291px); overflow: auto;">
        <div class="row">
            <div class="col-md-4" v-for="section in sections" :key="section.type">
                <h5 class="mb-1 fs-14 text-primary">{{ section.label }}</h5>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" :placeholder="'Add '+section.label.toLowerCase()" v-model="section.value" @keyup.enter="add(section)">
                    <b-button variant="primary" @click="add(section)"><i class="ri-add-line"></i></b-button>
                </div>
                <b-list-group>
                    <BListGroupItem v-for="item in items(section.type)" :key="item.id" class="d-flex align-items-center justify-content-between">
                        <span class="fs-13">{{ item.value }}</span>
                        <a href="#" class="text-danger" @click.prevent="remove(item)"><i class="ri-close-line"></i></a>
                    </BListGroupItem>
                    <BListGroupItem v-if="items(section.type).length === 0" class="text-muted fs-12">None added yet.</BListGroupItem>
                </b-list-group>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import { router, useForm } from '@inertiajs/vue3';
export default {
    props: ['lists'],
    data(){
        return {
            sections: [
                { type: 'skill', label: 'Special Skills & Hobbies', value: null },
                { type: 'distinction', label: 'Non-Academic Distinctions / Recognition', value: null },
                { type: 'organization', label: 'Membership in Association / Organization', value: null },
            ]
        }
    },
    methods: {
        items(type){
            return this.lists.filter(l => l.type === type);
        },
        add(section){
            if(!section.value) return;
            const form = useForm({
                type: section.type,
                value: section.value,
                option: 'other_information'
            });
            form.post('/profile/pds', {
                preserveScroll: true,
                onSuccess: () => {
                    section.value = null;
                    router.reload({ only: ['otherInformation'], preserveScroll: true });
                },
            });
        },
        remove(item){
            if(!confirm('Remove this entry?')) return;
            router.delete('/profile/pds/'+item.id, {
                data: { option: 'other_information' },
                preserveScroll: true,
                onSuccess: () => router.reload({ only: ['otherInformation'], preserveScroll: true }),
            });
        }
    }
}
</script>
