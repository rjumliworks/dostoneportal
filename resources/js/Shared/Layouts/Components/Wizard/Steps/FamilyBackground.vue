<template>
    <div>
        <h5 class="fs-14 text-primary mb-3">Family Background</h5>
        <div class="card-body bg-light-subtle p-4 rounded-3">
        <form class="customform p-2" style="min-height: 320px; max-height: calc(100vh - 420px); overflow-y: auto; overflow-x: hidden;">
            <BRow class="g-3">
                <BCol lg="12" class="mt-2">
                    <InputLabel value="Spouse"/>
                    <div class="input-group">
                        <TextInput v-model="form.spouse.lastname" type="text" class="form-control" placeholder="Lastname" :light="true"/>
                        <TextInput v-model="form.spouse.firstname" type="text" class="form-control" placeholder="Firstname" :light="true"/>
                        <TextInput v-model="form.spouse.middlename" type="text" class="form-control" placeholder="Middlename" :light="true"/>
                        <TextInput v-model="form.spouse.suffix" type="text" class="form-control" placeholder="Jr., Sr., III" :light="true"/>
                    </div>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel value="Occupation"/>
                    <TextInput v-model="form.spouse.occupation" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel value="Employer / Business Name"/>
                    <TextInput v-model="form.spouse.company" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel value="Contact No."/>
                    <TextInput v-model="form.spouse.contact_no" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="3" class="mt-0">
                    <InputLabel value="Business Address"/>
                    <TextInput v-model="form.spouse.address" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0 mb-0"><hr class="text-muted"/></BCol>
                <BCol lg="12" class="mt-n1">
                    <InputLabel value="Father"/>
                    <div class="input-group">
                        <TextInput v-model="form.parents.father.lastname" type="text" class="form-control" placeholder="Lastname" :light="true"/>
                        <TextInput v-model="form.parents.father.firstname" type="text" class="form-control" placeholder="Firstname" :light="true"/>
                        <TextInput v-model="form.parents.father.middlename" type="text" class="form-control" placeholder="Middlename" :light="true"/>
                        <TextInput v-model="form.parents.father.suffix" type="text" class="form-control" placeholder="Jr., Sr., III" :light="true"/>
                    </div>
                </BCol>

                <BCol lg="12" class="mt-0">
                    <InputLabel value="Mother's Maiden Name"/>
                    <div class="input-group">
                        <TextInput v-model="form.parents.mother.lastname" type="text" class="form-control" placeholder="Lastname" :light="true"/>
                        <TextInput v-model="form.parents.mother.firstname" type="text" class="form-control" placeholder="Firstname" :light="true"/>
                        <TextInput v-model="form.parents.mother.middlename" type="text" class="form-control" placeholder="Middlename" :light="true"/>
                        <TextInput v-model="form.parents.mother.suffix" type="text" class="form-control" placeholder="Jr., Sr., III" :light="true"/>
                    </div>
                </BCol>

                <BCol lg="12" class="mt-3 mb-2">
                    <div class="d-flex align-items-center justify-content-between bg-primary-subtle rounded-3 px-4 py-3">
                        <span class="fw-semibold fs-12 text-primary">Children</span>
                        <b-button variant="primary" size="sm" type="button" @click="addChild()"><i class="ri-add-line align-bottom"></i> Add Child</b-button>
                    </div>
                </BCol>
                <BCol lg="12" v-if="form.children.length === 0" class="text-muted fs-12">No children added yet.</BCol>
                <template v-else>
                    <BCol lg="8" class="mt-0">
                        <InputLabel value="Full Name"/>
                    </BCol>
                    <BCol lg="4" class="mt-0">
                        <InputLabel value="Birthdate"/>
                    </BCol>
                </template>
                <template v-for="(child, index) in form.children" :key="index">
                    <BCol lg="8" class="mt-0">
                        <TextInput v-model="child.name" type="text" class="form-control" :light="true"/>
                    </BCol>
                    <BCol lg="4" class="mt-0">
                         <div class="d-flex">
                            <div style="width: 100%;">
                                <TextInput v-model="child.birthdate" type="date" class="form-control" :light="true"/>
                            </div>
                            <div class="flex-shrink-0">
                                <b-button variant="soft-danger" type="button" class="ms-2" @click="removeChild(index)"><i class="ri-delete-bin-fill"></i></b-button>
                            </div>
                        </div>
                    </BCol>
                </template>
            </BRow>
        </form>
        </div>
    </div>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { TextInput, InputLabel },
    props: ['data'],
    data(){
        let backgrounds = this.data.userInformation?.backgrounds || {};
        if (typeof backgrounds === 'string') {
            try {
                backgrounds = JSON.parse(backgrounds) || {};
            } catch (e) {
                backgrounds = {};
            }
        }
        return {
            form: useForm({
                parents: {
                    father: {
                        lastname: backgrounds.parents?.father?.lastname ?? null,
                        firstname: backgrounds.parents?.father?.firstname ?? null,
                        middlename: backgrounds.parents?.father?.middlename ?? null,
                        suffix: backgrounds.parents?.father?.suffix ?? null,
                        address: backgrounds.parents?.father?.address ?? null,
                    },
                    mother: {
                        lastname: backgrounds.parents?.mother?.lastname ?? null,
                        firstname: backgrounds.parents?.mother?.firstname ?? null,
                        middlename: backgrounds.parents?.mother?.middlename ?? null,
                        suffix: backgrounds.parents?.mother?.suffix ?? null,
                        address: backgrounds.parents?.mother?.address ?? null,
                    },
                },
                spouse: {
                    lastname: backgrounds.spouse?.lastname ?? null,
                    firstname: backgrounds.spouse?.firstname ?? null,
                    middlename: backgrounds.spouse?.middlename ?? null,
                    suffix: backgrounds.spouse?.suffix ?? null,
                    address: backgrounds.spouse?.address ?? null,
                    contact_no: backgrounds.spouse?.contact_no ?? null,
                    occupation: backgrounds.spouse?.occupation ?? null,
                    company: backgrounds.spouse?.company ?? null,
                },
                children: backgrounds.children ? backgrounds.children.map(c => ({ ...c })) : [],
                option: 'family_background'
            })
        }
    },
    methods: {
        addChild(){
            this.form.children.push({ name: null, birthdate: null });
        },
        removeChild(index){
            this.form.children.splice(index, 1);
        },
        proceed(){
            this.form.post('/profile/pds', {
                preserveScroll: true,
                onSuccess: () => this.$emit('saved'),
                onError: () => this.$emit('failed'),
            });
        }
    }
}
</script>
